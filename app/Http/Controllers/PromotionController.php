<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromotionPackage;
use App\Models\PromotionOrder;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    public function index()
    {
        $packages = PromotionPackage::all();
        return view('promote.index', compact('packages'));
    }

    public function create()
    {
        $user = Auth::user();
        // Ambil produk user yang sedang tidak dalam promosi aktif
        $products = Product::where('user_id', $user->id)
            ->whereDoesntHave('productPromotion', function ($query) {
                $query->where('status', 'active')
                    ->where('expires_at', '>', now());
            })
            ->get();

        $packages = PromotionPackage::all();

        return view('promote.select_product', compact('products', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'package_id' => 'required|exists:promotion_packages,id',
        ]);

        $user = Auth::user();
        
        // Verifikasi kepemilikan produk
        $product = Product::where('user_id', $user->id)
            ->where('id', $request->product_id)
            ->firstOrFail();

        // Cek jika produk sudah dipromosikan aktif
        if ($product->isPromoted()) {
            return redirect()->route('promote.index')->with('toast_error', 'Produk ini sedang dipromosikan.');
        }

        // Cek jika ada order pending untuk produk ini
        $pendingOrder = PromotionOrder::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('status', 'pending')
            ->first();

        if ($pendingOrder) {
            return redirect()->route('promote.confirm', ['id' => $pendingOrder->id]);
        }

        $order = PromotionOrder::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'package_id' => $request->package_id,
            'status' => 'pending',
        ]);

        return redirect()->route('promote.confirm', ['id' => $order->id]);
    }

    public function confirmPayment($id)
    {
        $order = PromotionOrder::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return redirect()->route('promote.my')->with('toast_error', 'Order ini sudah tidak aktif.');
        }

        return view('promote.order_confirm', compact('order'));
    }

    public function submitPayment(Request $request, $id)
    {
        $order = PromotionOrder::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return redirect()->route('promote.my')->with('toast_error', 'Order ini sudah tidak aktif.');
        }

        // Cek jika sudah submit payment
        if ($order->payment) {
            return redirect()->route('promote.my')->with('toast_error', 'Konfirmasi pembayaran sedang diproses.');
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

        $referenceCode = 'PAY-PROM-' . strtoupper(uniqid());

        Payment::create([
            'user_id' => Auth::id(),
            'promotion_order_id' => $order->id,
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

        return redirect()->route('promote.my')->with('toast_success', 'Konfirmasi pembayaran berhasil dikirim. Admin akan segera memverifikasinya.');
    }

    public function myPromotions()
    {
        $user = Auth::user();
        
        $orders = PromotionOrder::with(['product', 'package', 'payment'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('promote.my_promotions', compact('orders'));
    }

    public function cancel($id)
    {
        $order = PromotionOrder::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        if ($order->payment) {
            if (isset($order->payment->payment_details['screenshot_path'])) {
                Storage::disk('public')->delete($order->payment->payment_details['screenshot_path']);
            }
            $order->payment->delete();
        }

        $order->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('promote.my')->with('toast_success', 'Pesanan promosi dibatalkan.');
    }
}
