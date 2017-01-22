<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 1/21/2017
 * Time: 1:38 PM
 */
namespace rkbavo;

class Theme {
	public static $current;

	public $name;
	public $version;
	public $textDomain;

	public function __construct() {
		if ( Theme::$current != null ) {
			throw new \LogicException( 'Only one instance of the Theme class can be instantiated.' );
		}

		Theme::$current = $this;

		$theme = wp_get_theme();

		$this->name       = $theme->Name;
		$this->version    = $theme->Version;
		$this->textDomain = $theme->TextDomain;
	}

	public function run() {
		$this->include_shortcodes();
		$this->add_actions();
		$this->add_filters();

		$updateChecker = new \ThemeUpdateChecker(
			'rkbavo',
			'https://raw.githubusercontent.com/maartenkools/wordpress/master/updater/rkbavo.json'
		);
	}

	private function include_shortcodes() {
		$directoryIterator = new \DirectoryIterator( get_stylesheet_directory() . '/shortcodes' );
		$iterator          = new \IteratorIterator( $directoryIterator );
		$files             = new \RegexIterator( $iterator, '/^(?!index).+\.php$/i', \RegexIterator::GET_MATCH );

		foreach ( $files as $filename ) {
			require( get_stylesheet_directory() . '/shortcodes/' . $filename[0] );
		}
	}

	private function add_actions() {
		add_action( 'admin_enqueue_scripts', 'rkbavo\\Actions::enqueue_admin_scripts' );
		add_action( 'wp_enqueue_scripts', 'rkbavo\\Actions::enqueue_scripts', 100 );
		add_action( 'wp_head', 'rkbavo\\Actions::head', 10000 );

		if ( function_exists( 'vc_map' ) ) {
			add_action( 'vc_before_init', 'rkbavo\\Actions::vc_map_shortcodes' );
		}
	}

	private function add_filters() {
	}
}
