<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 03/12/2015
 * Time: 7:06 PM
 */

define( 'DNBB_THEME_VERSION', '{wp-major}.{wp-minor}.{build}.{revision}' );

function wp_dnbb_enqueue_styles() {
	if ( is_child_theme() ) {
		$onetone_custom_css = "";
		if ( isset( $header_image ) && ! empty( $header_image ) ) {
			$onetone_custom_css .= ".home-header{background:url(" . $header_image . ") repeat;}\n";
		}
		if ( 'blank' != get_header_textcolor() && '' != get_header_textcolor() ) {
			$header_color = ' color:#' . get_header_textcolor() . ';';
			$onetone_custom_css .= '.home-header,.site-name,.site-description{' . $header_color . '}';
		}
		$custom_css = onetone_option( "custom_css" );
		$onetone_custom_css .= '.site{' . $background . '}';

		$links_color = onetone_option( 'links_color' );
		if ( $links_color == "" || $links_color == null ) {
			$links_color = "#963";
		}

		$onetone_custom_css .= 'a,.site-logo a:hover,.site-navigation a:hover,.widget a:hover,.entry-title a:hover,.entry-meta a:hover,.loop-pagination a:hover,.page_navi a:hover,.site-footer a:hover,.home-navigation > ul > li.current > a > span,.home-navigation > ul > li.current-menu-item > a > span,.home-navigation li a:hover,.home-navigation li.current a,.home-navigation li.current-menu-item a,.home-footer a:hover,#back-to-top,#back-to-top span{color:' . $links_color . ';}#back-to-top {border:1px solid ' . $links_color . ';}mark,ins,.widget #wp-calendar #today{background:' . $links_color . '; }::selection{background:' . $links_color . ' !important;}::-moz-selection{background:' . $links_color . ' !important;}';

		$top_menu_font_color = onetone_option( 'font_color' );
		if ( $top_menu_font_color != "" && $top_menu_font_color != null ) {
			$onetone_custom_css .= 'header #menu-main > li > a span,header .top-nav > ul > li > a span{color:' . $top_menu_font_color . '}';
		}

		$onetone_custom_css .= $custom_css;

		wp_dequeue_style( 'wr-pb-frontend' );
		wp_deregister_style( 'wr-pb-frontend' );

		wp_dequeue_style( 'onetone-main' );
		wp_deregister_style( 'onetone-main' );
		wp_enqueue_style( 'onetone-main', get_template_directory_uri() . '/style.css', array(), '1.3.7' );

		wp_add_inline_style( 'onetone-main', $onetone_custom_css );
	}

	wp_enqueue_style( 'denieuwebavobloeit-main', get_stylesheet_uri(), array(), DNBB_THEME_VERSION );
}

add_action( 'wp_enqueue_scripts', 'wp_dnbb_enqueue_styles', PHP_INT_MAX );

function wp_dnbb_after_setup_theme() {
	load_child_theme_textdomain( 'onetone', dirname( __FILE__ ) . '/languages/' );

	add_theme_support( 'html5', array( 'search-form' ) );
}

add_action( 'after_setup_theme', 'wp_dnbb_after_setup_theme' );

require_once( dirname( __FILE__ ) . '/updater/theme-update-checker.php' );
$updateChecker = new ThemeUpdateChecker(
	'denieuwebavobloeit',
	'https://raw.githubusercontent.com/maartenkools/wordpress/master/updater/denieuwebavobloeit.json'
);
