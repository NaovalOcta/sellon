@extends('layouts.base_layout')

@section('title', 'Edit Produk - SellOn')

@section('content')

{{-- 
VARIABLE:
$product = Variable about the product being edited
--}}
<main class="fade-in-effect w-auto mx-auto px-4 sm:px-6 py-8 min-h-[73vh] flex justify-center bg-brand-secondary">
  <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8 w-full max-w-3xl">
    <div class="mb-8 border-b border-slate-100 pb-4">
      <h1 class="text-2xl font-display font-bold text-brand-main">Edit Product</h1>
      <p class="text-brand-muted text-sm mt-1">Update the details of the product you are selling.</p>
    </div>
    <form id="form-product" action="{{ route('product.update', ['id' => $product->id]) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="mb-6">
        <label class="block text-sm font-semibold text-brand-main mb-2">Product Photos</label>
        <p class="text-xs text-brand-muted mb-3">Format JPG/PNG. Maximum 5 photos (Maximum size 5MB per photo).</p>

        <div class="flex flex-wrap gap-4" id="image-gallery">
          <!-- Existing Images -->
          @foreach($product->images as $image)
            <div class="upload-box relative group existing-image" data-id="{{ $image->id }}">
              <img src="{{ $image->display_image }}" alt="preview" class="w-full h-full object-cover rounded-xl border border-slate-200">
              <div class="remove-existing-img absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity" data-id="{{ $image->id }}">✕</div>
            </div>
          @endforeach

          <!-- The Add Button -->
          <div class="upload-box flex items-center justify-center cursor-pointer border-2 border-dashed border-slate-300 rounded-xl hover:bg-slate-50 transition-colors" id="add-image-button">
            <span class="text-3xl text-slate-300 font-light">+</span>
          </div>
        </div>
        
        <div id="new-images-inputs" class="hidden">
          <!-- Active empty input waiting for file -->
          <input type="file" name="new_image_urls[]" accept="image/png, image/jpeg" class="dynamic-file-input" id="input-file-cursor">
        </div>
        <div id="deleted-images-container" class="hidden">
          <!-- Hidden inputs for deleted existing image IDs -->
        </div>
        
        <span class="text-xs text-red-500 hidden mt-1" id="err-foto">Choose at least 1 photo.</span>
      </div>

      <div class="mb-6">
        <label class="block text-sm font-semibold text-brand-main mb-2">Product Name <span
            class="text-red-500">*</span></label>
        <input type="text" name="name" id="name" class="input-field" placeholder="Product Name"
          value="{{ old('name', $product->name) }}" required>
        <span class="text-xs text-red-500 hidden mt-1 err-msg">Product name is required.</span>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div>
          <label class="block text-sm font-semibold text-brand-main mb-2">Category <span
              class="text-red-500">*</span></label>
          <select id="category" name="category" class="input-field cursor-pointer" required>
            <option value="" disabled>Pilih Kategori</option>
            <option value="Preloved" {{ $product->category === 'Preloved' ? 'selected' : '' }}>Preloved</option>
            <option value="Food" {{ $product->category === 'Food' ? 'selected' : '' }}>Food</option>
            <option value="Beverage" {{ $product->category === 'Beverage' ? 'selected' : '' }}>Beverage</option>
            <option value="Service" {{ $product->category === 'Service' ? 'selected' : '' }}>Service</option>
          </select>
          <span class="text-xs text-red-500 hidden mt-1 err-msg">Choose a category.</span>
        </div>

        <div>
          <label class="block text-sm font-semibold text-brand-main mb-2">Price (Rp) <span
              class="text-red-500">*</span></label>
          <div class="relative">
            <input type="number" id="price" name="price" class="input-field pl-10" placeholder="0" min="0"
              value="{{ old('price', $product->price) }}" required>
          </div>
          <span class="text-xs text-red-500 hidden mt-1 err-msg">Price is required.</span>
        </div>
      </div>

      <div id="stock-section" class="mb-6">
        <label class="block text-sm font-semibold text-brand-main mb-2">Stock <span
            class="text-red-500">*</span></label>
        <div class="relative">
          <input type="number" id="stock" name="stock" class="input-field" placeholder="1" min="1" 
            value="{{ old('stock', $product->stock) }}">
        </div>
        <span class="text-xs text-red-500 hidden mt-1 err-msg">Stock is required.</span>
      </div>

      <div class="mb-6 bg-brand-tertiary p-4 rounded-xl border border-slate-100" id="condition-section">
        <label class="block text-sm font-semibold text-brand-main mb-3">Condition <span
            class="text-red-500">*</span></label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="condition" value="New (Never Opened)" class="custom-radio" {{ $product->condition === 'New (Never Opened)' ? 'checked' : '' }}>
            <span class="text-sm">New (Never Opened)</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="condition" value="Used - Pristine" class="custom-radio" {{ $product->condition === 'Used - Pristine' ? 'checked' : '' }}>
            <span class="text-sm">Used - Pristine</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="condition" value="Used - Good" class="custom-radio" {{ $product->condition === 'Used - Good' ? 'checked' : '' }}>
            <span class="text-sm">Used - Good</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="condition" value="Used - Fair" class="custom-radio" {{ $product->condition === 'Used - Fair' ? 'checked' : '' }}>
            <span class="text-sm">Used - Fair</span>
          </label>
        </div>
        <span class="text-xs text-red-500 hidden mt-2" id="err-condition">Condition is required for Preloved category.</span>
      </div>

      <div class="mb-8">
        <div class="flex justify-between items-end mb-2">
          <label class="block text-sm font-semibold text-brand-main">Product Description <span
              class="text-red-500">*</span></label>
          <span class="text-xs text-brand-muted"><span id="char-count">{{ strlen($product->description) }}</span>/500</span>
        </div>
        <textarea id="description" name="description" class="input-field resize-none"
          placeholder="Describe the product in detail, specifications, or any flaws if any..." maxlength="500"
          required>{{ old('description', $product->description) }}</textarea>
        <span class="text-xs text-red-500 hidden mt-1 err-msg">Description is required.</span>
      </div>

      <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-slate-100">
        <a href="{{ route('product.show', ['id' => $product->id]) }}" class="btn btn-secondary w-full sm:w-auto text-center">Cancel</a>
        <button type="submit" class="btn btn-primary w-full sm:w-auto gap-2">
          Save Changes
        </button>
      </div>
    </form>
  </div>
</main>

@vite('resources/js/products/product_edit_js.js')

@endsection
