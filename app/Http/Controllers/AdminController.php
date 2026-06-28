<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\UserSubscription;
use App\Models\ProductPromotion;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // --- Marketplace Overview ---
        $totalUsers          = User::where('role', '!=', 'admin')->count();
        $totalSellers        = User::where('role', '!=', 'admin')->has('products')->count();
        $activePremium       = UserSubscription::where('status', 'active')
                                ->where('expires_at', '>', now())->count();
        $activePromotions    = ProductPromotion::where('status', 'active')
                                ->where('expires_at', '>', now())->count();

        // --- Payment Overview ---
        $pendingPayments     = Payment::where('status', 'pending')->count();
        $verifiedPayments    = Payment::where('status', 'verified')->count();
        $rejectedPayments    = Payment::where('status', 'rejected')->count();

        // --- Revenue Overview ---
        $revenueToday        = Payment::where('status', 'verified')
                                ->whereDate('approved_at', today())->sum('amount');
        $revenueThisMonth    = Payment::where('status', 'verified')
                                ->whereMonth('approved_at', now()->month)
                                ->whereYear('approved_at', now()->year)->sum('amount');
        $revenueThisYear     = Payment::where('status', 'verified')
                                ->whereYear('approved_at', now()->year)->sum('amount');
        $revenueTotal        = Payment::where('status', 'verified')->sum('amount');

        // --- Activity Overview ---
        $approvedToday       = Payment::where('status', 'verified')
                                ->whereDate('approved_at', today())->count();
        $rejectedToday       = Payment::where('status', 'rejected')
                                ->whereDate('approved_at', today())->count();
        $paymentsThisWeek    = Payment::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $newPremiumThisMonth = UserSubscription::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)->count();
        $newPromoThisMonth   = ProductPromotion::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)->count();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalSellers', 'activePremium', 'activePromotions',
            'pendingPayments', 'verifiedPayments', 'rejectedPayments',
            'revenueToday', 'revenueThisMonth', 'revenueThisYear', 'revenueTotal',
            'approvedToday', 'rejectedToday', 'paymentsThisWeek',
            'newPremiumThisMonth', 'newPromoThisMonth'
        ));
    }

    public function paymentHistory(Request $request)
    {
        $query = Payment::with(['user', 'approver', 'subscriptionOrder.plan', 'promotionOrder.package']);

        // Filter: Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter: Status
        if ($request->filled('status') && in_array($request->status, ['pending', 'verified', 'rejected'])) {
            $query->where('status', $request->status);
        }

        // Filter: Payment Type
        if ($request->filled('type')) {
            if ($request->type === 'premium') {
                $query->whereNotNull('subscription_order_id');
            } elseif ($request->type === 'promotion') {
                $query->whereNotNull('promotion_order_id');
            }
        }

        // Filter: User (name or NIM)
        if ($request->filled('user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%')
                  ->orWhere('nim', 'like', '%' . $request->user . '%');
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->get();
        $filters  = $request->only(['date_from', 'date_to', 'status', 'type', 'user']);

        return view('admin.payment_history', compact('payments', 'filters'));
    }

    public function pendingPayments()
    {
        $payments = Payment::with([
            'user', 
            'subscriptionOrder.plan', 
            'promotionOrder.product', 
            'promotionOrder.package'
        ])
        ->where('status', 'pending')
        ->orderBy('created_at', 'asc')
        ->get();

        return view('admin.payments', compact('payments'));
    }

    public function approvePayment($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status !== 'pending') {
            return redirect()->route('admin.payments')->with('toast_error', 'Pembayaran ini sudah diproses.');
        }

        DB::transaction(function () use ($payment) {
            $payment->update([
                'status' => 'verified',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            if ($payment->subscription_order_id) {
                $order = $payment->subscriptionOrder;
                
                // Expiry duration dari plan
                $duration = $order->plan->duration_days;

                UserSubscription::create([
                    'user_id' => $order->user_id,
                    'plan_id' => $order->plan_id,
                    'payment_id' => $payment->id,
                    'status' => 'active',
                    'started_at' => now(),
                    'expires_at' => now()->addDays($duration),
                ]);

                $order->update([
                    'status' => 'completed',
                ]);
            } elseif ($payment->promotion_order_id) {
                $order = $payment->promotionOrder;
                
                // Expiry duration dari package
                $duration = $order->package->duration_days;

                ProductPromotion::create([
                    'product_id' => $order->product_id,
                    'user_id' => $order->user_id,
                    'package_id' => $order->package_id,
                    'payment_id' => $payment->id,
                    'status' => 'active',
                    'started_at' => now(),
                    'expires_at' => now()->addDays($duration),
                ]);

                $order->update([
                    'status' => 'completed',
                ]);
            }
        });

        return redirect()->route('admin.payments')->with('toast_success', 'Pembayaran berhasil disetujui. Layanan telah diaktifkan.');
    }

    public function rejectPayment(Request $request, $id)
    {
        $request->validate([
            'rejected_reason' => 'required|string|max:500',
        ]);

        $payment = Payment::findOrFail($id);

        if ($payment->status !== 'pending') {
            return redirect()->route('admin.payments')->with('toast_error', 'Pembayaran ini sudah diproses.');
        }

        // Simpan penolakan
        $payment->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejected_reason' => $request->rejected_reason,
        ]);

        // Catatan: Order (SubscriptionOrder / PromotionOrder) tetap berstatus pending
        // sehingga seller dapat mengupload ulang bukti pembayaran.

        return redirect()->route('admin.payments')->with('toast_success', 'Pembayaran ditolak. Alasan penolakan berhasil dikirim.');
    }
}
