@extends('layouts.base_layout')

@section('title', 'Konfirmasi Pembayaran Promosi - SellOn')

@section('content')
<main class="grow max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-screen bg-brand-secondary">
    <div class="mb-8">
        <a href="{{ route('promote.my') }}" class="text-brand-accent hover:text-brand-main flex items-center gap-2 text-sm font-semibold transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Promosi Saya
        </a>
    </div>

    @php
        $isUserPremium = Auth::user()->isPremium();
        $price = $isUserPremium ? $order->package->price_premium : $order->package->price_regular;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Instruksi Pembayaran --}}
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm">
            <h2 class="text-2xl font-bold text-brand-main mb-6">Instruksi Pembayaran</h2>
            <div class="space-y-6">
                <div class="p-4 bg-slate-50 rounded-2xl">
                    <p class="text-xs text-brand-muted uppercase font-bold tracking-wider mb-2">Pilihan 1: Transfer Bank</p>
                    <p class="text-sm font-bold text-brand-main">Bank Central Asia (BCA)</p>
                    <p class="text-lg font-extrabold text-brand-accent mt-1">872-049-2811</p>
                    <p class="text-xs text-slate-500 mt-1">a.n. SellOn Indonesia Mandiri</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl">
                    <p class="text-xs text-brand-muted uppercase font-bold tracking-wider mb-2">Pilihan 2: QRIS</p>
                    <div class="w-48 h-48 bg-white border border-slate-200 rounded-xl overflow-hidden mx-auto flex items-center justify-center p-2">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=SellOnPromote{{ $price }}" alt="QRIS Code" class="w-full h-full object-contain">
                    </div>
                    <p class="text-center text-xs text-brand-muted mt-2 font-medium">Pindai QRIS di atas dengan aplikasi e-wallet Anda</p>
                </div>
                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4">
                    <h4 class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-triangle-exclamation"></i> Penting
                    </h4>
                    <p class="text-xs text-amber-700 leading-relaxed">
                        Silakan transfer tepat sebesar <strong>Rp{{ number_format($price, 0, ',', '.') }}</strong>. Simpan bukti transfer Anda untuk diunggah pada form di sebelah kanan. Admin wajib memverifikasi transaksi Anda.
                    </p>
                </div>
            </div>
        </div>

        {{-- Form Konfirmasi --}}
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm">
            <h2 class="text-2xl font-bold text-brand-main mb-2">Konfirmasi Pembayaran</h2>
            <p class="text-xs text-brand-muted mb-6">Produk: <strong>{{ $order->product->name }}</strong> (Paket {{ $order->package->name }})</p>
            
            <form action="{{ route('promote.submit_payment', ['id' => $order->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                
                <div>
                    <label for="sender_name" class="block text-sm font-bold text-slate-700 mb-2">Nama Pengirim (sesuai rekening)</label>
                    <input type="text" name="sender_name" id="sender_name" class="input-field w-full border @error('sender_name') border-red-500 bg-red-50 text-red-900 placeholder-red-300 @else border-slate-200 @enderror px-4 py-2.5 rounded-2xl text-sm" placeholder="Contoh: Budi Santoso" required value="{{ old('sender_name') }}">
                    @error('sender_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="amount" class="block text-sm font-bold text-slate-700 mb-2">Nominal Transfer (Rp)</label>
                    <input type="number" name="amount" id="amount" class="input-field w-full border @error('amount') border-red-500 bg-red-50 @else border-slate-200 @enderror px-4 py-2.5 rounded-2xl text-sm bg-slate-50 text-slate-500 cursor-not-allowed" value="{{ old('amount', (int)$price) }}" readonly required>
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-bold text-slate-700 mb-2">Metode Pembayaran</label>
                    <select name="payment_method" id="payment_method" class="input-field w-full border @error('payment_method') border-red-500 bg-red-50 @else border-slate-200 @enderror px-4 py-2.5 rounded-2xl text-sm cursor-pointer" required>
                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    </select>
                    @error('payment_method')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="transfer_time" class="block text-sm font-bold text-slate-700 mb-2">Waktu Transfer</label>
                    <input type="text" name="transfer_time" id="transfer_time" class="input-field w-full border @error('transfer_time') border-red-500 bg-red-50 @else border-slate-200 @enderror px-4 py-2.5 rounded-2xl text-sm" required value="{{ old('transfer_time') }}" placeholder="Pilih Tanggal dan Waktu">
                    @error('transfer_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Screenshot Bukti Transfer (Wajib)</label>
                    <div class="relative border-2 border-dashed @error('screenshot') border-red-500 bg-red-50/50 @else border-slate-200 @enderror rounded-2xl p-6 text-center hover:border-brand-accent transition-colors">
                        <input type="file" name="screenshot" id="screenshot" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required accept="image/*" onchange="previewImage(event)">
                        <div id="upload-placeholder">
                            <i class="fa-regular fa-image text-slate-400 text-3xl mb-2"></i>
                            <p class="text-xs text-slate-600 font-semibold">Pilih file atau seret ke sini</p>
                            <p class="text-[10px] text-slate-400 mt-1">PNG, JPG, JPEG (Max 5MB)</p>
                        </div>
                        <div id="image-preview-container" class="hidden">
                            <img id="image-preview" src="#" alt="Preview" class="max-h-48 mx-auto rounded-lg shadow-sm">
                            <button type="button" class="mt-2 text-xs text-red-500 font-bold hover:underline" onclick="removePreview(event)">Ganti Gambar</button>
                        </div>
                    </div>
                    @error('screenshot')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn btn-primary w-full py-3.5 rounded-2xl font-bold cursor-pointer">
                        Kirim Konfirmasi Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('image-preview');
        output.src = reader.result;
        
        document.getElementById('upload-placeholder').classList.add('hidden');
        document.getElementById('image-preview-container').classList.remove('hidden');
    }
    reader.readAsDataURL(event.target.files[0]);
}

function removePreview(event) {
    event.stopPropagation();
    document.getElementById('screenshot').value = '';
    document.getElementById('image-preview').src = '#';
    document.getElementById('upload-placeholder').classList.remove('hidden');
    document.getElementById('image-preview-container').classList.add('hidden');
}
</script>
@endsection
