@extends('layouts.base_layout')

@section('title', 'Katalog Produk - SellOn')

@section('content')

{{-- 
VARIABLE:
$products = Array variable about all the data inside 'Product' table
--}}
<main id="product-catalog-container" class="grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-screen bg-brand-secondary">
  <div class="mt-6 mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-y-4">
    <div>
      <h1 class="mb-2 text-3xl sm:text-4xl font-serif scale-y-125 font-bold text-brand-main">Seller Dashboard</h1>
      <p class="text-brand-muted text-sm sm:text-base">Manage your products and services</p>
    </div>
    @auth
      <a href="{{ route('product.create') }}" class="btn btn-primary gap-2 w-full sm:w-auto">
        <i class="fa-solid fa-plus"></i>
        Add Product
      </a>
    @endauth
  </div>
  
  <div class="flex gap-2 mb-8"> 
    <div class="relative grow">
      <input id="search-bar" type="text" placeholder="Find Book, Food, or Service..." class="input-field pl-4 pr-12 border-slate-200 w-full">
    </div>

    <div class="flex items-center gap-2">
      <select class="input-field h-10! py-1! text-sm cursor-pointer min-w-[160px]" id="sort-select">
        <option value="Newest">Newest</option>
        <option value="Price: Low → High">Price: Low → High</option>
        <option value="Price: High → Low">Price: High → Low</option>
      </select>
    </div>
  </div>

  @if($products->isEmpty())
    <div class="invisible flex flex-col justify-center items-center mt-20">
      <i class="fa-solid fa-heart-crack text-6xl text-slate-200 mb-4"></i>
      <label class="text-stone-500 font-medium">No products yet</label>
      <p class="text-sm text-stone-400 mt-2">Start by adding products to sell.</p>
      <a href="{{ route('product.create') }}" class="btn btn-primary mt-6">Add Product</a>
    </div>
  @else
    @include('partials._rectangle_product_card', ['products' => $products])
    
    <div class="invisible mt-8 flex justify-center">
      {{ $products->links() }}
    </div>
  @endif
</main>

@vite('resources/js/products/product_catalog_js.js')

@endsection