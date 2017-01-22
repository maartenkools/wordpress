<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 1/22/2017
 * Time: 2:40 PM
 */
namespace rkbavo;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function post_link( $atts ) {
	$atts = shortcode_atts( array(
		'link_title' => 'Read more'
	), $atts );

	$output = '<div class="vc_gitem-post-data vc_gitem-post-data-source-post_link">';
	$output .= '<a href="{{ post_link_url }}" title="{{ post_title }}" class="vc_gitem-link">' . $atts['link_title'] . '</a>';
	$output .= "</div>";

	return $output;
}

add_shortcode('post_link', 'rkbavo\\post_link');
