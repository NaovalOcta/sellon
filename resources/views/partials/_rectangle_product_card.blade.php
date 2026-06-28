{{-- 
VARIABLE:
$products = Array variable about all the data inside 'Product' table
--}}

@if($products->isEmpty())
  <div class="fade-in-effect flex flex-col justify-center items-center">
    <label class="mt-40 text-stone-400">There is no product yet</label>
  </div>
@else
  <div class="fade-in-effect gap-5 flex flex-col items-start justify-start text-start w-full">
    @foreach($products as $product)
      <div class="rectangle-product-card w-full p-3 sm:p-4 flex items-center justify-between min-w-0 gap-x-4 {{ $product->isPromoted() ? 'border-2 border-amber-300 bg-amber-50/5' : '' }}" data-category="{{ $product->category }}" data-price="{{ $product->price }}">
        <a href="{{ route('product.show', ['id' => $product->id]) }}" class="gap-x-3 sm:gap-x-8 flex items-center min-w-0 grow">
          <img src="{{ $product->display_image }}" alt="{{ $product->name }}" class="w-16 h-16 sm:w-22 sm:h-22 rounded-2xl object-cover transition duration-300 brightness-90 hover:brightness-80 shrink-0">
          <div class="flex flex-col items-start justify-evenly min-w-0 w-full">
            <div class="flex items-center gap-1.5 mb-1 flex-wrap">
              <span class="bg-brand-accent text-white text-[10px] sm:text-xs font-bold px-2 py-0.5 sm:py-1 rounded">
                {{ $product->category }}
              </span>
              @if($product->isPromoted())
                <span class="bg-amber-500 text-white text-[9px] sm:text-[10px] font-extrabold px-1.5 py-0.5 rounded flex items-center gap-0.5">
                  🔥 Sponsor
                </span>
              @endif
              @if($product->user->isPremium())
                <span class="text-[9px] bg-amber-500 text-white px-1.5 py-0.5 rounded font-extrabold flex items-center gap-0.5">👑 Premium</span>
              @endif
            </div>
            <h3 class="text-slate-800 truncate text-sm sm:text-lg font-bold w-full">{{ $product->name }}</h3>
            <p class="font-display text-brand-muted text-xs sm:text-sm">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
          </div>
        </a>

        <div class="gap-x-2 sm:gap-x-5 flex items-center shrink-0">
          @auth
            <form method="POST" action="{{ route('favorite.toggle', ['product_id' => $product->id]) }}" class="m-0">
              @csrf
              <button class="sml-square-btn text-base sm:text-lg cursor-pointer btn-favorite {{ Auth::user()->favorites->contains('product_id', $product->id) ? 'btn-danger text-white' : 'bg-white text-slate-400 border border-slate-200 hover:text-red-500' }}" data-id="{{ $product->id }}">
                <i class="fa-solid fa-heart "></i>
              </button>
            </form>

            @if(Auth::id() === $product->user_id)
              <a href="{{ route('product.edit', ['id' => $product->id]) }}" class="sml-square-btn text-base sm:text-lg text-brand-accent">
                <i class="fa-solid fa-pen-to-square"></i>
              </a>

              <form action="{{ route('product.destroy', ['id' => $product->id]) }}" method="POST" class="inline m-0" onsubmit="return confirm('Yakin ingin menghapus produk ini dari katalog?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="sml-square-btn btn-danger text-base sm:text-lg cursor-pointer">
                    <i class="fa-solid fa-trash"></i>
                </button>
              </form>
            @endif
          @endauth
        </div>
      </div>
    @endforeach
  </div>
@endif