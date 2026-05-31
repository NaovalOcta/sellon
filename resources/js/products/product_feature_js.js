import { triggerToast } from '../utils';

$(document).ready(function () {
  // Use event delegation to handle clicks on favorite buttons across the app
  $(document).on('click', '.btn-favorite', function (e) {
    e.preventDefault(); // Prevent standard form submission for smooth reload

    const btn = $(this);
    const form = btn.closest('form');
    const url = form.attr('action');
    const token = form.find('input[name="_token"]').val();

    // Disable button during request to prevent double clicks
    btn.prop('disabled', true);

    $.ajax({
      url: url,
      type: 'POST',
      data: {
        _token: token
      },
      dataType: 'json',
      success: function (response) {
        if (response.status === 'added') {
          // Update UI for "added" state
          if (btn.hasClass('detail-fav-btn')) {
            btn.removeClass('btn-outline border-slate-300 text-slate-600').addClass('btn-danger text-white');
            btn.find('i').addClass('text-white');
          } else {
            btn.removeClass('bg-white text-slate-400 border border-slate-200 hover:text-red-500').addClass('btn-danger text-white');
          }
          triggerToast('success', response.message);
        } else if (response.status === 'removed') {
          // Update UI for "removed" state
          if (btn.hasClass('detail-fav-btn')) {
            btn.removeClass('btn-danger text-white').addClass('btn-outline border-slate-300 text-slate-600');
            btn.find('i').removeClass('text-white');
          } else {
            btn.removeClass('btn-danger text-white').addClass('bg-white text-slate-400 border border-slate-200 hover:text-red-500');
          }

          // Special handling for the "My Favorites" page to remove the card smoothly
          if ($('#product-favorite-container').length > 0) {
            btn.closest('.square-product-card, .rectangle-product-card').fadeOut(300, function () {
              $(this).remove();
              // If last item removed, we might want to refresh to show empty state
              if ($('.square-product-card, .rectangle-product-card').length === 0) {
                window.location.reload();
              }
            });
          }
          triggerToast('toast_success', response.message);
        }
      },
      error: function (xhr) {
        if (xhr.status === 401) {
          triggerToast('toast_error', 'Please login first!');
        } else {
          triggerToast('toast_error', 'An error occurred. Please try again.');
        }
      },
      complete: function () {
        btn.prop('disabled', false);
      }
    });
  });
});