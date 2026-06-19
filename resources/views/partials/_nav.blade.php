<nav class="bg-brand-main text-white sticky top-0 z-40 shadow-md">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Desktop & Mobile Top Bar --}}
    <div class="h-16 flex items-center justify-between">
      {{-- Logo --}}
      <a href="{{ route('home') }}" class="font-display font-bold text-2xl tracking-tight space-x-2 shrink-0">
        <i class="fa fa-shopping-bag"></i>
        <span class="text-brand-accent text-3xl">S</span>ellOn
      </a>

      {{-- Desktop Nav Links --}}
      <div class="hidden md:flex items-center gap-x-7 text-white text-sm">
        <a href="{{ route('home') }}"
          class="nav-link py-1 {{ request()->routeIs('home') ? 'border-b border-white -translate-y-1' : 'border-transparent' }} transition-all duration-300 cursor-pointer hover:border-b hover:border-white hover:-translate-y-1">Home</a>
        <a href="{{ route('product.catalog') }}"
          class="nav-link py-1 {{ request()->routeIs('product.catalog') ? 'border-b border-white -translate-y-1' : 'border-transparent' }} transition-all duration-300 cursor-pointer hover:border-b hover:border-white hover:-translate-y-1">Shop</a>
        <a href="{{ route('users.my-products') }}"
          class="nav-link py-1 {{ request()->routeIs('users.my-products') ? 'border-b border-white -translate-y-1' : 'border-transparent' }} transition-all duration-300 cursor-pointer hover:border-b hover:border-white hover:-translate-y-1">Dashboard</a>
      </div>

      {{-- Desktop Auth Section --}}
      <div class="hidden md:flex items-center">
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
                <a href="{{ route('users.my-products') }}" class="nav-link flex items-center gap-x-3 text-sm hover:text-brand-accent hover:bg-teal-50 px-3 py-2 rounded-md transition-colors">
                  <i class="fa-solid fa-box-open w-5 text-center text-brand-accent"></i> My Products
                </a>
                <a href="{{ route('favorite.index') }}" class="nav-link flex items-center gap-x-3 text-sm hover:text-brand-accent hover:bg-teal-50 px-3 py-2 rounded-md transition-colors">
                  <i class="fa-solid fa-heart w-5 text-center text-red-500"></i> My Favorites
                </a>
                <hr class="border-stone-100 my-1">
                <label class="px-3 pt-2 pb-1 text-xs font-bold text-stone-400 uppercase tracking-wider">Account Settings</label>
                <a href="{{ route('users.profile') }}" class="nav-link flex items-center gap-x-3 text-sm hover:text-brand-accent hover:bg-stone-50 px-3 py-2 rounded-md transition-colors">
                  <i class="fa-solid fa-gear w-5 text-center text-stone-500"></i> My Profile
                </a>
              </div>
            </div>
            @csrf
            <button type="submit" class="btn btn-danger nav-link">Sign Out</button>
          </form>
        @else
          <div class="flex items-center space-x-2 text-sm">
            <a href="{{ route('register') }}" class="btn btn-ghost text-white! nav-link">Register</a>
            <a href="{{ route('login') }}" class="btn btn-primary nav-link">Login</a>
          </div>
        @endauth
      </div>

      {{-- Mobile Hamburger Button --}}
      <button id="mobile-menu-btn" class="md:hidden flex items-center justify-center w-10 h-10 rounded-lg hover:bg-white/10 transition-colors" aria-label="Toggle menu">
        <i id="hamburger-icon" class="fa-solid fa-bars text-xl"></i>
      </button>
    </div>
  </div>

  {{-- Mobile Dropdown Menu (animasi via CSS class nav-open) --}}
  <div id="mobile-menu" class="md:hidden border-t border-white/10 bg-brand-main">
    <div class="px-4 py-4 flex flex-col gap-y-1 text-sm">
      {{-- Mobile Nav Links --}}
      <a href="{{ route('home') }}"
        class="nav-link flex items-center gap-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('home') ? 'bg-white/10 font-semibold' : '' }} hover:bg-white/10 transition-colors">
        <i class="fa-solid fa-house w-5 text-center text-brand-accent"></i> Home
      </a>
      <a href="{{ route('product.catalog') }}"
        class="nav-link flex items-center gap-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('product.catalog') ? 'bg-white/10 font-semibold' : '' }} hover:bg-white/10 transition-colors">
        <i class="fa-solid fa-shop w-5 text-center text-brand-accent"></i> Shop
      </a>
      <a href="{{ route('users.my-products') }}"
        class="nav-link flex items-center gap-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('users.my-products') ? 'bg-white/10 font-semibold' : '' }} hover:bg-white/10 transition-colors">
        <i class="fa-solid fa-box-open w-5 text-center text-brand-accent"></i> Dashboard
      </a>

      <hr class="border-white/10 my-2">

      {{-- Mobile Auth Section --}}
      @auth
        <div class="flex items-center gap-x-3 px-4 py-3 overflow-hidden">
          {{-- Wrapper untuk mengunci ukuran avatar SVG --}}
          <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 bg-white/10">
            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode(Auth::user()->name) }}" alt="Avatar" class="w-full h-full object-cover">
          </div>
          <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
            <span class="font-semibold truncate block">{{ Auth::user()->name }}</span>
            <span class="text-xs text-white/60 truncate block">{{ Auth::user()->email }}</span>
          </div>
        </div>
        <a href="{{ route('favorite.index') }}" class="nav-link flex items-center gap-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-colors">
          <i class="fa-solid fa-heart w-5 text-center text-red-400"></i> My Favorites
        </a>
        <a href="{{ route('users.profile') }}" class="nav-link flex items-center gap-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-colors">
          <i class="fa-solid fa-gear w-5 text-center text-white/60"></i> My Profile
        </a>
        <form action="{{ route('logout') }}" method="POST" class="mt-1">
          @csrf
          <button type="submit" class="w-full btn btn-danger">Sign Out</button>
        </form>
      @else
        <div class="flex flex-col gap-y-2 pt-1">
          <a href="{{ route('register') }}" class="btn btn-ghost text-white! nav-link w-full justify-center border border-white/30">Register</a>
          <a href="{{ route('login') }}" class="btn btn-primary nav-link w-full justify-center">Login</a>
        </div>
      @endauth
    </div>
  </div>
</nav>

@vite('resources/js/partials/_nav_js.js')