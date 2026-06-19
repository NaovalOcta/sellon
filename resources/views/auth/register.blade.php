@extends('layouts.base_layout')

@section('title', 'Daftar Akun - SellOn')

@section('content')

<main class="fade-in-effect min-h-[73vh] max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex justify-center items-center">
  <div id="register-view" class="view-section w-full max-w-md md:max-w-4xl flex flex-col md:flex-row justify-center items-stretch mt-4 md:mt-10">
    <div class="w-full md:w-1/3 p-8 md:p-10 gap-y-5 flex flex-col justify-center items-center rounded-t-2xl md:rounded-tr-none md:rounded-l-2xl bg-brand-main text-center">
      <h2 class="text-3xl font-display font-bold text-white mb-2">Register SellOn Account</h2>
      <p class="text-brand-muted text-sm">Join the campus marketplace ecosystem.</p>
    </div>

    <div class="w-full md:w-2/3 p-8 md:p-10 rounded-b-2xl md:rounded-bl-none md:rounded-r-2xl border-stone-300 border-2 border-t-0 md:border-t-2 md:border-l-0 bg-stone-100">
      <form id="form-register" action="{{ route('register_post') }}" method="POST">
        @csrf
        <div class="mb-4">
          <label class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
          <input id="name" name="name" type="text" class="input-field" placeholder="Nama sesuai KTM" required>
          <span id="err-name" class="text-xs text-red-500 hidden mt-1">Full Name must not be Empty.</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">NIM</label>
            <input id="nim" name="nim" type="text" class="input-field" placeholder="15 Digit NIM" maxlength="15">
            <span id="err-nim" class="text-xs text-red-500 hidden mt-1">NIM must be 15 digits.</span>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Major</label>
            <input id="major" name="major" type="text" class="input-field" placeholder="Major" required>
            <span id="err-jurusan" class="text-xs text-red-500 hidden mt-1">Major must not be Empty.</span>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
            <input id="email" name="email" type="email" class="input-field" placeholder="email@webmail.umm.ac.id" required>
            <span id="err-login-email" class="text-xs text-red-500 hidden mt-1">Use UMM email (@webmail.umm.ac.id).</span>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">No WhatsApp</label>
            <input id="whatsapp_no" name="whatsapp_no" type="tel" class="input-field" placeholder="08xxxxxxxxx">
            <span id="err-no-whatsapp" class="text-xs text-red-500 hidden mt-1">Invalid WhatsApp number (must be 08...).</span>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
            <input id="password" name="password" type="password" class="input-field" placeholder="••••••••" required>
            <span id="err-password" class="text-xs text-red-500 hidden mt-1">Use Minimum of 5 Character for Password.</span>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="input-field" placeholder="••••••••" required>
            <span id="err-password-conf" class="text-xs text-red-500 hidden mt-1">Confirm Password does not match.</span>
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-full">Register</button>
      </form>

      {{-- Info verifikasi email --}}
      <div class="flex items-start gap-x-2 bg-teal-50 border border-teal-200 rounded-lg px-4 py-3 mt-4">
        <i class="fa-solid fa-envelope-circle-check text-brand-accent text-sm mt-0.5 flex-shrink-0"></i>
        <p class="text-xs text-teal-700">
          Setelah mendaftar, <strong>link verifikasi</strong> akan dikirim ke email kampus Anda. Akun baru dapat diaktifkan setelah verifikasi selesai.
        </p>
      </div>

      <p class="text-center text-sm text-brand-muted mt-6">
        Already have an account? <a href="{{ route('login') }}"
          class="text-brand-accent font-medium inline-block hover:underline">Login here</a>
      </p>
    </div>
  </div>
</main>

@vite('resources/js/auth/register_js.js')

@endsection
