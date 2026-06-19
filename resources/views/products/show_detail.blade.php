@extends('layouts.base_layout')

@section('title', $product->name . ' - SellOn')

@section('content')

{{-- 
VARIABLE:
$product = Variable about detail of the product 
--}}
<main class="grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-[75vh] bg-brand-secondary break-all sm:break-normal">
  <nav class="fade-in-effect text-sm font-medium text-brand-muted mb-6 flex flex-row flex-wrap items-center gap-1 sm:gap-2">
    <a href="{{ route('product.catalog') }}" class="hover:text-brand-accent transition">Catalog</a>
    <span>›</span>
    <a href="{{ route('product.catalog') }}?filter={{ strtolower($product->category) }}" class="hover:text-brand-accent transition capitalize">Kategori {{ $product->category }}</a>
    <span>›</span>
    <span class="text-brand-main truncate max-w-[200px]">{{ $product->name }}</span>
  </nav>

  @if(session('success'))
    <div class="fade-in-effect bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">
      {{ session('success') }}
    </div>
  @endif

  <div class="fade-in-effect bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-slate-100 mb-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
      <div class="flex flex-col gap-4">
        <div class="w-full aspect-square bg-slate-100 rounded-xl overflow-hidden relative border border-slate-100">
          @if($product->images && $product->images->count() > 0)
            <img src="{{ $product->images->first()->display_image }}" alt="{{ $product->name }}"
              class="w-full h-full aspect-square object-cover transition-opacity duration-300" id="main-product-image">
          @else
            <img src="{{ $product->display_image }}" alt="{{ $product->name }}"
              class="w-full h-full object-cover transition-opacity duration-300" id="main-product-image">
          @endif
        </div>

        @if($product->images && $product->images->count() > 1)
        <div class="grid grid-cols-5 gap-1 md:gap-2">
          @foreach($product->images as $img)
            <div class="aspect-square rounded-lg overflow-hidden border-2 cursor-pointer transition-colors border-transparent hover:border-brand-main" 
                 onclick="document.getElementById('main-product-image').src='{{ $img->display_image }}'">
              <img src="{{ $img->display_image }}" class="w-full h-full object-cover" alt="thumbnail">
            </div>
          @endforeach
        </div>
        @endif
      </div>

      <div class="gap-y-2 flex flex-col">
        <div class="mb-2">
          <span
            class="inline-block bg-brand-accent text-white text-xs font-bold px-3 py-1 rounded-full capitalize">{{ $product->category }}</span>
        </div>
        <div>
          <h1 class="text-2xl md:text-4xl font-semibold text-brand-main mb-2">{{ $product->name }}</h1>
          <p class="font-semibold text-sm md:text-xl text-brand-main mb-4">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
        </div>

        <div class="flex flex-col sm:flex-row items-start gap-6 mb-6">
          <div class="gap-y-4 flex flex-col">
            <span class="text-sm md:text-base  font-semibold">Condition</span>
            <span class="bg-brand-secondary px-3 py-1 rounded-md text-sm text-center">{{ $product->condition ?? '-' }}</span>
          </div>
          <div class="gap-y-4 flex flex-col">
            <span class="text-sm md:text-base font-semibold">Stock</span>
            <span class="bg-brand-secondary px-3 py-1 rounded-md text-sm text-center">{{ $product->stock ?? '-' }}</span>
          </div>
        </div>

        <div class="mb-4">
          <h3 class="text-sm md:text-base font-semibold mb-4">Product Description</h3>
          <p class="leading-relaxed text-sm whitespace-pre-line text-brand-muted ">{{ $product->description }}</p>
        </div>

        <a href="{{ route('users.profile', $product->user->id) }}" class="bg-brand-secondary border border-slate-100 rounded-xl p-4 mb-4 gap-y-5 flex flex-col sm:flex-row justify-between items-center">
          <div class="flex flex-col sm:flex-row items-center gap-4">
            <div class="w-12 h-12 bg-white rounded-full overflow-hidden border border-slate-200 shrink-0">
              <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($product->user->name) }}"
                class="w-full h-full object-cover">
            </div>
            <div class="flex flex-col items-center sm:items-start">
              <h4 class="font-semibold text-brand-main flex items-center gap-1">{{ $product->user->name }}</h4>
              <p class="text-xs text-brand-muted"> • ⭐ Verified</p>
            </div>
          </div>
          <div>
            <p class="px-4 py-2 text-xs bg-brand-accent text-white rounded-full font-bold">{{ strtoupper($product->user->major) }}</p>
          </div>
        </a>

        @auth
          <div class="flex gap-4 w-full">
            <form method="POST" action="{{ route('favorite.toggle', ['product_id' => $product->id]) }}" class="m-0">
              @csrf
              <button class="btn detail-fav-btn btn-favorite {{ Auth::user()->favorites->contains('product_id', $product->id) ? 'btn-danger text-white' : 'btn-outline border-slate-300 text-slate-600' }} py-3! px-4! shadow-sm" data-id="{{ $product->id }}">
                <i class="fa-solid fa-heart {{ Auth::user()->favorites->contains('product_id', $product->id) ? 'text-white' : '' }}"></i>
              </button>
            </form>
            @php
              $phone = $product->user->whatsapp_no;
              $isPhoneValid = ($phone && trim($phone) !== '' && $phone !== '-' && strlen(trim($phone)) >= 9);
              if ($isPhoneValid) {
                  $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                  if (strpos($cleanPhone, '0') === 0) {
                      $cleanPhone = '62' . substr($cleanPhone, 1);
                  }
                  $message = "Hello, I am interested in the product on SellOn:\n\n📦 Product: " . $product->name . "\n💰 Price: Rp " . number_format($product->price, 0, ',', '.') . "\n\nIs it still available?";
                  $waUrl = "https://wa.me/" . $cleanPhone . "?text=" . urlencode($message);
              } else {
                  $waUrl = "#";
              }
            @endphp

            @if($isPhoneValid)
              <a href="{{ $waUrl }}"
                class="btn btn-success grow gap-2 text-md py-3! shadow-sm justify-center cursor-pointer">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                </svg>
                Buy Via WhatsApp
              </a>
            @else
              <a href="#" onclick="if(typeof window.triggerToast === 'function') { window.triggerToast('toast_error', 'Seller WhatsApp contact is invalid or unavailable.'); } return false;"
                class="btn btn-success grow gap-2 text-md py-3! shadow-sm justify-center cursor-pointer">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                </svg>
                Buy Via WhatsApp
              </a>
            @endif
          </div>
        @endauth
      </div>
    </div>
  </div>
</main>
@endsection
