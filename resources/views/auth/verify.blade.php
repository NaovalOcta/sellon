@extends('layouts.base_layout')

@section('title', 'Verifikasi Email - SellOn')

@section('content')

<main class="fade-in-effect min-h-[73vh] flex items-center justify-center px-4 py-16">
  <div class="w-full max-w-md">

    {{-- Card Utama --}}
    <div class="bg-white rounded-2xl shadow-lg border border-stone-200 overflow-hidden">

      {{-- Header Card --}}
      <div class="bg-brand-main px-8 py-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/10 mb-4">
          <i class="fa-solid fa-envelope-circle-check text-white text-3xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-white">Verifikasi Email Anda</h1>
        <p class="text-brand-muted text-sm mt-1">Satu langkah lagi untuk mengaktifkan akun SellOn</p>
      </div>

      {{-- Body Card --}}
      <div class="px-8 py-8">

        {{-- Info email tujuan --}}
        <div class="bg-teal-50 border border-teal-200 rounded-xl p-4 mb-6 flex items-start gap-x-3">
          <i class="fa-solid fa-circle-info text-brand-accent mt-0.5 flex-shrink-0"></i>
          <div>
            <p class="text-sm font-semibold text-teal-800 mb-1">Email verifikasi telah dikirim ke:</p>
            <p class="text-sm font-mono font-bold text-brand-accent break-all">{{ Auth::user()->email }}</p>
          </div>
        </div>

        {{-- Instruksi --}}
        <div class="space-y-3 mb-6 text-sm text-slate-600">
          <div class="flex items-start gap-x-3">
            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-accent text-white text-xs flex items-center justify-center font-bold">1</span>
            <p>Buka inbox email kampus Anda di <strong>webmail.umm.ac.id</strong></p>
          </div>
          <div class="flex items-start gap-x-3">
            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-accent text-white text-xs flex items-center justify-center font-bold">2</span>
            <p>Cari email dari <strong>SellOn</strong> dengan subjek "Verifikasi Email Akun SellOn"</p>
          </div>
          <div class="flex items-start gap-x-3">
            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-accent text-white text-xs flex items-center justify-center font-bold">3</span>
            <p>Klik tombol <strong>"Verifikasi Email Sekarang"</strong> di dalam email tersebut</p>
          </div>
        </div>

        {{-- Catatan Spam --}}
        <div class="bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 mb-6 flex items-center gap-x-2">
          <i class="fa-solid fa-triangle-exclamation text-amber-500 text-sm flex-shrink-0"></i>
          <p class="text-xs text-amber-700">Jika email tidak masuk dalam 2 menit, cek folder <strong>Spam</strong> atau <strong>Junk</strong> Anda.</p>
        </div>

        {{-- Tombol Kirim Ulang --}}
        <form action="{{ route('verification.send') }}" method="POST" id="resend-form">
          @csrf
          <button type="submit" id="resend-btn"
            class="w-full btn btn-primary flex items-center justify-center gap-x-2 transition-all duration-300">
            <i class="fa-solid fa-paper-plane text-sm"></i>
            <span id="resend-btn-text">Kirim Ulang Email Verifikasi</span>
          </button>
        </form>

        {{-- Countdown setelah kirim ulang --}}
        <p id="resend-countdown" class="text-center text-xs text-slate-400 mt-3 hidden">
          Dapat mengirim ulang dalam <span id="countdown-seconds" class="font-semibold text-brand-accent">60</span> detik
        </p>

        {{-- Divider --}}
        <div class="flex items-center gap-x-3 my-5">
          <hr class="flex-1 border-stone-200">
          <span class="text-xs text-slate-400">atau</span>
          <hr class="flex-1 border-stone-200">
        </div>

        {{-- Link Logout --}}
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit"
            class="w-full text-sm text-slate-500 hover:text-brand-accent transition-colors duration-200 text-center py-2">
            <i class="fa-solid fa-right-from-bracket mr-1"></i>
            Salah email? Logout dan daftar ulang
          </button>
        </form>

      </div>{{-- end body card --}}
    </div>{{-- end card --}}

    {{-- Link kembali ke home --}}
    <p class="text-center text-xs text-slate-400 mt-5">
      Ingin tetap browsing?
      <a href="{{ route('home') }}" class="text-brand-accent hover:underline font-medium">Kembali ke Beranda</a>
    </p>

  </div>
</main>

<script>
  // Countdown timer setelah tombol "Kirim Ulang" diklik
  document.getElementById('resend-form').addEventListener('submit', function () {
    const btn = document.getElementById('resend-btn');
    const btnText = document.getElementById('resend-btn-text');
    const countdown = document.getElementById('resend-countdown');
    const countdownSeconds = document.getElementById('countdown-seconds');

    btn.disabled = true;
    btn.classList.add('opacity-50', 'cursor-not-allowed');
    countdown.classList.remove('hidden');

    let seconds = 60;
    countdownSeconds.textContent = seconds;

    const timer = setInterval(() => {
      seconds--;
      countdownSeconds.textContent = seconds;
      if (seconds <= 0) {
        clearInterval(timer);
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
        countdown.classList.add('hidden');
        btnText.textContent = 'Kirim Ulang Email Verifikasi';
      }
    }, 1000);
  });
</script>

@endsection
