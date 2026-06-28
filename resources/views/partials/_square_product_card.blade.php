{{-- 
VARIABLE:
$products = Array/Collection variable about all the data inside 'Product' table
$promotedProducts = Optional Collection of promoted products
--}}

@php
    $hasPromotions = isset($promotedProducts) && $promotedProducts->isNotEmpty();
    $isEmpty = $products->isEmpty() && !$hasPromotions;
@endphp

@if($isEmpty)
  <div class="fade-in-effect flex flex-col justify-center items-center">
    <label class="mt-40 text-stone-400">There is no product yet</label>
  </div>
@else
  <div class="fade-in-effect grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="product-grid">
    {{-- 1. Tampilkan Produk Promosi Terlebih Dahulu (dengan styling khusus) --}}
    @if($hasPromotions)
      @foreach($promotedProducts as $product)
        <div class="square-product-card border-2 border-amber-300 shadow-md relative bg-amber-50/10" data-category="{{ $product->category }}" data-price="{{ $product->price }}">
          <div class="img-container">
            <img src="{{ $product->display_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition duration-300 brightness-90 hover:brightness-80">
            <div class="absolute top-2 left-2 flex flex-col gap-1.5 items-start">
              <span class="bg-brand-accent text-white text-xs font-bold px-2 py-1 rounded shadow-sm">
                {{ $product->category }}
              </span>
              <span class="bg-amber-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded shadow-sm flex items-center gap-0.5">
                🔥 Sponsor
              </span>
            </div>
            <div class="absolute top-2 right-5 gap-3 flex">
              @auth
                <form method="POST" action="{{ route('favorite.toggle', ['product_id' => $product->id]) }}">
                  @csrf
                  <button class="sml-square-btn text-sm cursor-pointer btn-favorite {{ Auth::user()->favorites->contains('product_id', $product->id) ? 'btn-danger text-white' : 'bg-white text-slate-400 border border-slate-200 hover:text-red-500' }}" data-id="{{ $product->id }}">
                    <i class="fa-solid fa-heart"></i>
                  </button>
                </form>
              @endauth
            </div>
          </div>
          <div class="p-4">
            <a href="{{ route('product.show', ['id' => $product->id]) }}">
              <h3 class="text-slate-800 truncate mb-1 text-lg font-bold">{{ $product->name }}</h3>
              <p class="font-display text-brand-muted text-sm mb-6">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
              <div class="flex items-center gap-2 mb-4">
                <div class="w-6 h-6 bg-slate-200 rounded-full overflow-hidden shrink-0">
                  <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($product->user->name) }}" alt="Avatar">
                </div>
                <span class="text-xs text-brand-muted truncate flex items-center gap-1.5">
                  {{ $product->user->name }}
                  @if($product->user->isPremium())
                    <span class="text-[8px] bg-amber-500 text-white px-1.5 py-0.5 rounded font-extrabold shrink-0">👑 Premium</span>
                  @endif
                  - {{ $product->user->major }}
                </span>
              </div>
            </a>
          </div>
        </div>
      @endforeach
    @endif

    {{-- 2. Tampilkan Produk Normal --}}
    @foreach($products as $product)
      {{-- Lewati jika produk ini sudah dirender di slot promosi --}}
      @if($hasPromotions && $promotedProducts->contains('id', $product->id))
        @continue
      @endif

      <div class="square-product-card" data-category="{{ $product->category }}" data-price="{{ $product->price }}">
        <div class="img-container">
          <img src="{{ $product->display_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition duration-300 brightness-90 hover:brightness-80">
          <div class="absolute top-2 left-2 flex flex-col gap-1.5 items-start">
            <span class="bg-brand-accent text-white text-xs font-bold px-2 py-1 rounded">
              {{ $product->category }}
            </span>
            @if($product->isPromoted())
              <span class="bg-amber-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded shadow-sm flex items-center gap-0.5">
                🔥 Sponsor
              </span>
            @endif
          </div>
          <div class="absolute top-2 right-5 gap-3 flex">
            @auth
              <form method="POST" action="{{ route('favorite.toggle', ['product_id' => $product->id]) }}">
                @csrf
                <button class="sml-square-btn text-sm cursor-pointer btn-favorite {{ Auth::user()->favorites->contains('product_id', $product->id) ? 'btn-danger text-white' : 'bg-white text-slate-400 border border-slate-200 hover:text-red-500' }}" data-id="{{ $product->id }}">
                  <i class="fa-solid fa-heart"></i>
                </button>
              </form>
            @endauth
          </div>
        </div>
        <div class="p-4">
          <a href="{{ route('product.show', ['id' => $product->id]) }}">
            <h3 class="text-slate-800 truncate mb-1 text-lg font-bold">{{ $product->name }}</h3>
            <p class="font-display text-brand-muted text-sm mb-6">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
            <div class="flex items-center gap-2 mb-4">
              <div class="w-6 h-6 bg-slate-200 rounded-full overflow-hidden shrink-0">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($product->user->name) }}" alt="Avatar">
              </div>
              <span class="text-xs text-brand-muted truncate flex items-center gap-1.5">
                {{ $product->user->name }}
                @if($product->user->isPremium())
                  <span class="text-[8px] bg-amber-500 text-white px-1.5 py-0.5 rounded font-extrabold shrink-0">👑 Premium</span>
                @endif
                - {{ $product->user->major }}
              </span>
            </div>
          </a>
        </div>
      </div>
    @endforeach
  </div>
@endif