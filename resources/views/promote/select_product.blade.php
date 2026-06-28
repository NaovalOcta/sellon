@extends('layouts.base_layout')

@section('title', 'Pilih Produk untuk Promosi - SellOn')

@section('content')
<main class="grow max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-screen bg-brand-secondary">
    <div class="mb-8">
        <a href="{{ route('promote.index') }}" class="text-brand-accent hover:text-brand-main flex items-center gap-2 text-sm font-semibold transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Promoted Listing
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm">
        <h2 class="text-2xl font-bold text-brand-main mb-2">Promosikan Produk Anda</h2>
        <p class="text-brand-muted text-sm mb-8">Pilih salah satu produk aktif Anda dan paket durasi promosi di bawah ini.</p>

        @if($products->isEmpty())
            <div class="text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                <i class="fa-solid fa-boxes-stacked text-slate-300 text-4xl mb-3"></i>
                <h4 class="text-sm font-bold text-slate-700 mb-1">Tidak Ada Produk Tersedia</h4>
                <p class="text-xs text-brand-muted max-w-sm mx-auto leading-relaxed px-4">
                    Semua produk Anda mungkin sedang dipromosikan, atau Anda belum menambahkan produk ke katalog.
                </p>
                <div class="mt-6">
                    <a href="{{ route('product.create') }}" class="btn btn-primary px-6 py-2.5 rounded-xl font-bold text-sm">
                        Unggah Produk Baru <i class="fa-solid fa-plus ml-1.5 text-xs"></i>
                    </a>
                </div>
            </div>
        @else
            <form action="{{ route('promote.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Pilih Produk --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">Pilih Produk</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($products as $product)
                            <label class="relative flex items-center p-4 border border-slate-200 rounded-2xl hover:border-brand-accent cursor-pointer transition-colors bg-slate-50/20">
                                <input type="radio" name="product_id" value="{{ $product->id }}" class="mr-4 text-brand-accent focus:ring-brand-accent cursor-pointer" required {{ old('product_id') == $product->id ? 'checked' : '' }}>
                                <div class="flex items-center gap-3 min-w-0">
                                    <img src="{{ $product->display_image }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-xl shrink-0">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-800 truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-brand-muted">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('product_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pilih Paket --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">Pilih Paket Durasi</label>
                    <div class="space-y-3">
                        @foreach($packages as $package)
                            @php
                                $isUserPremium = Auth::user()->isPremium();
                                $finalPrice = $isUserPremium ? $package->price_premium : $package->price_regular;
                                $isSelected = old('package_id', request()->query('package_id')) == $package->id;
                            @endphp
                            <label class="relative flex items-center justify-between p-4 border border-slate-200 rounded-2xl hover:border-brand-accent cursor-pointer transition-colors bg-slate-50/20">
                                <div class="flex items-center">
                                    <input type="radio" name="package_id" value="{{ $package->id }}" class="mr-4 text-brand-accent focus:ring-brand-accent cursor-pointer" required {{ $isSelected ? 'checked' : '' }}>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Sponsor {{ $package->name }} ({{ $package->duration_days }} Hari)</p>
                                        @if($isUserPremium)
                                            <p class="text-xs text-brand-accent font-semibold flex items-center gap-1 mt-0.5">
                                                <i class="fa-solid fa-crown text-[10px]"></i> Harga Diskon Premium
                                            </p>
                                        @else
                                            <p class="text-xs text-slate-500 mt-0.5">Harga Akun Biasa</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($isUserPremium)
                                        <p class="text-sm font-extrabold text-brand-accent">Rp{{ number_format($package->price_premium, 0, ',', '.') }}</p>
                                        <p class="text-[10px] text-slate-400 line-through">Rp{{ number_format($package->price_regular, 0, ',', '.') }}</p>
                                    @else
                                        <p class="text-sm font-extrabold text-slate-800">Rp{{ number_format($package->price_regular, 0, ',', '.') }}</p>
                                        <p class="text-[10px] text-brand-accent font-medium">Hemat 30% jika Premium</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('package_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-6">
                    <button type="submit" class="btn btn-primary w-full py-3.5 rounded-2xl font-bold cursor-pointer">
                        Buat Pesanan Promosi <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </button>
                </div>
            </form>
        @endif
    </div>
</main>
@endsection
