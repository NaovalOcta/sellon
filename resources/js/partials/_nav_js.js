$(document).ready(function () {
  $(document).on('click', '#user-account', function (e) {
    e.preventDefault();
    $('#user-menu').toggleClass('hidden');
  });
});