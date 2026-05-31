$(document).ready(function () {
  $(document).on('scroll', function () {
    $('.invisible').each(function () {
      const sensor = 100;
      let elementTopPos = $(this).offset().top;
      let windowBottomPos = $(window).scrollTop() + $(window).height();

      if (windowBottomPos > elementTopPos + sensor) {
        $(this).removeClass('invisible').addClass('fade-in-effect visible');
      }
    });
  });


});

$(document).trigger('scroll');