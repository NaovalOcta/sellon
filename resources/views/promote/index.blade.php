@extends('layouts.base_layout')

@section('title', 'Promoted Listing - SellOn')

@section('content')
<main class="grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-screen bg-brand-secondary">
    {{-- Header Section --}}
    <div class="text-center max-w-3xl mx-auto mb-10">
        <h1 class="text-3xl font-serif font-bold text-brand-main mb-3">
            Promoted Listing
        </h1>
        <p class="text-brand-muted text-sm leading-relaxed">
            Iklankan produk Anda agar berada di urutan teratas hasil pencarian dan homepage.
        </p>
        <div class="mt-6 flex justify-center gap-4 flex-wrap text-sm">
            @guest
                <a href="{{ route('login') }}" class="btn btn-primary px-6 py-2.5 rounded-xl font-bold">
                    Login Untuk Memulai
                </a>
            @else
                <a href="{{ route('promote.create') }}" class="btn btn-primary px-6 py-2.5 rounded-xl font-bold cursor-pointer">
                    Promosikan Produk Baru <i class="fa-solid fa-plus ml-2"></i>
                </a>
                <a href="{{ route('promote.my') }}" class="btn btn-secondary px-6 py-2.5 rounded-xl font-bold cursor-pointer">
                    Lihat Promosi Saya <i class="fa-solid fa-list-check ml-2"></i>
                </a>
            @endguest
        </div>
    </div>

    {{-- Pricing Table --}}
    <div class="max-w-4xl mx-auto">
        <h2 class="text-xl font-bold text-brand-main text-center mb-8">Pilihan Paket Promosi</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($packages as $package)
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div class="p-8 text-center bg-slate-50/50 border-b border-slate-100">
                        <span class="inline-block px-3 py-1 rounded-full bg-slate-100 text-brand-main text-xs font-bold mb-3">
                            Sponsor {{ $package->name }}
                        </span>
                        <div class="mt-4 flex justify-center items-baseline">
                            <span class="text-3xl font-extrabold text-brand-main">Rp{{ number_format($package->price_regular, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-brand-muted text-xs mt-1">Durasi: {{ $package->duration_days }} Hari</p>
                    </div>
                    <div class="p-8 flex-grow flex flex-col justify-between">
                        <div class="mb-6 space-y-3">
                            <div class="flex items-center justify-between text-xs py-1 border-b border-dashed border-slate-100">
                                <span class="text-slate-500 font-medium">Akun Biasa</span>
                                <span class="font-bold text-brand-main">Rp{{ number_format($package->price_regular, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs py-1 bg-indigo-50/50 p-2 rounded-lg border border-indigo-100/50">
                                <span class="text-brand-accent font-bold"><i class="fa-solid fa-crown mr-1"></i> Premium Seller</span>
                                <span class="font-extrabold text-brand-accent">Rp{{ number_format($package->price_premium, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-[10px] text-brand-muted mt-2 text-center">Hemat 30% jika Anda adalah Premium Seller!</p>
                        </div>
                        @auth
                            <a href="{{ route('promote.create') }}?package_id={{ $package->id }}" class="btn btn-primary w-full py-2.5 rounded-2xl font-bold text-sm text-center">
                                Pilih Paket Ini
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-full py-2.5 rounded-2xl font-bold text-sm text-center">
                                Login Untuk Membeli
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</main>
@endsection
