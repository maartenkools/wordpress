/**
 * Created by Maarten Kools on 1/8/2016.
 */
jQuery(document).on('click', '.install_flags_notice a', function () {
    $this = jQuery(this);
    var $parent = $this.closest('.install_flags_notice');

    $this.addClass('processing');
    $parent.find('.spinner').addClass('is-active');

    jQuery.ajax({
        url: ajaxurl,
        data: {
            action: 'cfc_install_flags'
        },
        success: function () {
            // Hide the notice
            $parent.hide();
        }
    });

    return false;
});
