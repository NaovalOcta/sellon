@extends('layouts.base_layout')

@section('title', 'Verifikasi Pembayaran - Admin SellOn')

@section('content')
<main class="grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-screen bg-brand-secondary">
    <div class="mb-8">
        <a href="{{ route('admin.dashboard') }}" class="text-brand-accent hover:text-brand-main flex items-center gap-2 text-sm font-semibold transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <h1 class="text-3xl font-serif font-bold text-brand-main mt-4">Verifikasi Pembayaran Pending</h1>
        <p class="text-brand-muted text-sm mt-1">Daftar konfirmasi pembayaran manual yang diajukan oleh seller. Silakan cek mutasi rekening Anda secara manual sebelum menyetujui.</p>
    </div>

    @if($payments->isEmpty())
        <div class="text-center py-16 bg-white rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center mb-4 mx-auto">
                <i class="fa-solid fa-clipboard-check text-slate-300 text-3xl"></i>
            </div>
            <h3 class="text-lg font-bold text-brand-main mb-2">Tidak Ada Antrean Pembayaran</h3>
            <p class="text-brand-muted text-sm max-w-sm mx-auto leading-relaxed">
                Semua konfirmasi pembayaran telah diverifikasi.
            </p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($payments as $payment)
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col lg:flex-row justify-between items-start gap-6">
                    
                    {{-- Detail Order & Pengirim --}}
                    <div class="space-y-4 min-w-0 grow">
                        {{-- Header Tipe Order --}}
                        <div>
                            @if($payment->subscription_order_id)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 text-brand-accent text-xs font-bold">
                                    👑 Premium Seller (Bulanan)
                                </span>
                            @elseif($payment->promotion_order_id)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-500 text-xs font-bold">
                                    🔥 Promoted Listing ({{ $payment->promotionOrder->package->name }})
                                </span>
                                <p class="text-sm font-bold text-slate-800 mt-2">
                                    Produk: <a href="{{ route('product.show', ['id' => $payment->promotionOrder->product_id]) }}" target="_blank" class="underline text-brand-accent">{{ $payment->promotionOrder->product->name }}</a>
                                </p>
                            @endif
                            
                            <div class="mt-3 text-xs text-brand-muted space-y-1">
                                <p>NIM: <strong>{{ $payment->user->nim }}</strong> | Nama: <strong>{{ $payment->user->name }}</strong></p>
                                <p>Ref Code: <strong class="text-slate-700 font-mono">{{ $payment->reference_code }}</strong> | Diajukan: {{ $payment->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        {{-- Rincian Transfer yang Diinput User --}}
                        <div class="bg-slate-50 p-4 rounded-2xl grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="text-slate-400 font-medium">Nama Pengirim Form</p>
                                <p class="font-bold text-slate-800 mt-0.5">{{ $payment->payment_details['sender_name'] ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-slate-400 font-medium">Nominal di Form</p>
                                <p class="font-extrabold text-brand-accent mt-0.5">Rp{{ number_format($payment->amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-slate-400 font-medium">Metode Pembayaran</p>
                                <p class="font-bold text-slate-800 mt-0.5">{{ $payment->payment_method === 'qris' ? 'QRIS' : 'Transfer Bank' }}</p>
                            </div>
                            <div>
                                <p class="text-slate-400 font-medium">Waktu Transfer Form</p>
                                <p class="font-bold text-slate-800 mt-0.5">
                                    {{ $payment->paid_at ? $payment->paid_at->format('d M Y, H:i') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Bukti Transfer Screenshot --}}
                    @if(isset($payment->payment_details['screenshot_path']))
                        <div class="w-full lg:w-48 shrink-0">
                            <p class="text-xs font-bold text-slate-700 mb-2">Screenshot Bukti Transfer</p>
                            <a href="{{ asset('uploads/' . $payment->payment_details['screenshot_path']) }}" target="_blank" class="block border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:opacity-90 transition-opacity">
                                <img src="{{ asset('uploads/' . $payment->payment_details['screenshot_path']) }}" alt="Bukti Transfer" class="w-full h-32 object-cover">
                            </a>
                            <p class="text-[10px] text-center text-brand-muted mt-1.5"><i class="fa-solid fa-magnifying-glass-plus mr-1"></i> Klik untuk memperbesar</p>
                        </div>
                    @endif

                    {{-- Tombol Aksi Verifikasi --}}
                    <div class="w-full lg:w-60 shrink-0 border-t lg:border-t-0 lg:border-l border-slate-100 pt-4 lg:pt-0 lg:pl-6 flex flex-col gap-3 justify-center">
                        <div class="bg-amber-50 border border-amber-100 rounded-xl p-3 text-[10px] text-amber-800 leading-normal mb-2">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i> <strong>Wajib:</strong> Cek mutasi rekening Anda pada tanggal & jam di sebelah kiri sebelum approve!
                        </div>

                        {{-- Tombol Approve --}}
                        <form action="{{ route('admin.payments.approve', ['id' => $payment->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda sudah mengecek mutasi dan yakin ingin menyetujui pembayaran ini?');">
                            @csrf
                            <button type="submit" class="btn btn-success w-full py-2.5 rounded-xl text-xs font-bold cursor-pointer">
                                Setujui (Approve) <i class="fa-solid fa-check ml-1.5"></i>
                            </button>
                        </form>

                        {{-- Form Tolak (Reject) --}}
                        <div x-data="{ open: false }" class="w-full">
                            <button @click="open = !open" type="button" class="btn btn-ghost w-full py-2.5 rounded-xl text-xs text-red-500 hover:bg-red-50 font-bold border border-red-200 cursor-pointer">
                                Tolak Pembayaran <i class="fa-solid fa-xmark ml-1.5"></i>
                            </button>

                            <div x-show="open" x-cloak class="mt-3 p-3 bg-red-50/50 border border-red-100 rounded-xl space-y-3">
                                <form action="{{ route('admin.payments.reject', ['id' => $payment->id]) }}" method="POST">
                                    @csrf
                                    <label for="rejected_reason-{{ $payment->id }}" class="block text-[10px] font-bold text-red-700 mb-1">Alasan Penolakan</label>
                                    <textarea name="rejected_reason" id="rejected_reason-{{ $payment->id }}" rows="3" class="input-field w-full border border-red-200 p-2 rounded-lg text-xs" placeholder="Contoh: Dana tidak masuk di mutasi, atau nominal salah." required></textarea>
                                    <button type="submit" class="btn btn-danger w-full py-1.5 mt-2 rounded-lg text-[10px] font-bold cursor-pointer">
                                        Kirim Penolakan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endif
</main>

{{-- Alpine.js fallback just in case it is not loaded, but we can also write plain JS for the toggle if needed --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Simple pure JS toggle for rejection forms since Alpine might not be initialized
    document.querySelectorAll('[x-data]').forEach(function (el) {
        var btn = el.querySelector('button[type="button"]');
        var formContainer = el.querySelector('div');
        if (btn && formContainer) {
            formContainer.style.display = 'none';
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                if (formContainer.style.display === 'none') {
                    formContainer.style.display = 'block';
                } else {
                    formContainer.style.display = 'none';
                }
            });
        }
    });
});
</script>
@endsection
