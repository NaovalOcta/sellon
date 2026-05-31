$(document).ready(function () {
  function initCatalogState() {
    // Only run on pages that have the filter container
    if (!$('#filter-container').length && !$('#search-bar').length) return;

    // Logic URL Parameter Filter
    const urlParams = new URLSearchParams(window.location.search);
    const activeUrlFilter = urlParams.get('filter') || 'All';
    const currentSearch = urlParams.get('search') || '';
    const currentSort = urlParams.get('sort') || 'Newest';

    // Render initial active states based on URL
    $('.filter-category').removeClass('active border-0 border-stone-400 bg-brand-main hover:bg-brand-accent text-white').addClass('inactive bg-white hover:bg-slate-200 text-slate-600 border border-slate-400');

    $('.filter-category').each(function () {
      if ($(this).data('filter').toString().toLowerCase() === activeUrlFilter.toLowerCase()) {
        $(this).removeClass('inactive bg-white hover:bg-slate-200 text-slate-600 border border-slate-400').addClass('active border-0 border-stone-400 bg-brand-main hover:bg-brand-accent text-white');
      }
    });

    if ($('#search-bar').length) {
      $('#search-bar').val(currentSearch);
    }

    if ($('#sort-select').length) {
      $('#sort-select').val(currentSort);
    }
  }

  // Helper untuk update URL
  function updateURL(key, value) {
    const params = new URLSearchParams(window.location.search);
    if (value && value !== 'All') {
      params.set(key, value);
    } else if (key === 'filter' && value === 'All') {
      params.delete(key);
    } else if (!value) {
      params.delete(key);
    }

    // Reset page ke 1 setiap kali filter/search/sort berubah
    params.delete('page');

    const newUrl = window.location.pathname + '?' + params.toString();
    if (typeof Livewire !== 'undefined' && Livewire.navigate) {
      Livewire.navigate(newUrl);
    } else {
      window.location.href = newUrl;
    }
  }

  // Event Delegation (runs once, persists across navigations)
  $(document).off('click', '.filter-category').on('click', '.filter-category', function () {
    // Immediate visual feedback before navigation
    $('.filter-category').removeClass('active border-0 border-stone-400 bg-brand-main hover:bg-brand-accent text-white').addClass('inactive bg-white hover:bg-slate-200 text-slate-600 border border-slate-400');
    $(this).removeClass('inactive bg-white hover:bg-slate-200 text-slate-600 border border-slate-400').addClass('active border-0 border-stone-400 bg-brand-main hover:bg-brand-accent text-white');

    const filterValue = $(this).data('filter');
    updateURL('filter', filterValue);
  });

  // Trigger pencarian hanya jika menekan tombol Enter (key code 13)
  $(document).off('keypress', '#search-bar').on('keypress', '#search-bar', function (e) {
    if (e.which === 13) {
      const searchValue = $(this).val();
      updateURL('search', searchValue);
    }
  });

  $(document).off('change', '#sort-select').on('change', '#sort-select', function () {
    const sortValue = $(this).val();
    updateURL('sort', sortValue);
  });

  // Run on first page load
  document.addEventListener('DOMContentLoaded', initCatalogState);
  // Run on Livewire SPA navigations
  document.addEventListener('livewire:navigated', initCatalogState);
});