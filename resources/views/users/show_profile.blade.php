@extends('layouts.base_layout')

@section('title', 'Profil Saya - SellOn')

@section('content')

{{-- 
VARIABLE:
$user = Data user yang sedang login
$products = Produk-produk milik user tersebut
--}}
<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-[73vh] bg-brand-secondary">
  {{-- Profile Card --}}
  <div class="fade-in-effect bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8 mb-8">
    <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
      <div class="w-24 h-24 bg-slate-100 rounded-full overflow-hidden border-4 border-brand-accent shrink-0 shadow-md">
        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($user->name) }}" alt="Avatar"
          class="w-full h-full object-cover">
      </div>
      <div class="text-center md:text-left grow">
        <div class="flex justify-between items-center gap-5">
          <div>
            <h1 class="text-2xl font-display font-bold text-brand-main mb-1">{{ $user->name }}</h1>
            <p class="text-brand-muted text-sm mb-4">{{ $user->major }}</p>
          </div>
          @auth
            @if(auth()->id() === $user->id)
              <a href="{{ route('users.edit_profile', ['id' => $user->id]) }}" class="btn btn-primary py-2! px-4! text-sm gap-1">
                <i class="fa-solid fa-pen"></i> Edit
              </a>
            @endif
          @endauth
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
          <div class="flex items-center gap-3 bg-brand-secondary rounded-xl px-4 py-3 border border-slate-100">
            <i class="fa-solid fa-envelope w-5 text-center text-brand-accent"></i>
            <div>
              <span class="text-xs text-brand-muted block">Email</span>
              <span class="font-medium text-slate-700">{{ $user->email }}</span>
            </div>
          </div>
          <div class="flex items-center gap-3 bg-brand-secondary rounded-xl px-4 py-3 border border-slate-100">
            <i class="fa-solid fa-id-card w-5 text-center text-brand-accent"></i>
            <div>
              <span class="text-xs text-brand-muted block">NIM</span>
              <span class="font-medium text-slate-700">{{ $user->nim }}</span>
            </div>
          </div>
          <div class="flex items-center gap-3 bg-brand-secondary rounded-xl px-4 py-3 border border-slate-100">
            <i class="fa-brands fa-whatsapp w-5 text-center text-green-500"></i>
            <div>
              <span class="text-xs text-brand-muted block">WhatsApp</span>
              <span class="font-medium text-slate-700">{{ $user->whatsapp_no }}</span>
            </div>
          </div>
          <div class="flex items-center gap-3 bg-brand-secondary rounded-xl px-4 py-3 border border-slate-100">
            <i class="fa-solid fa-graduation-cap w-5 text-center text-brand-accent"></i>
            <div>
              <span class="text-xs text-brand-muted block">Major</span>
              <span class="font-medium text-slate-700">{{ $user->major }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Produk Saya --}}
  <div class="fade-in-effect flex justify-between items-end mb-6">
    <h2 class="text-xl font-display font-bold text-brand-main">
      My Products <span class="text-brand-muted font-normal text-base">({{ $products->total() }})</span>
    </h2>
    @auth
      @if ($user->id === auth()->user()->id)
        <a href="{{ route('product.create') }}" class="btn btn-primary py-2! px-4! text-sm gap-1">
          <i class="fa-solid fa-plus"></i> Add New Product
        </a>
      @endif
    @endauth
  </div>

  @if($products->isEmpty())
    <div class="invisible flex flex-col justify-center items-center mt-20">
      <i class="fa-solid fa-heart-crack text-6xl text-slate-200 mb-4"></i>
      <label class="text-stone-500 font-medium">No products yet</label>
      <p class="text-sm text-stone-400 mt-2">Start by adding products to sell.</p>
      <a href="{{ route('product.create') }}" class="btn btn-primary mt-6">Add Product</a>
    </div>
  @else
    @include('partials._square_product_card', ['products' => $products])
    
    @if($products->hasPages())
      <div class="mt-8 flex justify-center">
        {{ $products->links() }}
      </div>
    @endif
  @endif
  
</main>

@endsection
