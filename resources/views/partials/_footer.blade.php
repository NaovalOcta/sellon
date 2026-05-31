<footer class="invisible bg-brand-main text-brand-muted py-8 mt-12 border-t border-slate-800">
  <div class="max-w-7xl mx-auto xl:mx-50 px-4 gap-x-20 flex flex-row items-start justify-between text-md">
    <div class="w-full gap-y-7 flex flex-col items-start">
      <a href="{{ route('home') }}" wire:navigate class="font-display font-bold text-2xl text-white tracking-tight space-x-2">
        <i class="fa fa-shopping-bag"></i>
        <span class="text-brand-accent text-3xl">S</span>ellOn
      </a>
      <p class="">The exclusive campus marketplace connecting student sellers and buyers in one trusted platform.</p>
    </div>

    <div class="w-full gap-y-7 flex flex-col items-start">
      <p class="text-white text-2xl font-bold">Quick Link</p>
      <div class="flex flex-col gap-y-2 items-start">
        <a href="{{ route('product.catalog') }}" wire:navigate class="nav-link cursor-pointer hover:text-brand-accent">Browse Products</a>
        <a href="{{ route('register') }}" wire:navigate class="nav-link cursor-pointer hover:text-brand-accent">Start Selling</a>
        {{-- <a href="{{ route('login') }}" wire:navigate class="nav-link cursor-pointer hover:text-brand-accent">Sign In</a> --}}
      </div>
    </div>

    <div class="w-full gap-y-7 flex flex-col items-start">
      <p class="text-2xl text-white font-bold">About</p>
      <p class="">SellOn is built by students, for students. Support your campus community by buying and selling locally.</p>
    </div>
  </div>
</footer>
