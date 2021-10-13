$(window).scroll(function () {
    var scroll = $(window).scrollTop();

    if (scroll >= 100) {
        $(".header-section").addClass("header-fix");
    } else {
        $(".header-section").removeClass("header-fix");
    }
});

(function() {
    // User Slider

    $('.slider-list').owlCarousel({
        loop: true,
        nav: true,
        dots: false,
        margin: 0,
        autoplay:true,
        animateOut: 'fadeOut',
        autoplayTimeout:5000,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    })

    // Dashboard Slider

    $('.dashboard-slider').owlCarousel({
        loop: true,
        nav: true,
        dots: true,
        animateOut: 'fadeOut',
        autoplay: false,
        margin: 0,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    })

    // Back To Top Button
    var btn = $("#backToTop");

    $(window).scroll(function() {
        if ($(window).scrollTop() > 300) {
            btn.addClass("show");
        } else {
            btn.removeClass("show");
        }
    });

    btn.on("click", function(e) {
        e.preventDefault();
        $("html, body").animate({ scrollTop: 0 }, "300");
    });
})();

(function($) {
    "use strict";

    jQuery(document).ready(function($) {});

    jQuery(window).load(function() {
        $(".preloader").fadeOut(1000);
    });
})(jQuery);