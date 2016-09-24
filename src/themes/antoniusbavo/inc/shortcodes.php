<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 9/20/2016
 * Time: 9:55 PM
 */
require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-gitem-post-data.php' );

class WPBakeryShortCode_AB_Gitem_Post_Permalink extends WPBakeryShortCode {
	protected function getFileName() {
		return 'ab_gitem_post_permalink';
	}

	public function getAttributes( $atts ) {
		$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
		extract( $atts );

		return array(
			'title' => isset( $title ) ? $title : 'Read More'
		);
	}
}
