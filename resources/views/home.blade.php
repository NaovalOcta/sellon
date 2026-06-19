@extends('layouts.base_layout')

@section('title', 'SellOn - Marketplace Eksklusif Kampusmu')

@section('content')

{{-- 
VARIABLE:
$products = Array variable about all the data inside 'Product' table
--}}
<main class="bg-brand-secondary min-h-screen">
  {{-- Hero Section --}}
  <section class="fade-in-effect relative overflow-hidden mx-auto">
    <div class="max-w-7xl mx-auto px-6 py-15 md:py-20 grid md:grid-cols-2 gap-12 items-center">
      <div>
        <span class="inline-block px-4 py-1.5 rounded-full bg-brand-light-accent/20 text-brand-accent text-sm font-bold mb-6 tracking-wide">
          Campus Exclusive Marketplace
        </span>
        <h1 class="text-4xl md:text-5xl lg:text-6xl leading-tight tracking-tight font-display font-bold text-brand-main">
          Buy & Sell
          <br />
          Within Your
          <br />
          <span class="text-brand-accent">Campus</span>
        </h1>
        <p class="mt-6 text-brand-muted max-w-md text-lg leading-relaxed">
          A trusted marketplace built exclusively for students. Discover food, preloved items, and services from your fellow classmates.
        </p>
        <div class="flex flex-wrap gap-4 mt-8">
          <a href="{{ route('product.catalog') }}"
            class="px-8 py-3.5 rounded-full bg-brand-main text-white hover:bg-slate-800 transition-colors flex items-center gap-2 font-medium shadow-sm">
            Browse Products <i class="fa-solid fa-arrow-right text-sm"></i>
          </a>
          <a href="{{ route('users.my-products') }}"
            class="px-8 py-3.5 rounded-full border-2 border-slate-200 text-brand-main hover:bg-slate-50 transition-colors font-medium">
            Start Selling
          </a>
        </div>
      </div>
      <div class="relative hidden md:block">
        <div class="rounded-3xl overflow-hidden aspect-4/5 bg-brand-secondary shadow-sm">
          <img src="https://images.unsplash.com/photo-1542868727-2666cd25399c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjb2xsZWdlJTIwc3R1ZGVudCUyMHNlbGxpbmclMjBmb29kJTIwY2FtcHVzfGVufDF8fHx8MTc3NDc2NTc2MXww&ixlib=rb-4.1.0&q=80&w=1080"
            alt="Students marketplace"
            class="w-full h-full object-cover"/>
        </div>
        <div class="absolute -bottom-4 -left-4 bg-white rounded-2xl p-5 shadow-xl border border-slate-100">
          <p class="text-xs text-brand-muted font-bold tracking-wider uppercase">Active sellers</p>
          <p class="text-3xl font-bold text-brand-main mt-1">500+</p>
        </div>
      </div>
    </div>
  </section>

  {{-- Value Props --}}
  <section class="invisible bg-white border-y border-slate-100">
    <div class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-8">
      <div class="text-center md:text-left">
        <div class="w-14 h-14 rounded-2xl bg-brand-accent/10 flex items-center justify-center mx-auto md:mx-0 mb-5">
          <i class="fa-solid fa-shield-halved text-brand-accent text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-brand-main mb-3">Verified Students Only</h3>
        <p class="text-brand-muted text-sm leading-relaxed">All users are authenticated with campus email, ensuring a safe and trusted community.</p>
      </div>
      <div class="text-center md:text-left">
        <div class="w-14 h-14 rounded-2xl bg-brand-accent/10 flex items-center justify-center mx-auto md:mx-0 mb-5">
          <i class="fa-brands fa-whatsapp text-brand-accent text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-brand-main mb-3">Instant WhatsApp Order</h3>
        <p class="text-brand-muted text-sm leading-relaxed">No complicated checkout. One tap connects you directly to the seller via WhatsApp.</p>
      </div>
      <div class="text-center md:text-left">
        <div class="w-14 h-14 rounded-2xl bg-brand-accent/10 flex items-center justify-center mx-auto md:mx-0 mb-5">
          <i class="fa-solid fa-users text-brand-accent text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-brand-main mb-3">Support Your Peers</h3>
        <p class="text-brand-muted text-sm leading-relaxed">Every purchase helps a fellow student. Build a stronger campus economy together.</p>
      </div>
    </div>
  </section>

  {{-- Featured Products --}}
  <section class="invisible max-w-7xl px-6 py-20 mx-auto">
    <div class="flex items-end justify-between mb-10">
      <div>
        <h2 class="font-serif scale-y-125 text-3xl md:text-4xl font-display font-bold text-brand-main">
          Featured Products
        </h2>
        <p class="text-brand-muted mt-2 text-lg">Trending on campus right now</p>
      </div>
      <a href="{{ route('product.catalog') }}"
        class="hidden md:flex items-center gap-2 text-brand-accent font-semibold hover:text-brand-main transition-colors">
        View All <i class="fa-solid fa-arrow-right text-sm"></i>
      </a>
    </div>
    
    @include('partials._square_product_card', ['products' => $products->take(4)])

    <div class="mt-10 text-center md:hidden">
      <a href="{{ route('product.catalog') }}" class="text-brand-accent font-bold hover:underline flex items-center justify-center gap-2">
        View All Products <i class="fa-solid fa-arrow-right text-sm"></i>
      </a>
    </div>
  </section>

  {{-- CTA Section --}}
  <section class="invisible bg-brand-main text-white py-24">
    <div class="max-w-4xl mx-auto px-6 text-center">
      <h2 class="text-4xl md:text-5xl font-display font-bold text-white mb-6 leading-tight">
        Ready to Sell on Campus?
      </h2>
      <p class="text-brand-muted mt-4 text-lg">
        Join hundreds of students already using SellOn to grow their small business and reach more customers.
      </p>
      <a href="{{ route('register') }}"
        class="inline-block mt-10 px-10 py-4 text-lg rounded-full bg-brand-accent text-white font-bold hover:bg-brand-light-accent transition-colors shadow-lg shadow-brand-accent/30">
        Get Started Free
      </a>
    </div>
  </section>
</main>

@vite('resources/js/home_js.js')

@endsection