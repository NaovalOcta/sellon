@extends('layouts.base_layout')

@section('title', 'Premium Seller - SellOn')

@section('content')
<main class="grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-screen bg-brand-secondary flex flex-col justify-center items-center">
    {{-- Status Section --}}
    <div class="text-center max-w-3xl mx-auto mb-10">
        <h1 class="text-3xl font-serif font-bold text-brand-main mb-3">
            Premium Seller
        </h1>
        <p class="text-brand-muted text-sm leading-relaxed">
            Kelola status langganan Premium Seller Anda untuk menikmati berbagai keuntungan eksklusif di toko Anda.
        </p>
    </div>

    {{-- Pricing Card --}}
    <div class="w-full max-w-md bg-white rounded-3xl border border-slate-100 shadow-lg overflow-hidden">
        <div class="p-8 text-center border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-2xl font-bold text-brand-main mb-2">Paket MVP Premium</h3>
            <p class="text-brand-muted text-sm">Akses penuh ke semua fitur premium seller</p>
            <div class="mt-6 flex justify-center items-baseline">
                <span class="text-4xl font-extrabold text-brand-main">Rp15.000</span>
                <span class="text-brand-muted ml-2 text-sm">/ 30 Hari</span>
            </div>
            <p class="text-brand-highlight text-xs mt-3 font-semibold">* Paket lain dapat ditambahkan di kemudian hari.</p>
        </div>
        <div class="p-8">
            <ul class="space-y-4 mb-8">
                <li class="flex items-center text-sm text-slate-700">
                    <i class="fa-solid fa-circle-check text-brand-success mr-3"></i>
                    Batas Kapasitas 100 Produk
                </li>
                <li class="flex items-center text-sm text-slate-700">
                    <i class="fa-solid fa-circle-check text-brand-success mr-3"></i>
                    Badge Identitas Toko Premium
                </li>
                <li class="flex items-center text-sm text-slate-700">
                    <i class="fa-solid fa-circle-check text-brand-success mr-3"></i>
                    Akses Dashboard Statistik
                </li>
                <li class="flex items-center text-sm text-slate-700">
                    <i class="fa-solid fa-circle-check text-brand-success mr-3"></i>
                    Diskon Promoted Listing 30%
                </li>
            </ul>

            @guest
                <a href="{{ route('login') }}" class="btn btn-primary w-full py-3.5 rounded-2xl font-bold text-center">
                    Login Untuk Berlangganan
                </a>
            @else
                @if($activeSubscription)
                    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 text-center">
                        <span class="text-brand-accent text-sm font-bold block mb-1">👑 Akun Premium Aktif!</span>
                        <span class="text-slate-600 text-xs">Berlaku sampai: {{ $activeSubscription->expires_at->format('d M Y H:i') }}</span>
                    </div>
                @elseif($pendingOrder)
                    <div class="space-y-3">
                        <a href="{{ route('premium.confirm', ['id' => $pendingOrder->id]) }}" class="btn btn-primary w-full py-3.5 rounded-2xl font-bold text-center">
                            Lanjutkan Konfirmasi Bayar
                        </a>
                        <form action="{{ route('premium.cancel', ['id' => $pendingOrder->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pendaftaran ini?');">
                            @csrf
                            <button type="submit" class="btn btn-ghost w-full py-2 text-sm text-red-500 font-medium hover:bg-red-50/50 rounded-2xl">
                                Batalkan Pendaftaran
                            </button>
                        </form>
                    </div>
                @else
                    @foreach($plans as $plan)
                        <form action="{{ route('premium.subscribe') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <button type="submit" class="btn btn-primary w-full py-3.5 rounded-2xl font-bold cursor-pointer">
                                Berlangganan Sekarang
                            </button>
                        </form>
                    @endforeach
                @endif
            @endguest
        </div>
    </div>
</main>
@endsection
