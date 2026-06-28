@extends('layouts.base_layout')

@section('title', 'Riwayat Pembayaran - Admin SellOn')

@section('content')
<main class="grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-screen bg-brand-secondary">
    <div class="mb-8">
        <a href="{{ route('admin.dashboard') }}" class="text-brand-accent hover:text-brand-main flex items-center gap-2 text-sm font-semibold transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <h1 class="text-3xl font-serif font-bold text-brand-main mt-4">Riwayat Pembayaran</h1>
        <p class="text-brand-muted text-sm mt-1">Daftar seluruh riwayat pembayaran premium seller dan iklan promosi di SellOn (Read-Only).</p>
    </div>

    {{-- Filter Form --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm mb-8">
        <form action="{{ route('admin.payments.history') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            {{-- 1. Date Range --}}
            <div>
                <label for="date_from" class="block text-xs font-bold text-slate-500 mb-1">Tanggal Mulai</label>
                <input type="date" name="date_from" id="date_from" value="{{ $filters['date_from'] ?? '' }}" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-brand-accent">
            </div>
            <div>
                <label for="date_to" class="block text-xs font-bold text-slate-500 mb-1">Tanggal Selesai</label>
                <input type="date" name="date_to" id="date_to" value="{{ $filters['date_to'] ?? '' }}" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-brand-accent">
            </div>

            {{-- 2. Status --}}
            <div>
                <label for="status" class="block text-xs font-bold text-slate-500 mb-1">Status Pembayaran</label>
                <select name="status" id="status" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-brand-accent bg-white">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="verified" {{ ($filters['status'] ?? '') === 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="rejected" {{ ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            {{-- 3. Payment Type --}}
            <div>
                <label for="type" class="block text-xs font-bold text-slate-500 mb-1">Jenis Layanan</label>
                <select name="type" id="type" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-brand-accent bg-white">
                    <option value="">Semua Layanan</option>
                    <option value="premium" {{ ($filters['type'] ?? '') === 'premium' ? 'selected' : '' }}>Premium Seller</option>
                    <option value="promotion" {{ ($filters['type'] ?? '') === 'promotion' ? 'selected' : '' }}>Promoted Listing</option>
                </select>
            </div>

            {{-- 4. User --}}
            <div>
                <label for="user" class="block text-xs font-bold text-slate-500 mb-1">Cari User (Nama / NIM)</label>
                <input type="text" name="user" id="user" value="{{ $filters['user'] ?? '' }}" placeholder="Masukkan Nama atau NIM..." class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-brand-accent">
            </div>

            {{-- Actions --}}
            <div class="sm:col-span-2 lg:col-span-5 flex justify-end gap-3 mt-2">
                <a href="{{ route('admin.payments.history') }}" class="btn btn-ghost border border-slate-200 text-slate-600 hover:bg-slate-50 px-4 py-2 rounded-xl text-xs font-bold transition-colors">
                    Reset Filter
                </a>
                <button type="submit" class="btn btn-primary px-6 py-2 rounded-xl text-xs font-bold transition-colors cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-filter text-[10px]"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Payments Table --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold text-xs uppercase">
                        <th class="px-6 py-4">Order Reference</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Payment Type</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Verified At</th>
                        <th class="px-6 py-4">Approved By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            {{-- Order Reference --}}
                            <td class="px-6 py-4 font-mono font-bold text-slate-700">
                                {{ $payment->reference_code }}
                            </td>
                            
                            {{-- User --}}
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $payment->user->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $payment->user->nim }}</div>
                            </td>

                            {{-- Payment Type --}}
                            <td class="px-6 py-4">
                                @if($payment->subscription_order_id)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-indigo-50 text-brand-accent font-bold text-[10px]">
                                        👑 Premium Seller
                                    </span>
                                @elseif($payment->promotion_order_id)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-50 text-rose-500 font-bold text-[10px]">
                                        🔥 Promoted Listing
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            {{-- Amount --}}
                            <td class="px-6 py-4 font-extrabold text-slate-800">
                                Rp{{ number_format($payment->amount, 0, ',', '.') }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 text-center">
                                @if($payment->status === 'verified')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-50 text-green-700 font-bold text-[10px]">
                                        <i class="fa-solid fa-circle-check text-[8px]"></i> Verified
                                    </span>
                                @elseif($payment->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-50 text-red-700 font-bold text-[10px]">
                                        <i class="fa-solid fa-circle-xmark text-[8px]"></i> Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 font-bold text-[10px]">
                                        <i class="fa-solid fa-clock text-[8px]"></i> Pending
                                    </span>
                                @endif
                            </td>

                            {{-- Verified At --}}
                            <td class="px-6 py-4 text-slate-500">
                                {{ $payment->approved_at ? $payment->approved_at->format('d M Y, H:i') : '-' }}
                            </td>

                            {{-- Approved By --}}
                            <td class="px-6 py-4 font-bold text-slate-700">
                                {{ $payment->approver ? $payment->approver->name : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center mb-3 mx-auto">
                                    <i class="fa-solid fa-clipboard-question text-slate-300 text-2xl"></i>
                                </div>
                                <h4 class="font-bold text-slate-700 mb-1">Tidak Ada Data Pembayaran</h4>
                                <p class="text-slate-400 text-xs max-w-xs mx-auto">Silakan ubah filter pencarian Anda atau reset filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
