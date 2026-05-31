// Toast Notification System
export function triggerToast(type, message) {
  // Normalize type: strip 'toast_' prefix jika ada ('toast_success' → 'success')
  const cssType = type.replace('toast_', '');
  const id = 'toast-' + Date.now();
  const icon = cssType === 'success' ? '✅' : cssType === 'error' ? '❌' : 'ℹ️';
  const toastHTML = `
    <div id="${id}" class="toast ${cssType}">
      <div class="flex items-center gap-2 text-white">
        ${icon}
        <span>${message}</span>
      </div>
    </div>
  `;
  $('#toast-container').append(toastHTML);

  // Slide in
  setTimeout(() => $(`#${id}`).addClass('show'), 10);
  // Auto hide and remove after 4s
  setTimeout(() => {
    $(`#${id}`).removeClass('show');
    setTimeout(() => $(`#${id}`).remove(), 300);
  }, 4000);
}

// Make globally available for inline scripts in Blade
window.triggerToast = triggerToast;
