@extends('layouts.base_layout')

@section('title', 'Promosi Saya - SellOn')

@section('content')
<main class="grow max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-screen bg-brand-secondary">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-serif font-bold text-brand-main">Promosi Saya</h1>
            <p class="text-brand-muted text-sm mt-1">Daftar riwayat pesanan promosi dan iklan produk Anda.</p>
        </div>
        <a href="{{ route('promote.create') }}" class="btn btn-primary px-6 py-2.5 rounded-xl font-bold text-sm cursor-pointer">
            Promosikan Produk Baru <i class="fa-solid fa-plus ml-1.5 text-xs"></i>
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-16 bg-white rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center mb-4 mx-auto">
                <i class="fa-solid fa-rectangle-ad text-brand-accent text-3xl"></i>
            </div>
            <h3 class="text-lg font-bold text-brand-main mb-2">Belum Ada Riwayat Promosi</h3>
            <p class="text-brand-muted text-sm max-w-sm mx-auto leading-relaxed px-4 mb-8">
                Tingkatkan penjualan produk Anda dengan mempromosikannya agar tampil di halaman depan.
            </p>
            <a href="{{ route('promote.create') }}" class="btn btn-primary px-8 py-3 rounded-full font-bold">
                Promosikan Sekarang
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($orders as $order)
                @php
                    $isUserPremium = Auth::user()->isPremium();
                    $price = $isUserPremium ? $order->package->price_premium : $order->package->price_regular;
                @endphp
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    {{-- Detail Produk & Paket --}}
                    <div class="flex items-center gap-4 min-w-0">
                        <img src="{{ $order->product->display_image }}" alt="{{ $order->product->name }}" class="w-16 h-16 object-cover rounded-2xl shrink-0 border border-slate-100">
                        <div class="min-w-0">
                            <span class="inline-block px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold mb-1">
                                Paket {{ $order->package->name }}
                            </span>
                            <h3 class="text-lg font-bold text-slate-800 truncate">{{ $order->product->name }}</h3>
                            <p class="text-xs text-brand-muted mt-0.5">
                                Dibuat pada: {{ $order->created_at->format('d M Y, H:i') }} | Harga: <strong>Rp{{ number_format($price, 0, ',', '.') }}</strong>
                            </p>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full md:w-auto shrink-0 justify-between md:justify-end">
                        <div>
                            @if($order->status == 'completed')
                                @php
                                    // Ambil product promotion yang aktif
                                    $promo = $order->product->productPromotion;
                                @endphp
                                @if($promo && $promo->isActive())
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-success/10 text-brand-success text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-brand-success animate-pulse"></span> Iklan Aktif
                                    </span>
                                    <p class="text-[10px] text-slate-500 mt-1">Berakhir: {{ $promo->expires_at->format('d M Y, H:i') }}</p>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold">
                                        Iklan Selesai / Expired
                                    </span>
                                @endif
                            @elseif($order->status == 'cancelled')
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-600 text-xs font-bold">
                                    Dibatalkan
                                </span>
                            @else
                                {{-- Status: Pending --}}
                                @if(!$order->payment)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                                        Menunggu Pembayaran
                                    </span>
                                @elseif($order->payment->status == 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                        Menunggu Verifikasi Admin
                                    </span>
                                    <p class="text-[10px] text-slate-400 mt-1">Ref: {{ $order->payment->reference_code }}</p>
                                @elseif($order->payment->status == 'rejected')
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-600 text-xs font-bold mb-1">
                                            Pembayaran Ditolak
                                        </span>
                                        <p class="text-[10px] text-red-500 max-w-[200px] leading-relaxed">
                                            Alasan: "{{ $order->payment->rejected_reason }}"
                                        </p>
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- Aksi --}}
                        <div class="flex items-center gap-2">
                            @if($order->status == 'pending')
                                @if(!$order->payment || $order->payment->status == 'rejected')
                                    <a href="{{ route('promote.confirm', ['id' => $order->id]) }}" class="btn btn-primary px-4 py-2 rounded-xl text-xs font-bold cursor-pointer">
                                        Bayar Sekarang
                                    </a>
                                @endif
                                <form action="{{ route('promote.cancel', ['id' => $order->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan promosi ini?');">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost px-3 py-2 text-xs text-red-500 font-bold hover:bg-red-50 rounded-xl cursor-pointer">
                                        Batal
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</main>
@endsection
