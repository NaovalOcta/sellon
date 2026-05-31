<nav class="bg-brand-main text-white sticky top-0 z-40 shadow-md">
  <div class="max-w-7xl mx-auto xl:mx-50 px-4 sm:px-6 lg:px-8">
    <div class="h-auto md:h-16 py-10 md:py-0 gap-y-10 flex flex-col md:flex-row items-center justify-center md:justify-between">
      <a href="{{ route('home') }}" wire:navigate class="font-display font-bold text-2xl tracking-tight space-x-2">
        <i class="fa fa-shopping-bag"></i>
        <span class="text-brand-accent text-3xl">S</span>ellOn
      </a>
      <div class="gap-y-3 md:gap-x-7 flex flex-col md:flex-row items-center justify-center overflow-x-auto hide-scrollbar w-full md:w-auto pb-2 md:pb-0 text-white text-sm">
        <a href="{{ route('home') }}" wire:navigate
          class="nav-link py-1 {{ request()->routeIs('home') ? 'border-b border-white -translate-y-1' : 'border-transparent' }} transition-all duration-300 cursor-pointer hover:border-b hover:border-white hover:-translate-y-1">Home</a>
        <a href="{{ route('product.catalog') }}" wire:navigate
          class="nav-link py-1 {{ request()->routeIs('product.catalog') ? 'border-b border-white -translate-y-1' : 'border-transparent' }} transition-all duration-300 cursor-pointer hover:border-b hover:border-white hover:-translate-y-1">Shop</a>
        <a href="{{ route('users.my-products') }}" wire:navigate
          class="nav-link py-1 {{ request()->routeIs('users.my-products') ? 'border-b border-white -translate-y-1' : 'border-transparent' }} transition-all duration-300 cursor-pointer hover:border-b hover:border-white hover:-translate-y-1">Dashboard</a>
      </div>

      @auth
        <form action="{{ route('logout') }}" method="POST" class="gap-x-5 flex flex-row items-center text-sm">
          <div class="relative flex flex-col gap-x-2">
            <p id="user-account" class="text-md cursor-pointer transition-all duration-300 hover:text-brand-light-accent">Halo, {{ Auth::user()->name }}</p> 
            <div id="user-menu" class="w-70 p-3 gap-y-1 hidden flex-col absolute top-9 left-0 bg-white text-stone-700 rounded-md border border-stone-300">
              <div class="px-3 py-3 mb-1 border-b border-stone-100 flex items-center gap-x-3 bg-stone-50 rounded-t-md">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode(Auth::user()->name) }}" alt="Avatar" class="w-10 h-10 object-cover rounded-full">
                <div class="flex flex-col overflow-hidden">
                  <span class="text-sm font-bold truncate">{{ Auth::user()->name }}</span>
                  <span class="text-xs text-stone-500 truncate">{{ strlen(Auth::user()->email) > 25 ? substr(Auth::user()->email, 0, 25) . '...' : Auth::user()->email }}</span>
                </div>
              </div>
              <label class="px-3 pt-2 pb-1 text-xs font-bold text-stone-400 uppercase tracking-wider">Product Dashboard</label>
              <a href="{{ route('users.my-products') }}" wire:navigate class="nav-link flex items-center gap-x-3 text-sm hover:text-brand-accent hover:bg-teal-50 px-3 py-2 rounded-md transition-colors">
                <i class="fa-solid fa-box-open w-5 text-center text-brand-accent"></i> My Products
              </a>
              <a href="{{ route('favorite.index') }}" wire:navigate class="nav-link flex items-center gap-x-3 text-sm hover:text-brand-accent hover:bg-teal-50 px-3 py-2 rounded-md transition-colors">
                <i class="fa-solid fa-heart w-5 text-center text-red-500"></i> My Favorites
              </a>
              <hr class="border-stone-100 my-1">
              <label class="px-3 pt-2 pb-1 text-xs font-bold text-stone-400 uppercase tracking-wider">Account Settings</label>
              <a href="{{ route('users.profile') }}" wire:navigate class="nav-link flex items-center gap-x-3 text-sm hover:text-brand-accent hover:bg-stone-50 px-3 py-2 rounded-md transition-colors">
                <i class="fa-solid fa-gear w-5 text-center text-stone-500"></i> My Profile
              </a>
            </div>
          </div>

          @csrf
          <button type="submit" class="btn btn-danger nav-link">Sign Out</button>
        </form>
      @else
        <div class="gap-y-3 flex flex-col  sm:flex-row justify-center items-center md:space-x-2 text-sm">
          <a href="{{ route('register') }}" wire:navigate class="btn btn-ghost text-white! nav-link">Register</a>
          <a href="{{ route('login') }}" wire:navigate class="btn btn-primary nav-link">Login</a>
        </div>
      @endauth
    </div>
  </div>
</nav>

@vite('resources/js/partials/_nav_js.js')