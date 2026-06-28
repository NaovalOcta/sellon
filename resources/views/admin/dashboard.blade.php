@extends('layouts.base_layout')

@section('title', 'Admin Dashboard - SellOn')

@section('content')
<main class="grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-screen bg-brand-secondary">
    <div class="mb-8">
        <h1 class="text-3xl font-serif font-bold text-brand-main">Admin Dashboard</h1>
        <p class="text-brand-muted text-sm mt-1">Pusat monitoring administrasi dan keuangan marketplace SellOn.</p>
    </div>

    {{-- 1. Marketplace Overview --}}
    <div class="mb-10">
        <h2 class="text-lg font-bold text-brand-main mb-4 flex items-center gap-2">
            <i class="fa-solid fa-shop text-brand-accent"></i> Marketplace Overview
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total Users --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-teal-50 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-users text-brand-accent text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-brand-muted font-bold uppercase tracking-wider">Total Users</p>
                    <p class="text-2xl font-extrabold text-brand-main mt-0.5">{{ number_format($totalUsers) }}</p>
                </div>
            </div>

            {{-- Total Sellers --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-store text-emerald-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-brand-muted font-bold uppercase tracking-wider">Total Sellers</p>
                    <p class="text-2xl font-extrabold text-brand-main mt-0.5">{{ number_format($totalSellers) }}</p>
                </div>
            </div>

            {{-- Premium Sellers Active --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-crown text-amber-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-brand-muted font-bold uppercase tracking-wider">Premium Sellers Active</p>
                    <p class="text-2xl font-extrabold text-brand-main mt-0.5">{{ number_format($activePremium) }}</p>
                </div>
            </div>

            {{-- Active Product Promotions --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-rectangle-ad text-rose-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-brand-muted font-bold uppercase tracking-wider">Promosi Aktif</p>
                    <p class="text-2xl font-extrabold text-brand-main mt-0.5">{{ number_format($activePromotions) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Payment Overview --}}
    <div class="mb-10">
        <h2 class="text-lg font-bold text-brand-main mb-4 flex items-center gap-2">
            <i class="fa-solid fa-file-invoice-dollar text-brand-accent"></i> Payment Overview
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Pending Payments --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between h-44">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-clock text-amber-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-brand-muted font-bold uppercase tracking-wider">Pending Payments</p>
                        <p class="text-3xl font-extrabold text-brand-main mt-1">{{ $pendingPayments }}</p>
                    </div>
                </div>
                <div class="pt-4">
                    <a href="{{ route('admin.payments') }}" class="btn btn-primary w-full py-2.5 rounded-xl text-xs font-bold text-center cursor-pointer flex items-center justify-center gap-1.5">
                        Verify Payments <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            {{-- Verified Payments --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between h-44">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-brand-muted font-bold uppercase tracking-wider">Verified Payments</p>
                        <p class="text-3xl font-extrabold text-brand-main mt-1">{{ $verifiedPayments }}</p>
                    </div>
                </div>
                <div class="pt-4">
                    <a href="{{ route('admin.payments.history') }}?status=verified" class="btn btn-ghost border border-slate-200 text-slate-600 hover:bg-slate-50 w-full py-2.5 rounded-xl text-xs font-bold text-center cursor-pointer">
                        Lihat Riwayat Terverifikasi
                    </a>
                </div>
            </div>

            {{-- Rejected Payments --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between h-44">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-circle-xmark text-red-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-brand-muted font-bold uppercase tracking-wider">Rejected Payments</p>
                        <p class="text-3xl font-extrabold text-brand-main mt-1">{{ $rejectedPayments }}</p>
                    </div>
                </div>
                <div class="pt-4">
                    <a href="{{ route('admin.payments.history') }}?status=rejected" class="btn btn-ghost border border-slate-200 text-slate-600 hover:bg-slate-50 w-full py-2.5 rounded-xl text-xs font-bold text-center cursor-pointer">
                        Lihat Riwayat Ditolak
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Revenue Overview --}}
    <div class="mb-10">
        <h2 class="text-lg font-bold text-brand-main mb-4 flex items-center gap-2">
            <i class="fa-solid fa-coins text-brand-accent"></i> Revenue Overview (Verified Payments Only)
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Revenue Today --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <p class="text-[10px] text-brand-muted font-bold uppercase tracking-wider">Revenue Today</p>
                <p class="text-xl font-extrabold text-brand-main mt-2">Rp{{ number_format($revenueToday, 0, ',', '.') }}</p>
                <p class="text-[10px] text-slate-400 mt-1">Hari ini</p>
            </div>

            {{-- Revenue This Month --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <p class="text-[10px] text-brand-muted font-bold uppercase tracking-wider">Revenue This Month</p>
                <p class="text-xl font-extrabold text-brand-main mt-2">Rp{{ number_format($revenueThisMonth, 0, ',', '.') }}</p>
                <p class="text-[10px] text-slate-400 mt-1">Bulan ini</p>
            </div>

            {{-- Revenue This Year --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <p class="text-[10px] text-brand-muted font-bold uppercase tracking-wider">Revenue This Year</p>
                <p class="text-xl font-extrabold text-brand-main mt-2">Rp{{ number_format($revenueThisYear, 0, ',', '.') }}</p>
                <p class="text-[10px] text-slate-400 mt-1">Tahun ini</p>
            </div>

            {{-- Total Revenue --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm bg-gradient-to-br from-brand-main to-slate-900 text-white border-transparent">
                <p class="text-[10px] text-brand-light-accent font-bold uppercase tracking-wider">Total Revenue</p>
                <p class="text-xl font-extrabold text-brand-accent mt-2">Rp{{ number_format($revenueTotal, 0, ',', '.') }}</p>
                <p class="text-[10px] text-slate-300 mt-1">Seluruh waktu</p>
            </div>
        </div>
    </div>

    {{-- 4. Activity Overview --}}
    <div>
        <h2 class="text-lg font-bold text-brand-main mb-4 flex items-center gap-2">
            <i class="fa-solid fa-chart-line text-brand-accent"></i> Activity Overview
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Approval Today --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-xs text-brand-muted font-bold uppercase tracking-wider">Approval Today</p>
                    <div class="mt-2 space-y-1">
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-500">Approved:</span>
                            <span class="font-bold text-green-600">{{ $approvedToday }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-500">Rejected:</span>
                            <span class="font-bold text-red-600">{{ $rejectedToday }}</span>
                        </div>
                    </div>
                </div>
                <div class="text-[10px] text-slate-400 mt-3 pt-2 border-t border-slate-50">
                    Aktivitas verifikasi hari ini
                </div>
            </div>

            {{-- Payments This Week --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-xs text-brand-muted font-bold uppercase tracking-wider">Payments This Week</p>
                    <p class="text-2xl font-extrabold text-brand-main mt-2">{{ $paymentsThisWeek }}</p>
                </div>
                <div class="text-[10px] text-slate-400 mt-3 pt-2 border-t border-slate-50">
                    Total pengajuan masuk minggu ini
                </div>
            </div>

            {{-- New Premium Sellers --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-xs text-brand-muted font-bold uppercase tracking-wider">New Premium Sellers</p>
                    <p class="text-2xl font-extrabold text-brand-main mt-2">{{ $newPremiumThisMonth }}</p>
                </div>
                <div class="text-[10px] text-slate-400 mt-3 pt-2 border-t border-slate-50">
                    Langganan baru bulan ini
                </div>
            </div>

            {{-- New Product Promotions --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-xs text-brand-muted font-bold uppercase tracking-wider">New Promotions</p>
                    <p class="text-2xl font-extrabold text-brand-main mt-2">{{ $newPromoThisMonth }}</p>
                </div>
                <div class="text-[10px] text-slate-400 mt-3 pt-2 border-t border-slate-50">
                    Iklan baru didaftarkan bulan ini
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
