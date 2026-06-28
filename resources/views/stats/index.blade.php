@extends('layouts.base_layout')

@section('title', 'Statistik Toko - SellOn')

@section('content')
<main class="grow max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-screen bg-brand-secondary">
    <div class="mb-8">
        <h1 class="text-3xl font-serif font-bold text-brand-main">Statistik Toko</h1>
        <p class="text-brand-muted text-sm mt-1">Pantau performa produk dan minat pembeli Anda.</p>
    </div>

    {{-- Overview Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center shrink-0">
                <i class="fa-regular fa-eye text-brand-accent text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-brand-muted font-bold uppercase tracking-wider">Total Kunjungan Produk</p>
                <p class="text-3xl font-extrabold text-brand-main mt-1">{{ number_format($totalVisits, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-crown text-brand-success text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-brand-muted font-bold uppercase tracking-wider">Status Akun</p>
                <p class="text-lg font-bold text-brand-success mt-1">✨ Premium Seller</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-arrow-up-right-from-square text-orange-500 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-brand-muted font-bold uppercase tracking-wider">Kapasitas Katalog</p>
                <p class="text-lg font-bold text-brand-main mt-1">
                    {{ Auth::user()->products()->count() }} / 100 <span class="text-xs text-slate-500 font-normal">produk</span>
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Chart Tren Kunjungan --}}
        <div class="lg:col-span-2 bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm">
            <h3 class="text-lg font-bold text-brand-main mb-6">Tren Kunjungan (7 Hari Terakhir)</h3>
            
            <div class="flex items-end gap-2 h-56 pt-4 justify-between">
                @php
                    $maxCount = collect($visitsTrend)->max('count') ?: 1;
                @endphp
                @foreach($visitsTrend as $trend)
                    @php
                        $percentHeight = ($trend['count'] / $maxCount) * 100;
                    @endphp
                    <div class="flex flex-col items-center flex-1 h-full justify-end">
                        <div class="w-full bg-slate-50 rounded-t-xl relative flex items-end h-44 hover:bg-slate-100/80 transition-colors">
                            <div class="w-full bg-brand-accent rounded-t-xl text-white text-[10px] font-bold text-center flex items-center justify-center transition-all duration-500 hover:bg-brand-main" style="height: {{ $percentHeight }}%; min-height: {{ $trend['count'] > 0 ? '20px' : '0px' }}">
                                @if($trend['count'] > 0)
                                    {{ $trend['count'] }}
                                @endif
                            </div>
                        </div>
                        <span class="text-[10px] text-slate-500 font-bold mt-3 truncate w-full text-center">{{ $trend['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Produk Terpopuler --}}
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm">
            <h3 class="text-lg font-bold text-brand-main mb-6">Produk Terpopuler</h3>
            
            @if($popularProducts->isEmpty())
                <div class="text-center py-12 text-slate-400">
                    <i class="fa-regular fa-folder-open text-3xl mb-2"></i>
                    <p class="text-xs">Belum ada kunjungan produk tercatat.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($popularProducts as $index => $product)
                        <div class="flex items-center gap-3 py-2 border-b border-slate-50 last:border-0 last:pb-0">
                            <span class="text-sm font-extrabold text-slate-400 w-5 shrink-0">{{ $index + 1 }}</span>
                            <img src="{{ $product->display_image }}" alt="{{ $product->name }}" class="w-10 h-10 object-cover rounded-lg border border-slate-100 shrink-0">
                            <div class="min-w-0 grow">
                                <h4 class="text-xs font-bold text-slate-800 truncate">{{ $product->name }}</h4>
                                <p class="text-[10px] text-brand-muted">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="inline-block px-2 py-0.5 rounded bg-indigo-50 text-brand-accent text-[10px] font-bold">
                                    {{ $product->store_visits_count }} views
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
