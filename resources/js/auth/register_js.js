import { triggerToast } from '../utils';

// Validasi Register dengan Event Delegation
$(document).ready(function () {
  $(document).on('submit', '#form-register', function (e) {
    e.preventDefault();

    const name = $('#name').val();
    const nim = $('#nim').val();
    const email = $('#email').val();
    const major = $('#major').val();
    const wa = $('#whatsapp_no').val();
    const pass = $('#password').val();
    const passConf = $('#password_confirmation').val();
    const UMMEmail = '@webmail.umm.ac.id';
    let isValid = true;

    $('.input-field').removeClass('error');
    $('[id^="err-"]').hide();

    if (name.length === 0) {
      $('#err-name').text('Full Name must not be Empty.').show();
      isValid = false;
    }

    // Validasi NIM (15 digit angka)
    if (nim.length === 0) {
      $('#err-nim').text('NIM must not be Empty.').show();
      isValid = false;
    } else if (!/^\d{15}$/.test(nim)) {
      $('#nim').addClass('error');
      $('#err-nim').text('NIM must be 15 digits.').show();
      isValid = false;
    }

    if (!major) {
      $('#major').addClass('error');
      $('#err-jurusan').text('Major must not be Empty.').show();
      isValid = false;
    }

    if (email.length === 0) {
      $('#err-login-email').text('UMM Email must not be Empty.').show();
      isValid = false;
    } else if (!email.includes(UMMEmail)) {
      $('#err-login-email').text('Use UMM Email (' + UMMEmail + ').').show();
      isValid = false;
    }

    // Validasi WA (Diawali 08, wajib angka)
    if (wa.length === 0) {
      $('#err-no-whatsapp').text('WhatsApp Number must not be Empty.').show();
      isValid = false;
    } else if (!/^08\d{8,12}$/.test(wa)) {
      $('#whatsapp_no').addClass('error');
      $('#err-no-whatsapp').text('Invalid WhatsApp Number (must be 08...).').show();
      isValid = false;
    }

    // Validasi Password
    if (pass.length === 0) {
      $('#err-password').text('Password must not be Empty.').show();
      isValid = false;
    } else if (pass.length < 5) {
      $('#password').addClass('error');
      $('#err-password').text('Password must be at least 5 characters.').show();
      isValid = false;
    } else if (pass !== passConf) {
      $('#password, #password_confirmation').addClass('error');
      $('#err-password-conf').text('Confirm Password does not match.').show();
      isValid = false;
    }

    if (!isValid) {
      triggerToast('toast_error', 'Please check your registration data.');
    }
    else {
      // Send to UserController for further review
      setTimeout(() => {
        e.target.submit();
      }, 1000);
    }
  });

  // Real-time Number Only Restriction untuk NIM dan WA (ID matching view)
  $(document).on('input', '#nim, #whatsapp_no', function () {
    this.value = this.value.replace(/[^0-9]/g, '');
  });
});