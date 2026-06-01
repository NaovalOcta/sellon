import { triggerToast } from '../utils';

$(document).ready(function () {

  // --- 1. Inisialisasi: sync visibility section berdasarkan kategori yang sudah dipilih ---
  function syncSectionsToCategory() {
    const cat = $('#category').val();
    if (cat === 'Preloved') {
      $('#condition-section').show();
      $('#stock-section').show();
    } else if (cat === 'Service') {
      $('#stock-section').hide();
      $('#condition-section').hide();
    } else {
      $('#stock-section').show();
      $('#condition-section').hide();
    }
  }
  syncSectionsToCategory(); // Jalankan saat halaman pertama kali load

  // --- 2. Update visibility saat kategori diubah ---
  $(document).on('change', '#category', function () {
    syncSectionsToCategory();
    $('input[name="condition"]').prop('checked', false);
    $('#err-condition').hide();
  });

  const maxImages = 5;

  window.updateAddButtonVisibility = function () {
    const currentImages = $('.existing-image').length + $('.new-image').length;
    if (currentImages >= maxImages) {
      $('#add-image-button').hide();
    } else {
      $('#add-image-button').css('display', 'flex');
    }
  };

  // Jalankan saat halaman pertama load
  if ($('#add-image-button').length) {
    window.updateAddButtonVisibility();
  }

  // Jalankan juga setiap kali Livewire SPA navigasi (back/forward)
  document.addEventListener('livewire:navigated', function () {
    if ($('#add-image-button').length) {
      window.updateAddButtonVisibility();
    }
  });

  $(document).on('click', '#add-image-button', function () {
    $('#input-file-cursor').click();
  });

  $(document).on('change', '.dynamic-file-input', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const maxImages = 5;
    const maxSize = 5 * 1024 * 1024; // 5MB
    let currentImages = $('.existing-image').length + $('.new-image').length;

    if (currentImages >= maxImages) {
      triggerToast('toast_error', 'Maximum 5 images!');
      $(this).val('');
      return;
    }


    if (file.size > maxSize) {
      triggerToast('toast_error', 'Maximum 5MB image size!');
      $(this).val('');
      return;
    }

    const inputCounter = Date.now();
    const inputId = `file-input-${inputCounter}`;
    $(this).attr('id', inputId);
    $(this).removeClass('dynamic-file-input').addClass('active-file-input');

    // Create a new empty input for the next file
    $('#new-images-inputs').append('<input type="file" name="new_image_urls[]" accept="image/png, image/jpeg" class="dynamic-file-input" id="input-file-cursor">');

    const reader = new FileReader();
    reader.onload = function (e) {
      const previewHtml = `
        <div class="upload-box relative group new-image" data-input-id="${inputId}">
          <img src="${e.target.result}" alt="preview" class="w-full h-full object-cover rounded-xl border border-slate-200">
          <div class="remove-new-img absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity" data-input-id="${inputId}">✕</div>
        </div>
      `;
      $(previewHtml).insertBefore('#add-image-button');
      if (window.updateAddButtonVisibility) window.updateAddButtonVisibility();
      $('#err-foto').hide();
    };
    reader.readAsDataURL(file);
  });

  // Remove existing image
  $(document).on('click', '.remove-existing-img', function () {
    const id = $(this).data('id');
    $(this).closest('.existing-image').remove();
    $('#deleted-images-container').append(`<input type="hidden" name="deleted_images[]" value="${id}">`);
    if (window.updateAddButtonVisibility) window.updateAddButtonVisibility();
  });

  // Remove new image
  $(document).on('click', '.remove-new-img', function () {
    const inputId = $(this).data('input-id');
    $(`#${inputId}`).remove();
    $(this).closest('.new-image').remove();
    if (window.updateAddButtonVisibility) window.updateAddButtonVisibility();
  });

  // Char count for description
  $(document).on('input', '#description', function () {
    $('#char-count').text($(this).val().length);
  });

  // Validation before submit
  $(document).on('submit', '#form-product', function (e) {
    let isValid = true;

    // Validasi gambar
    let currentImages = $('.existing-image').length + $('.new-image').length;
    if (currentImages === 0) {
      e.preventDefault();
      $('#err-foto').show();
      triggerToast('toast_error', 'Please select at least 1 product image!');
      return false;
    }

    // Validasi stock hanya jika section-nya visible
    if ($('#stock-section').is(':visible')) {
      if ($('#stock').val() === '' || $('#stock').val() === null) {
        e.preventDefault();
        $('#stock').addClass('error').siblings('.err-msg').show();
        triggerToast('toast_error', 'Please complete the data marked in red.');
        isValid = false;
      }
    }

    // Validasi condition hanya jika Preloved dan section visible
    if ($('#category').val() === 'Preloved' && $('#condition-section').is(':visible')) {
      if (!$('input[name="condition"]:checked').val()) {
        e.preventDefault();
        $('#err-condition').show();
        triggerToast('toast_error', 'Please complete the data marked in red.');
        isValid = false;
      }
    }

    if (!isValid) return false;

    // Clean empty input
    $('.dynamic-file-input').remove();
  });
});
