(function (jQuery) {
    jQuery(document).ready(function () {
        jQuery('.wpu-gallery').slick({
            dots: true,
            autoplay: settings.autoplaySpeed > 0,
            autoplaySpeed: settings.autoplaySpeed
        });
    });

    jQuery(window).load(function () {
        jQuery('.wpu-gallery .slick-slide').centerToParent('y');

        jQuery('.wpu-gallery img').each(function () {
            var jImage = jQuery(this);

            var width = jImage.width();
            var height = jImage.height();

            var jLink = jImage.parent('a');
            jLink.css({
                width: width,
                height: height
            });

            jLink.centerToParent();

            var position = jImage.position();

            jImage.siblings('div.image-overlay').css({
                width: width,
                height: height,
                top: position.top,
                left: position.left
            });
        });
    });
})(jQuery);
