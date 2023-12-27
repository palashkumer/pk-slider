(function ($) {
    jQuery(document).ready(function ($) {
      var swiper = new Swiper(".mySwiper", {
        lazy: true,
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
      });
    });
  })(jQuery);