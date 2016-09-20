<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    AntoniusBavo_Agenda
 * @subpackage AntoniusBavo_Agenda/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    AntoniusBavo_Agenda
 * @subpackage AntoniusBavo_Agenda/admin
 * @author     Your Name <email@example.com>
 */
class AntoniusBavo_Agenda_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in AntoniusBavo_Agenda_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The AntoniusBavo_Agenda_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/antoniusbavo-agenda-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in AntoniusBavo_Agenda_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The AntoniusBavo_Agenda_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/antoniusbavo-agenda-admin.js', array( 'jquery' ), $this->version, false );

		/*wp_localize_script( $this->plugin_name, 'wpApiSettings', array( 'root'  => esc_url_raw( rest_url() ),
		                                                                'nonce' => wp_create_nonce( 'wp_rest' )
		) );*/
	}

	public function install_admin_menu() {
		add_options_page(
			__( 'H.H. Antonius en Bavo Agenda' ),
			__( 'Agenda' ),
			'manage_options',
			'antoniusbavo-agenda-admin-menu',
			array(
				$this,
				'render_admin_page'
			) );
	}

	public function render_admin_page() {
		die( wp_create_nonce( 'wp_rest' ) );
	}

}
