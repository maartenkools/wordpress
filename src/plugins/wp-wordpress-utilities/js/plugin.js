/**
 * Created by Maarten Kools on 11/16/2014.
 */
(function (jQuery) {
    if (settings && settings.enableLightbox) {
        jQuery(document).ready(function () {
            var jImg = jQuery('.entry-content a > img[class*=wp-image]');
            var jLink = jImg.parent();

            var caption = jImg.attr('alt');

            jLink.attr('rel', 'lightbox');
            if (caption) jLink.attr('data-title', caption);
        });
    }
})(jQuery);
