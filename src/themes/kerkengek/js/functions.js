(function(jQuery) {
    jQuery(document).ready(
			function() {
				// Remove the home-link class, as it messes up the layout
			    jQuery('.navigation-main a[href="'+ bushwick_functions_vars.home_url + '"]')
						.closest('li')
						.removeClass('home-link');
			});
})(jQuery);