@extends('layouts.base_layout')

@section('title', 'Tambah Produk - SellOn')

@section('content')

{{-- 
VARIABLE:
$user = Array variable about all the data inside 'User' table
--}}
<main class="fade-in-effect w-auto mx-auto px-4 sm:px-6 py-8 min-h-[73vh] flex justify-center bg-brand-secondary">
  <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8 w-full max-w-3xl">
    <div class="mb-8 border-b border-slate-100 pb-4">
      <h1 class="text-2xl font-display font-bold text-brand-main">Update Profile</h1>
      <p class="text-brand-muted text-sm mt-1">Update the details of your profile.</p>
    </div>
    <form id="form-product" action="{{ route('users.update_profile', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
      @csrf 
      @method('PUT')
      <div class="mb-6">
        <label class="block text-sm font-semibold text-brand-main mb-2">Full Name<span
            class="text-red-500">*</span></label>
        <input type="text" name="name" id="name" class="input-field" placeholder="John Wayne" value="{{ $user->name }}"
          required>
        <span class="text-xs text-red-500 hidden mt-1 err-msg">Full name is required.</span>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div class="mb-6">
          <label class="block text-sm font-semibold text-brand-main mb-2">NIM<span
              class="text-red-500">*</span></label>
          <input type="text" name="nim" id="nim" class="input-field" placeholder="201210370311187" value="{{ $user->nim }}"
            required>
          <span class="text-xs text-red-500 hidden mt-1 err-msg">NIM is required.</span>
        </div>

        <div class="mb-6">
          <label class="block text-sm font-semibold text-brand-main mb-2">Major<span
              class="text-red-500">*</span></label>
          <input type="text" name="major" id="major" class="input-field" placeholder="Informatic" value="{{ $user->major }}"
            required>
          <span class="text-xs text-red-500 hidden mt-1 err-msg">Major is required.</span>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div class="mb-6">
          <label class="block text-sm font-semibold text-brand-main mb-2">Email<span
              class="text-red-500">*</span></label>
          <input type="text" name="email" id="email" class="input-field" placeholder="[EMAIL_ADDRESS]" value="{{ $user->email }}"
            required>
          <span class="text-xs text-red-500 hidden mt-1 err-msg">Email is required.</span>
        </div>

        <div class="mb-6">
          <label class="block text-sm font-semibold text-brand-main mb-2">WhatsApp Number<span
              class="text-red-500">*</span></label>
          <input type="text" name="whatsapp_no" id="whatsapp_no" class="input-field" placeholder="08123456789" value="{{ $user->whatsapp_no }}"
            required>
          <span class="text-xs text-red-500 hidden mt-1 err-msg">WhatsApp number is required.</span>
        </div>
      </div>

      <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-slate-100">
        <button type="submit" class="btn btn-primary w-full sm:w-auto gap-2">
          Save Profile
        </button>
      </div>
    </form>
  </div>
</main>

{{-- @vite('resources/js/products/create_form_js.js') --}}

@endsection