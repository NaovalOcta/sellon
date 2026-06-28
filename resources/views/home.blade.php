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

  {{-- Promoted Products --}}
  @if(isset($promotedProducts) && count($promotedProducts) > 0)
  <section class="invisible max-w-7xl px-6 py-12 mx-auto">
    <div class="flex items-end justify-between mb-8">
      <div>
        <h2 class="font-serif scale-y-125 text-3xl md:text-4xl font-display font-bold text-brand-main">
          🌟 Produk Sponsor
        </h2>
        <p class="text-brand-muted mt-2 text-lg">Pilihan terbaik untuk Anda hari ini</p>
      </div>
    </div>
    @include('partials._square_product_card', ['products' => $promotedProducts])
  </section>
  @endif

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

  {{-- Premium Seller Landing Section --}}
  <section class="invisible bg-white border-t border-slate-100 py-20">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="inline-block px-4 py-1.5 rounded-full bg-brand-accent/10 text-brand-accent text-xs font-bold mb-4 uppercase tracking-wider">
          Upgrade Toko Anda
        </span>
        <h2 class="text-3xl md:text-4xl font-display font-bold text-brand-main mb-6 leading-tight">
          Menjadi <span class="text-brand-accent">Premium Seller</span> & Tingkatkan Penjualan!
        </h2>
        <p class="text-brand-muted text-base sm:text-lg leading-relaxed">
          Dapatkan berbagai fitur eksklusif untuk membantu bisnis kampus Anda tumbuh lebih cepat dan menjangkau lebih banyak mahasiswa.
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-center">
        {{-- Benefit Cards (Left - Spans 2 cols on lg) --}}
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-brand-secondary p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-brand-accent/10 flex items-center justify-center mb-6">
              <i class="fa-solid fa-gem text-brand-accent text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-brand-main mb-2">Badge Premium</h3>
            <p class="text-brand-muted text-sm leading-relaxed">
              Tampilkan badge "✨ Premium" pada nama toko dan produk Anda untuk meningkatkan kepercayaan pembeli.
            </p>
          </div>

          <div class="bg-brand-secondary p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-brand-accent/10 flex items-center justify-center mb-6">
              <i class="fa-solid fa-boxes-stacked text-brand-accent text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-brand-main mb-2">Batas Upload 100 Produk</h3>
            <p class="text-brand-muted text-sm leading-relaxed">
              Upload hingga 100 produk aktif sekaligus (dari batas normal gratis hanya 20 produk) untuk variasi katalog Anda.
            </p>
          </div>

          <div class="bg-brand-secondary p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-brand-accent/10 flex items-center justify-center mb-6">
              <i class="fa-solid fa-chart-line text-brand-accent text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-brand-main mb-2">Statistik Toko</h3>
            <p class="text-brand-muted text-sm leading-relaxed">
              Pantau jumlah kunjungan ke produk Anda dan tren popularitas untuk menganalisis minat pembeli.
            </p>
          </div>

          <div class="bg-brand-secondary p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-brand-accent/10 flex items-center justify-center mb-6">
              <i class="fa-solid fa-percent text-brand-accent text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-brand-main mb-2">Diskon Promoted Listing</h3>
            <p class="text-brand-muted text-sm leading-relaxed">
              Dapatkan harga spesial diskon 30% untuk mengiklankan produk Anda di homepage dan katalog utama.
            </p>
          </div>
        </div>

        {{-- Pricing Card (Right - Spans 1 col) --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-lg overflow-hidden">
          <div class="p-8 text-center border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-xl font-bold text-brand-main mb-2">Paket MVP Premium</h3>
            <p class="text-brand-muted text-xs">Akses penuh ke semua fitur premium seller</p>
            <div class="mt-4 flex justify-center items-baseline">
              <span class="text-3xl font-extrabold text-brand-main">Rp15.000</span>
              <span class="text-brand-muted ml-2 text-xs">/ 30 Hari</span>
            </div>
          </div>
          <div class="p-8">
            <ul class="space-y-3 mb-6">
              <li class="flex items-center text-xs text-slate-700">
                <i class="fa-solid fa-circle-check text-brand-success mr-2.5"></i>
                Batas Kapasitas 100 Produk
              </li>
              <li class="flex items-center text-xs text-slate-700">
                <i class="fa-solid fa-circle-check text-brand-success mr-2.5"></i>
                Badge Identitas Toko Premium
              </li>
              <li class="flex items-center text-xs text-slate-700">
                <i class="fa-solid fa-circle-check text-brand-success mr-2.5"></i>
                Akses Dashboard Statistik
              </li>
              <li class="flex items-center text-xs text-slate-700">
                <i class="fa-solid fa-circle-check text-brand-success mr-2.5"></i>
                Diskon Promoted Listing 30%
              </li>
            </ul>

            @guest
              <a href="{{ route('login') }}" class="btn btn-primary w-full py-3 rounded-xl font-bold text-center text-xs">
                Login Untuk Berlangganan
              </a>
            @else
              @if($activeSubscription)
                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-3 text-center">
                  <span class="text-brand-accent text-xs font-bold block mb-0.5">👑 Akun Premium Aktif!</span>
                  <span class="text-slate-600 text-[10px]">Sampai: {{ $activeSubscription->expires_at->format('d M Y') }}</span>
                </div>
              @elseif($pendingOrder)
                <div class="space-y-2">
                  <a href="{{ route('premium.confirm', ['id' => $pendingOrder->id]) }}" class="btn btn-primary w-full py-3 rounded-xl font-bold text-center text-xs">
                    Lanjutkan Konfirmasi Bayar
                  </a>
                  <form action="{{ route('premium.cancel', ['id' => $pendingOrder->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pendaftaran ini?');">
                    @csrf
                    <button type="submit" class="btn btn-ghost w-full py-1.5 text-xs text-red-500 font-medium hover:bg-red-50/50 rounded-xl">
                      Batalkan Pendaftaran
                    </button>
                  </form>
                </div>
              @else
                @foreach($plans as $plan)
                  <form action="{{ route('premium.subscribe') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <button type="submit" class="btn btn-primary w-full py-3 rounded-xl font-bold cursor-pointer text-xs">
                      Berlangganan Sekarang
                    </button>
                  </form>
                @endforeach
              @endif
            @endguest
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Promoted Listing Section --}}
  <section class="invisible bg-brand-secondary border-t border-slate-100 py-20">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="inline-block px-4 py-1.5 rounded-full bg-brand-accent/10 text-brand-accent text-xs font-bold mb-4 uppercase tracking-wider">
          Iklankan Produk Anda
        </span>
        <h2 class="text-3xl md:text-4xl font-display font-bold text-brand-main mb-6 leading-tight">
          Jadikan Produk Anda <span class="text-brand-accent">Sorotan Utama</span> Kampus!
        </h2>
        <p class="text-brand-muted text-base sm:text-lg leading-relaxed">
          Tampilkan produk Anda di slot teratas homepage dan hasil pencarian katalog. Menarik perhatian calon pembeli 5x lebih cepat.
        </p>
      </div>

      {{-- How It Works --}}
      <div class="mb-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center">
            <div class="w-10 h-10 rounded-2xl bg-brand-accent/10 flex items-center justify-center mb-4 mx-auto">
              <span class="text-brand-accent font-extrabold text-sm">1</span>
            </div>
            <h3 class="text-md font-bold text-brand-main mb-2">Pilih Produk & Paket</h3>
            <p class="text-brand-muted text-xs leading-relaxed">
              Pilih produk aktif Anda dan durasi promosi yang diinginkan (3, 7, atau 14 hari).
            </p>
          </div>
          <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center">
            <div class="w-10 h-10 rounded-2xl bg-brand-accent/10 flex items-center justify-center mb-4 mx-auto">
              <span class="text-brand-accent font-extrabold text-sm">2</span>
            </div>
            <h3 class="text-md font-bold text-brand-main mb-2">Lakukan Pembayaran</h3>
            <p class="text-brand-muted text-xs leading-relaxed">
              Transfer manual sesuai nominal paket. Unggah bukti pembayaran untuk diverifikasi admin.
            </p>
          </div>
          <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center">
            <div class="w-10 h-10 rounded-2xl bg-brand-accent/10 flex items-center justify-center mb-4 mx-auto">
              <span class="text-brand-accent font-extrabold text-sm">3</span>
            </div>
            <h3 class="text-md font-bold text-brand-main mb-2">Promosi Aktif!</h3>
            <p class="text-brand-muted text-xs leading-relaxed">
              Produk Anda otomatis tampil di bagian atas homepage dan katalog, lengkap dengan badge "🔥 Promoted".
            </p>
          </div>
        </div>
      </div>

      {{-- Pricing Packages --}}
      <div>
        <h3 class="text-xl font-bold text-brand-main text-center mb-8">Pilihan Paket Promosi</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
          @foreach($packages as $package)
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition-shadow">
              <div class="p-6 text-center bg-slate-50/50 border-b border-slate-100">
                <span class="inline-block px-3 py-1 rounded-full bg-slate-100 text-brand-main text-[10px] font-bold mb-2">
                  Sponsor {{ $package->name }}
                </span>
                <div class="mt-2 flex justify-center items-baseline">
                  <span class="text-2xl font-extrabold text-brand-main">Rp{{ number_format($package->price_regular, 0, ',', '.') }}</span>
                </div>
                <p class="text-brand-muted text-[10px] mt-0.5">Durasi: {{ $package->duration_days }} Hari</p>
              </div>
              <div class="p-6 flex-grow flex flex-col justify-between">
                <div class="mb-4 space-y-2">
                  <div class="flex items-center justify-between text-[10px] py-1 border-b border-dashed border-slate-100">
                    <span class="text-slate-500 font-medium">Akun Biasa</span>
                    <span class="font-bold text-brand-main">Rp{{ number_format($package->price_regular, 0, ',', '.') }}</span>
                  </div>
                  <div class="flex items-center justify-between text-[10px] py-1 bg-indigo-50/50 p-1.5 rounded-lg border border-indigo-100/50">
                    <span class="text-brand-accent font-bold"><i class="fa-solid fa-crown mr-1"></i> Premium</span>
                    <span class="font-extrabold text-brand-accent">Rp{{ number_format($package->price_premium, 0, ',', '.') }}</span>
                  </div>
                  <p class="text-[9px] text-brand-muted text-center">Hemat 30% untuk Premium Seller!</p>
                </div>
                @auth
                  <a href="{{ route('promote.create') }}?package_id={{ $package->id }}" class="btn btn-primary w-full py-2 rounded-xl font-bold text-xs text-center">
                    Pilih Paket Ini
                  </a>
                @else
                  <a href="{{ route('login') }}" class="btn btn-primary w-full py-2 rounded-xl font-bold text-xs text-center">
                    Login Untuk Membeli
                  </a>
                @endauth
              </div>
            </div>
          @endforeach
        </div>
      </div>
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