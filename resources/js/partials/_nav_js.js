// ============================================================
// NAV JS — Bekerja dengan Livewire SPA (wire:navigate)
// ============================================================

// Desktop: Toggle dropdown user-account (event delegation — aman di SPA)
$(document).on('click', '#user-account', function (e) {
  e.stopPropagation();
  $('#user-menu').toggleClass('hidden');
});

// Desktop: Tutup dropdown saat klik di luar
$(document).on('click', function (e) {
  if (!$(e.target).closest('#user-account, #user-menu').length) {
    $('#user-menu').addClass('hidden');
  }
});

// ============================================================
// Mobile: Hamburger Menu Toggle
// Fungsi bernama agar bisa dipanggil ulang setelah navigasi
// Livewire SPA tanpa menambah listener duplikat pada tombol.
// ============================================================
function initMobileNav() {
  const btn  = document.getElementById('mobile-menu-btn');
  const menu = document.getElementById('mobile-menu');

  if (!btn || !menu) return;

  // Hapus listener lama dengan clone (cara paling andal)
  const newBtn = btn.cloneNode(true);
  btn.parentNode.replaceChild(newBtn, btn);

  // Helper: tutup menu
  function closeMenu() {
    menu.classList.remove('nav-open');
    const icon = document.getElementById('hamburger-icon');
    if (icon) {
      icon.classList.remove('fa-xmark', 'rotate');
      icon.classList.add('fa-bars');
    }
  }

  // Helper: buka menu
  function openMenu() {
    menu.classList.add('nav-open');
    const icon = document.getElementById('hamburger-icon');
    if (icon) {
      icon.classList.remove('fa-bars');
      icon.classList.add('fa-xmark', 'rotate');
    }
  }

  // Toggle saat tombol hamburger diklik
  newBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    menu.classList.contains('nav-open') ? closeMenu() : openMenu();
  });

  // Tutup menu otomatis saat link di dalam menu diklik
  menu.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', closeMenu);
  });
}

// ============================================================
// Guards — hanya register listener global SATU KALI seumur
// hidup halaman agar tidak menumpuk saat SPA navigasi.
// ============================================================
if (!window._mobileNavListenerRegistered) {
  window._mobileNavListenerRegistered = true;

  // Re-inisialisasi tombol setiap navigasi Livewire selesai
  document.addEventListener('livewire:navigated', function () {
    initMobileNav();
  });

  // Tutup menu saat klik di luar area <nav>
  document.addEventListener('click', function (e) {
    const menu = document.getElementById('mobile-menu');
    if (menu && !e.target.closest('nav')) {
      menu.classList.remove('nav-open');
      const icon = document.getElementById('hamburger-icon');
      if (icon) {
        icon.classList.remove('fa-xmark', 'rotate');
        icon.classList.add('fa-bars');
      }
    }
  });
}

// Inisialisasi pertama kali (saat script pertama kali diload)
initMobileNav();