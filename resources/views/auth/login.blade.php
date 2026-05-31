@extends('layouts.base_layout')

@section('title', 'Login Akun - SellOn') 

@section('content')  

<main class="fade-in-effect min-h-[73vh] max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-30">
  <div id="login-view" class="view-section h-[60vh] flex flex-row justify-center items-center">
    <div class="h-full w-1/5 p-10 gap-y-5 flex flex-col justify-center items-center rounded-l-xl bg-brand-main ">
      <h2 class="text-3xl font-display font-bold text-white">Welcome Back</h2>
      <p class="text-brand-muted mb-6">Please login using your NIM and password.</p>
    </div>
    <div class="h-full w-1/3 p-10 rounded-r-2xl border-stone-300 border-2 bg-stone-100">
      <form id="form-login" action="{{ route('login_post') }}" method="POST">
        @csrf
        <div id="email-form" class="mb-4">
          <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
          <input id="email" name="email" type="email" class="input-field" placeholder="email@webmail.umm.ac.id">
          <span id="err-login-email" class="text-xs text-red-500 hidden mt-1">Use UMM email (@webmail.umm.ac.id).</span>
        </div>  
        <div id="password-form" class="mb-6">
          <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
          <input id="password" name="password" type="password" class="input-field" placeholder="••••••••">
          <span id="err-login-password" class="text-xs text-red-500 hidden mt-1">Password must not be Empty.</span>
        </div>
        <button type="submit" class="btn btn-primary w-full">Login</button>
      </form>

      <p class="text-center text-sm text-brand-muted mt-6">
        Don't have an account? <a href="{{ route('register') }}" wire:navigate
          class="text-brand-accent font-medium inline-block hover:underline">Register here</a>
      </p>
    </div>
  </div>
</main>

@vite('resources/js/auth/login_js.js')

@endsection