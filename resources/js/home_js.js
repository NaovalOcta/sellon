import { triggerToast } from './utils';

$(document).ready(function () {
  $(window).on('click', '.btn-buy', function () {
    triggerToast('toast_success', 'Opening WhatsApp...');
  });
});