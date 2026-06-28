<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionOrder;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::all();
        $user = Auth::user();
        
        $activeSubscription = null;
        $pendingOrder = null;

        if ($user) {
            $activeSubscription = $user->userSubscription;
            $pendingOrder = $user->subscriptionOrders()
                ->where('status', 'pending')
                ->first();
        }

        return view('premium.index', compact('plans', 'activeSubscription', 'pendingOrder'));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $user = Auth::user();

        // Cek jika sudah premium
        if ($user->isPremium()) {
            return redirect()->route('premium.index')->with('toast_error', 'Anda sudah memiliki langganan aktif.');
        }

        // Cek jika ada order pending sebelumnya, arahkan ke konfirmasi
        $pendingOrder = $user->subscriptionOrders()
            ->where('status', 'pending')
            ->first();

        if ($pendingOrder) {
            return redirect()->route('premium.confirm', ['id' => $pendingOrder->id]);
        }

        $order = $user->subscriptionOrders()->create([
            'plan_id' => $request->plan_id,
            'status' => 'pending',
        ]);

        return redirect()->route('premium.confirm', ['id' => $order->id]);
    }

    public function confirmPayment($id)
    {
        $order = SubscriptionOrder::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return redirect()->route('premium.index')->with('toast_error', 'Order ini sudah tidak aktif atau selesai.');
        }

        return view('premium.subscribe_confirm', compact('order'));
    }

    public function submitPayment(Request $request, $id)
    {
        $order = SubscriptionOrder::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return redirect()->route('premium.index')->with('toast_error', 'Order ini sudah tidak aktif.');
        }

        // Cek jika sudah pernah submit payment untuk order ini
        if ($order->payment) {
            return redirect()->route('premium.index')->with('toast_error', 'Konfirmasi pembayaran untuk order ini sedang diproses.');
        }

        $request->validate([
            'sender_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:bank_transfer,qris',
            'transfer_time' => 'required|date',
            'screenshot' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('screenshot')) {
            $path = $request->file('screenshot')->store('payments', 'public');
        }

        $referenceCode = 'PAY-SUB-' . strtoupper(uniqid());

        Payment::create([
            'user_id' => Auth::id(),
            'subscription_order_id' => $order->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'paid_at' => $request->transfer_time,
            'reference_code' => $referenceCode,
            'payment_details' => [
                'sender_name' => $request->sender_name,
                'screenshot_path' => $path,
            ],
        ]);

        return redirect()->route('premium.index')->with('toast_success', 'Konfirmasi pembayaran berhasil dikirim. Admin akan segera memverifikasinya.');
    }

    public function cancel($id)
    {
        $order = SubscriptionOrder::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        // Hapus payment pending jika ada
        if ($order->payment) {
            if (isset($order->payment->payment_details['screenshot_path'])) {
                Storage::disk('public')->delete($order->payment->payment_details['screenshot_path']);
            }
            $order->payment->delete();
        }

        $order->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('premium.index')->with('toast_success', 'Pesanan langganan dibatalkan.');
    }
}
