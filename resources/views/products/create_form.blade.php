@extends('layouts.base_layout')

@section('title', 'Tambah Produk - SellOn')

@section('content')

<main class="fade-in-effect w-auto mx-auto xl:mx-50 px-4 sm:px-6 py-8 min-h-[73vh] flex justify-center bg-brand-secondary">
  <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8 w-full max-w-3xl">
    <div class="mb-8 border-b border-slate-100 pb-4">
      <h1 class="text-2xl font-display font-bold text-brand-main">Add New Product</h1>
      <p class="text-brand-muted text-sm mt-1">Enter the details of the product you want to sell.</p>
    </div>
    <form id="form-product" action="{{ route('product.post') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="mb-6">
        <label class="block text-sm font-semibold text-brand-main mb-2">Product Photos <span
            class="text-red-500">*</span></label>
        <p class="text-xs text-brand-muted mb-3">Format JPG/PNG. Maximum 5 photos (Maximum size 5MB per photo).</p>

        <div class="flex flex-wrap gap-4">
          <label class="upload-box" id="box-1">
            <input type="file" name="image_urls[]" class="hidden file-input" accept="image/png, image/jpeg" data-box="1">
            <span class="text-3xl text-slate-300 font-light">+</span>
            <img src="" id="img-preview-1" alt="preview">
            <div class="remove-img" data-box="1">✕</div>
          </label>
          <label class="upload-box" id="box-2">
            <input type="file" name="image_urls[]" class="hidden file-input" accept="image/png, image/jpeg" data-box="2">
            <span class="text-3xl text-slate-300 font-light">+</span>
            <img src="" id="img-preview-2" alt="preview">
            <div class="remove-img" data-box="2">✕</div>
          </label>
          <label class="upload-box" id="box-3">
            <input type="file" name="image_urls[]" class="hidden file-input" accept="image/png, image/jpeg" data-box="3">
            <span class="text-3xl text-slate-300 font-light">+</span>
            <img src="" id="img-preview-3" alt="preview">
            <div class="remove-img" data-box="3">✕</div>
          </label>
          <label class="upload-box" id="box-4">
            <input type="file" name="image_urls[]" class="hidden file-input" accept="image/png, image/jpeg" data-box="4">
            <span class="text-3xl text-slate-300 font-light">+</span>
            <img src="" id="img-preview-4" alt="preview">
            <div class="remove-img" data-box="4">✕</div>
          </label>
          <label class="upload-box" id="box-5">
            <input type="file" name="image_urls[]" class="hidden file-input" accept="image/png, image/jpeg" data-box="5">
            <span class="text-3xl text-slate-300 font-light">+</span>
            <img src="" id="img-preview-5" alt="preview">
            <div class="remove-img" data-box="5">✕</div>
          </label>
        </div>
        <span class="text-xs text-red-500 hidden mt-1" id="err-foto">Choose a min of 1 product</span>
      </div>

      <div class="mb-6">
        <label class="block text-sm font-semibold text-brand-main mb-2">Product Name <span
            class="text-red-500">*</span></label>
        <input type="text" name="name" id="name" class="input-field" placeholder="Contoh: Buku Kalkulus Purcell Edisi 9"
          required>
        <span class="text-xs text-red-500 hidden mt-1 err-msg">Product name is required.</span>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div>
          <label class="block text-sm font-semibold text-brand-main mb-2">Category <span
              class="text-red-500">*</span></label>
          <select id="category" name="category" class="input-field cursor-pointer" required>
            <option value="" disabled selected>Choose Category</option>
            <option value="Preloved">Preloved</option>
            <option value="Food">Food</option>
            <option value="Beverage">Beverage</option>
            <option value="Service">Service</option>
          </select>
          <span class="text-xs text-red-500 hidden mt-1 err-msg">Choose a category.</span>
        </div>

        <div>
          <label class="block text-sm font-semibold text-brand-main mb-2">Price (Rp.) <span
              class="text-red-500">*</span></label>
          <div class="relative">
            <input type="number" id="price" name="price" class="input-field" placeholder="0" min="0" required>
          </div>
          <span class="text-xs text-red-500 hidden mt-1 err-msg">Price is required.</span>
        </div>
      </div>

      <div id="stock-section" class="mb-6">
        <label class="block text-sm font-semibold text-brand-main mb-2">Stock <span
            class="text-red-500">*</span></label>
        <div class="relative">
          <input type="number" id="stock" name="stock" class="input-field" placeholder="1" min="1">
        </div>
        <span class="text-xs text-red-500 hidden mt-1 err-msg">Stock is required.</span>
      </div>

      <div class="mb-6 bg-brand-tertiary px-4 py-5 rounded-xl border border-slate-100" id="condition-section">
        <label class="block text-sm font-semibold text-brand-main mb-3">Condition <span
            class="text-red-500">*</span></label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="condition" value="New (Never Opened)" class="custom-radio">
            <span class="text-sm">New (Never Opened)</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="condition" value="Used - Pristine" class="custom-radio">
            <span class="text-sm">Used - Pristine</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="condition" value="Used - Good" class="custom-radio">
            <span class="text-sm">Used - Good</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="condition" value="Used - Decent" class="custom-radio">
            <span class="text-sm">Used - Decent</span>
          </label>
        </div>
        <span class="text-xs text-red-500 hidden mt-2" id="err-condition">Condition is required.</span>
      </div>

      <div class="mb-8">
        <div class="flex justify-between items-end mb-2">
          <label class="block text-sm font-semibold text-brand-main">Product Description <span
              class="text-red-500">*</span></label>
          <span class="text-xs text-brand-muted"><span id="char-count">0</span>/500</span>
        </div>
        <textarea id="description" name="description" class="input-field resize-none"
          placeholder="Jelaskan detail produk, spesifikasi, atau minus barang jika ada..." maxlength="500"
          required></textarea>
        <span class="text-xs text-red-500 hidden mt-1 err-msg">Product description is required.</span>
      </div>

      <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-slate-100">
        <button type="button" class="btn btn-secondary w-full sm:w-auto"
          onclick="window.history.back()">Cancel</button>
        <button type="submit" class="btn btn-primary w-full sm:w-auto gap-2">
          Save Product
        </button>
      </div>
    </form>
  </div>
</main>

@vite('resources/js/products/create_form_js.js')

@endsection