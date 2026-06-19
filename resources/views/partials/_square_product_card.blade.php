{{-- 
VARIABLE:
$products = Array variable about all the data inside 'Product' table
--}}

@if($products->isEmpty())
  <div class="fade-in-effect flex flex-col justify-center items-center">
    <label class="mt-40 text-stone-400">There is no product yet</label>
  </div>
@else
  <div class="fade-in-effect grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="product-grid">
    @foreach($products as $product)
      <div class="square-product-card" data-category="{{ $product->category }}" data-price="{{ $product->price }}">
        <div class="img-container">
          <img src="{{ $product->display_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition duration-300 brightness-90 hover:brightness-80">
          <span class="absolute top-2 left-2 bg-brand-accent text-white text-xs font-bold px-2 py-1 rounded">
            {{ $product->category }}
          </span>
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
            <h3 class=" text-slate-800 truncate mb-1 text-lg font-bold">{{ $product->name }}</h3>
            <p class="font-display text-brand-muted text-sm mb-6">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
            <div class="flex items-center gap-2 mb-4">
              <div class="w-6 h-6 bg-slate-200 rounded-full overflow-hidden">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($product->user->name) }}" alt="Avatar">
              </div>
              <span class="text-xs text-brand-muted truncate">{{ $product->user->name }} - {{ $product->user->major }}</span>
            </div>
          </a>
        </div>
      </div>
    @endforeach
  </div>
@endif  