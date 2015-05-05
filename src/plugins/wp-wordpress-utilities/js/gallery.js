(function (jQuery) {
    jQuery(document).ready(function () {
        var jElement = jQuery('.wpu-gallery');

        var autoplaySpeed = jElement.attr('data-autoplayspeed');

        jElement.slick({
            dots: true,
            adaptiveHeight: true,
            infinite: true,
            autoplay: autoplaySpeed > 0,
            autoplaySpeed: autoplaySpeed
        });
    });
})(jQuery);
