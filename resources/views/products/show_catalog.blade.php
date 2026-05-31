@extends('layouts.base_layout')

@section('title', 'Katalog Produk - SellOn')

@section('content')

{{-- 
VARIABLE:
$products = Array variable about all the data inside 'Product' table
--}}
<main id="product-catalog-container" class="grow max-w-7xl mx-auto xl:mx-50 px-4 sm:px-6 lg:px-8 py-8 min-h-screen bg-brand-secondary">
  <div class="mt-6 mb-8">
    <h1 class="mb-2 text-4xl font-serif scale-y-125 font-bold text-brand-main">Shop</h2>
    <p class="text-brand-muted">Discover products and services from your campus community</p>
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

  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div class="flex items-center gap-2 overflow-x-auto hide-scrollbar w-full md:w-auto pb-2 md:pb-0 text-sm"
      id="filter-container">
      <div
        class="filter-category active px-6 py-2 rounded-full cursor-pointer border-0 border-stone-400 bg-brand-main hover:bg-brand-accent transition-colors duration-300 text-white"
        data-filter="All">All ({{ isset($categoryCounts) ? $categoryCounts['All'] : 0 }})</div>
      <div
        class="filter-category inactive px-6 py-2 rounded-full cursor-pointer bg-white hover:bg-slate-200 transition-colors duration-300 text-slate-600 border border-slate-400"
        data-filter="Preloved">Preloved ({{ isset($categoryCounts) ? $categoryCounts['Preloved'] : 0 }})</div>
      <div
        class="filter-category inactive px-6 py-2 rounded-full cursor-pointer bg-white hover:bg-slate-200 transition-colors duration-300 text-slate-600 border border-slate-400"
        data-filter="Food">Food ({{ isset($categoryCounts) ? $categoryCounts['Food'] : 0 }})</div>
      <div
        class="filter-category inactive px-6 py-2 rounded-full cursor-pointer bg-white hover:bg-slate-200 transition-colors duration-300 text-slate-600 border border-slate-400"
        data-filter="Beverage">Beverage ({{ isset($categoryCounts) ? $categoryCounts['Beverage'] : 0 }})</div>
      <div
        class="filter-category inactive px-6 py-2 rounded-full cursor-pointer bg-white hover:bg-slate-200 transition-colors duration-300 text-slate-600 border border-slate-400"
        data-filter="Service">Service ({{ isset($categoryCounts) ? $categoryCounts['Service'] : 0 }})</div>
    </div>
  </div>

  @include('partials._square_product_card', ['products' => $products])

  <div class="mt-8">
    {{ $products->links() }}
  </div>
</main>

@vite('resources/js/products/product_catalog_js.js')

@endsection