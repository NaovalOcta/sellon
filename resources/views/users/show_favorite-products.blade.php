@extends('layouts.base_layout')

@section('title', 'Favorit Saya - SellOn')

@section('content')

<main id="product-favorite-container" class="grow max-w-7xl mx-auto xl:mx-50 px-4 sm:px-6 lg:px-8 py-8 min-h-[75vh] bg-brand-secondary">
  <div class="mt-6 mb-8 border-b border-slate-100">
    <h1 class="mb-2 text-4xl font-serif scale-y-125 font-bold  text-brand-main">My Favorites</h1>
    <p class="text-brand-muted">Products you have saved.</p>
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
      <label class="text-stone-500 font-medium">No favorite products yet</label>
      <p class="text-sm text-stone-400 mt-2">Find and save products you like from the catalog.</p>
      <a href="{{ route('product.catalog') }}" wire:navigate class="btn btn-primary mt-6">Find Products</a>
    </div>
  @else
    @include('partials._square_product_card', ['products' => $products])
    
    <div class="invisible mt-8 flex justify-center">
      {{ $products->links() }}
    </div>
  @endif
</main>

@endsection
