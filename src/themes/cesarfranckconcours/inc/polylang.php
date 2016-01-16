<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 1/8/2016
 * Time: 9:08 PM
 */
add_action( 'admin_enqueue_scripts', 'cesarfranckconcours_polylang_admin_enqueue_scripts' );
add_action( 'admin_notices', 'cesarfranckconcours_polylang_install_flags_notice' );
add_action( 'wp_ajax_cfc_install_flags', 'cesarfranckconcours_polylang_install_flags' );

function cesarfranckconcours_polylang_admin_enqueue_scripts() {
	wp_enqueue_script( 'cfc_polylang_scripts', get_template_directory_uri() . '/js/polylang.js', array( 'jquery' ), CFC_THEME_VERSION, true );
	wp_enqueue_style('cfc_polylang_styles', get_template_directory_uri() . '/layouts/polylang.css', false, CFC_THEME_VERSION, 'all');
}

function cesarfranckconcours_polylang_install_flags_notice() {
	if ( ! defined( 'PLL_LOCAL_DIR' ) ) {
		return;
	}

	$installFlags = false;

	// If the PLL_LOCAL_DIR exists, check if all the flags are installed. Otherwise, show the admin notice
	if ( ! file_exists( PLL_LOCAL_DIR ) ) {
		$installFlags = true;
	} else {
		$handle = opendir( get_template_directory() . '/images/flags' );

		try {
			while ( false !== ( $entry = readdir( $handle ) ) ) {
				// See if the entry ends with .png, but only in reverse
				if ( stripos( strrev( $entry ), 'gnp.' ) !== 0 ) {
					continue;
				}

				if ( ! file_exists( PLL_LOCAL_DIR . DIRECTORY_SEPARATOR . $entry ) ) {
					$installFlags = true;
					break;
				}
			}

			closedir( $handle );
		} catch ( Exception $e ) {
			closedir( $handle );
		}
	}

	if ( ! $installFlags ) {
		return;
	}

	?>
	<div class="error install_flags_notice">
		<p><?php printf( __( 'The custom flags for the <strong>%1$s</strong> theme are not installed.' ), wp_get_theme()->get( 'Name' ) ); ?></p>

		<div>
			<a href="#"><span><?php echo __( 'Install' ); ?></a></span>
			<div class="spinner"></div>
		</div>
	</div>
<?php
}

function cesarfranckconcours_polylang_install_flags() {
	try {
		if ( ! file_exists( PLL_LOCAL_DIR ) ) {
			mkdir( PLL_LOCAL_DIR );
		}

		$handle = opendir( get_template_directory() . '/images/flags' );

		try {
			while ( false !== ( $entry = readdir( $handle ) ) ) {
				// See if the entry ends with .png, but only in reverse
				if ( stripos( strrev( $entry ), 'gnp.' ) !== 0 ) {
					continue;
				}

				if ( file_exists( PLL_LOCAL_DIR . DIRECTORY_SEPARATOR . $entry ) ) {
					continue;
				}

				copy( get_template_directory() . '/images/flags/' . $entry, PLL_LOCAL_DIR . DIRECTORY_SEPARATOR . $entry );
			}

			closedir( $handle );

			// Ensure Polylang will pick up the new flags immediately
			delete_transient( 'pll_languages_list' );
		} catch ( Exception $e ) {
			closedir( $handle );
		}
	} catch ( Exception $e ) {

	}
}
