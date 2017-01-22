<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 1/21/2017
 * Time: 1:55 PM
 */
namespace rkbavo;

class Actions {
	public static function enqueue_admin_scripts() {
		wp_enqueue_style( 'rkbavo_admin', get_stylesheet_directory_uri() . '/css/admin.css', array( 'js_composer' ), Theme::$current->version );
	}

	public static function enqueue_scripts() {
		// Unregister unused scripts
		$script_handles = array(
			'2checkout',
			'my_stripe'
		);
		foreach ( $script_handles as $script_handle ) {
			wp_dequeue_script( $script_handle );
			wp_deregister_script( $script_handle );
		}

		wp_enqueue_style( 'rkbavo_revolution_slider', get_stylesheet_directory_uri() . '/css/revolution_slider.css', array( 'rs-plugin-settings' ), Theme::$current->version );
		wp_enqueue_style( 'rkbavo_js_composer_tta', get_stylesheet_directory_uri() . '/css/js_composer_tta.css', array( 'vc_tta_style' ), Theme::$current->version );
	}

	public static function head() {
		echo '<link rel="stylesheet" type="text/css" id="rkbavo_style-css" media="all" href="' . get_stylesheet_uri() . '?ver=' . Theme::$current->version . '">';
	}

	/**
	 * Maps shortcodes to Visual Composer
	 */
	public static function vc_map_shortcodes() {
		vc_map( array(
			'name'        => __( 'Agenda', Theme::$current->textDomain ),
			'description' => __( 'Adds a Google Agenda', Theme::$current->textDomain ),
			'base'        => 'calendar',
			'icon'        => 'sprite sprite-calendar',
			'category'    => Theme::$current->name,
			'params'      => array(
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'Agenda', Theme::$current->textDomain ),
					'param_name'  => 'id',
					'description' => __( 'The agenda to display', Theme::$current->textDomain ),
					'admin_label' => true,
					'value'       => array_reduce( get_posts( array( 'post_type' => 'calendar', 'nopaging' => true ) ),
						function ( $result, $post ) {
							$result[ $post->post_title ] = $post->ID;

							return $result;
						} )
				)
			)
		) );


		vc_map( array(
			'name'        => __( 'Post link', Theme::$current->textDomain ),
			'description' => __( 'A link to a post', Theme::$current->textDomain ),
			'base'        => 'post_link',
			'icon'        => 'sprite sprite-link',
			'category'    => Theme::$current->name,
			'post_type'   => \Vc_Grid_Item_Editor::postType(),
			'params'      => array(
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Link title', Theme::$current->textDomain ),
					'param_name' => 'link_title'
				)
			)
		) );
	}
}
