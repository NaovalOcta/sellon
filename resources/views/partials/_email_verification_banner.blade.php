{{-- 
  Banner peringatan verifikasi email.
  Hanya tampil jika user sudah login TAPI email belum diverifikasi.
  Di-include di base_layout tepat setelah navbar.
--}}
@auth
  @if(!Auth::user()->hasVerifiedEmail())
    <div id="email-verification-banner" class="bg-amber-400 text-amber-900 px-4 py-2.5">
      <div class="max-w-7xl mx-auto xl:mx-50 flex flex-col sm:flex-row items-center justify-between gap-y-2 gap-x-4">
        <div class="flex items-center gap-x-2 text-sm font-medium">
          <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
          <span>
            Email Anda <strong>{{ Auth::user()->email }}</strong> belum diverifikasi. Beberapa fitur tidak dapat diakses sebelum verifikasi selesai.
          </span>
        </div>
        <div class="flex items-center gap-x-3 flex-shrink-0">
          <a href="{{ route('verification.notice') }}" wire:navigate
            class="text-xs font-semibold bg-amber-900 text-amber-50 px-3 py-1.5 rounded-lg hover:bg-amber-800 transition-colors duration-200 whitespace-nowrap">
            <i class="fa-solid fa-envelope mr-1"></i> Verifikasi Sekarang
          </a>
          <button onclick="document.getElementById('email-verification-banner').style.display='none'"
            class="text-amber-700 hover:text-amber-900 transition-colors duration-200 p-1">
            <i class="fa-solid fa-xmark text-sm"></i>
          </button>
        </div>
      </div>
    </div>
  @endif
@endauth
