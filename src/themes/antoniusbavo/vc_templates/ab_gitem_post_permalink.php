<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 9/24/2016
 * Time: 8:24 PM
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var $this WPBakeryShortCode_AB_Gitem_Post_Permalink
 */

$output = $title = '';
extract( $this->getAttributes( $atts ) );

$output .= '<div class="vc_gitem-post-data vc_gitem-post-data-source-post_link">';
$output .= '<a href="{{ post_link_url }}" title="{{ post_title }}" class="vc_gitem-link">' . $title . '</a>';
$output .= "</div>";

echo $output;
