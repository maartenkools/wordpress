(function (jQuery) {
    jQuery(document).ready(function () {
        jQuery('.wpu-gallery').slick({
            dots: true,
            adaptiveHeight: true,
            infinite: true,
            autoplay: settings.autoplaySpeed > 0,
            autoplaySpeed: settings.autoplaySpeed
        });
    });
})(jQuery);
