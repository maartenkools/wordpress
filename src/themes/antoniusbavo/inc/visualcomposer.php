<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 9/20/2016
 * Time: 9:19 PM
 */
require_once vc_path_dir( 'PARAMS_DIR', 'vc_grid_item/editor/class-vc-grid-item-editor.php' );

add_action( 'vc_before_init', 'ab_integrate_with_vc' );
function ab_integrate_with_vc() {
	vc_map( array(
		"name"                    => __( "Post Link", "antoniusbavo" ),
		"base"                    => "ab_gitem_post_permalink",
		"description"             => "Adds a link to the post",
		"class"                   => "",
		"show_settings_on_create" => false,
		"category"                => __( "Post", "antoniusbavo" ),
		"params"                  => array(
			array(
				"type"        => "textfield",
				"holder"      => "div",
				"class"       => "",
				"heading"     => __( "Title", "antoniusbavo" ),
				"param_name"  => "title",
				"value"       => __( "Read More", "antoniusbavo" ),
				"description" => "A link to the post"
			)
		),
		"post_type"               => Vc_Grid_Item_Editor::postType()
	) );
}
