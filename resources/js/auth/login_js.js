import { triggerToast } from '../utils';

$(document).ready(function () {
  // Event Delegation for Login Validation
  $(document).on('submit', '#form-login', function (e) {
    // Prevent default web reload (Make the error not be updated)
    e.preventDefault();

    const email = $('#email').val();
    const pass = $('#password').val();
    const UMMEmail = '@webmail.umm.ac.id';
    let isValid = true;

    // Reset errors
    $('.input-field').removeClass('error');
    $('[id^="err-"]').hide();

    // Validasi email UMM
    if (email.length === 0) {
      $('#err-login-email').text('UMM Email must not be Empty.');
      isValid = false;
    } else if (!email.includes(UMMEmail)) {
      $('#err-login-email').text('Use UMM Email (' + UMMEmail + ').');
      isValid = false;
    }

    if (pass.length === 0) {
      $('#err-login-password').text('Password must not be Empty.');
      isValid = false;
    } else if (pass.length < 5) {
      $('#err-login-password').text('Password must be at least 5 characters.');
      isValid = false;
    }

    if (!isValid) {
      $('#email').addClass('error');
      $('#password').addClass('error');
      $('#err-login-email').show();
      $('#err-login-password').show();

      triggerToast('toast_error', 'Please check your login data.');
    } else {
      this.submit();
    }
  });
});