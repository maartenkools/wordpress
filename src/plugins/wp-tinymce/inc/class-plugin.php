<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 1/16/2017
 * Time: 8:57 PM
 */
namespace wp_tinymce {
	class Plugin {
		public static $textDomain = 'wp-tinymce';
		private $pluginDir;
		private $baseName;
		private $file;
		private $options;
		private $admin;

		/**
		 * Initializes a new instance of the Plugin class.
		 *
		 * @param $file string The full path to the main file of the plugin.
		 */
		public function __construct( $file ) {
			$this->pluginDir = plugin_dir_path( $file );
			$this->baseName  = plugin_basename( $file );
			$this->file      = $file;

			register_activation_hook( $this->file, array( 'wp_tinymce\Plugin', 'onActivate' ) );
			register_deactivation_hook( $this->file, array( 'wp_tinymce\Plugin', 'onDeactivate' ) );

			if ( ! defined( 'WP_INSTALLING' ) || WP_INSTALLING == false ) {
				add_action( 'plugins_loaded', array( $this, 'onPluginsLoaded' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueueAdminScripts' ) );
			}

			\PucFactory::buildUpdateChecker(
				'https://raw.githubusercontent.com/maartenkools/wordpress/master/updater/wp-tinymce.json',
				$this->file,
				'wp-tinymce'
			);
		}

		/**
		 * Gets the current instance of the WPU_Plugin class.
		 * @return WPU_Plugin The current instance of the WPU_Plugin class, if initialized; otherwise, null.
		 */
		public static function current() {
			if ( ! isset( $GLOBALS['wpu_plugin'] ) ) {
				return null;
			}

			return $GLOBALS['wpu_plugin'];
		}

		/**
		 * Called by WordPress when the plugin is activated.
		 */
		public static function onActivate() {
			do_action( 'wp_tinymce_activate' );
		}

		/**
		 * Called by WordPress when the plugin is deactivated.
		 */
		public static function onDeactivate() {
			do_action( 'wp_tinymce_deactivate' );
		}

		/**
		 * Called by WordPress when the activated plugins have been loaded.
		 */
		public function onPluginsLoaded() {
			add_filter( 'wp_headers', array( $this, 'onBeforeHeadersSent' ) );
		}

		/**
		 * Called by WordPress before the headers are sent to the browser.
		 *
		 * @param $headers array The list of headers to be sent.
		 *
		 * @return array The list of headers to be sent.
		 */
		public function onBeforeHeadersSent( $headers ) {
			if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) !== false ) ) {
				$headers['X-UA-Compatible'] = 'IE=edge,chrome=1';
			}

			return $headers;
		}

		/**
		 * Called by WordPress when scripts and stylesheets may be queued.
		 */
		public function enqueueAdminScripts() {
			// Scripts
			wp_enqueue_script( 'tinymce', includes_url() . 'js/tinymce/tinymce.min.js');
			wp_enqueue_script( 'tinymce_plugin', includes_url() . 'js/tinymce/plugins/compat3x/plugin.min.js');

			do_action( 'wp_tinymce_enqueue_scripts' );
		}

		/**
		 * Gets the filesystem directory path (with trailing slash) of the plugin.
		 * @return string The file system directory path of the plugin.
		 */
		public function get_pluginDir() {
			return $this->pluginDir;
		}

		/**
		 * Gets the path to the main plugin file relative to the plugins directory, without the leading and trailing slashes.
		 * @return string The path to the main plugin file relative to the plugins directory.
		 */
		public function get_baseName() {
			return $this->baseName;
		}

		/**
		 * Gets the version of the plugin.
		 * @return string The version of the plugin.
		 */
		public function get_version() {
			return '{wp-major}.{wp-minor}.{build}.{revision}';
		}
	}
}
