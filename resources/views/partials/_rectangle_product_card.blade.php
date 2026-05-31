{{-- 
VARIABLE:
$products = Array variable about all the data inside 'Product' table
$
--}}

@if($products->isEmpty())
  <div class="fade-in-effect flex-col justify-center items-center">
    <label class="mt-40 text-stone-400">There is no product yet</label>
  </div>
@else
  <div class="fade-in-effect gap-5 flex flex-col items-start justify-start text-start">
    @foreach($products as $product)
      <div class="rectangle-product-card w-full p-4 flex items-center justify-between" data-category="{{ $product->category }}" data-price="{{ $product->price }}">
        <a href="{{ route('product.show', ['id' => $product->id]) }}" wire:navigate class="gap-x-8 flex items-center justify-evenly">
          <img src="{{ $product->display_image }}" alt="{{ $product->name }}" class="w-22 h-22 rounded-2xl object-cover transition duration-300 brightness-90 hover:brightness-80">
          <div class="w-full flex flex-col items-start justify-evenly">
            <span class="bg-brand-accent text-white text-xs font-bold px-2 py-1 rounded">
              {{ $product->category }}
            </span>
            <h3 class=" text-slate-800 truncate mb-1 text-lg font-bold">{{ $product->name }}</h3>
            <p class="font-display text-brand-muted text-sm">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
          </div>
        </a>

        <div class="gap-x-5 flex items-center justify-evenly" >
          @auth
            <form method="POST" action="{{ route('favorite.toggle', ['product_id' => $product->id]) }}">
              @csrf
              <button class="sml-square-btn text-lg cursor-pointer btn-favorite {{ Auth::user()->favorites->contains('product_id', $product->id) ? 'btn-danger text-white' : 'bg-white text-slate-400 border border-slate-200 hover:text-red-500' }}" data-id="{{ $product->id }}">
                <i class="fa-solid fa-heart "></i>
              </button>
            </form>

            @if(Auth::id() === $product->user_id)
              <a href="{{ route('product.edit', ['id' => $product->id]) }}" wire:navigate class="sml-square-btn text-lg text-brand-accent">
                <i class="fa-solid fa-pen-to-square"></i>
              </a>

              <form action="{{ route('product.destroy', ['id' => $product->id]) }}" method="POST" class="inline m-0" onsubmit="return confirm('Yakin ingin menghapus produk ini dari katalog?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="sml-square-btn btn-danger text-lg cursor-pointer">
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