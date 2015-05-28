<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 3/14/2015
 * Time: 1:06 PM
 */

/**
 * Provides access to the plugin options.
 */
class WPU_Options {
	private $optionDefinitions;
	private $name;

	/**
	 * Initializes a new instance of the WPU_Options class.
	 */
	public function __construct() {
		$this->name = '_wp_utilities';

		add_action( 'init', array( $this, 'onInitialize' ) );
	}

	/**
	 * Automatically called by WordPress when the plugin is initializing.
	 */
	public function onInitialize() {
		$this->optionDefinitions = $this->__createOptionDefinitions();
	}

	/**
	 * Gets the value of the specified option in the specified section.
	 *
	 * @param $section string The name of the section from which to get the value.
	 * @param $name string The name of the option to get.
	 *
	 * @return mixed The value of the specified option.
	 */
	public function getValue( $section, $name ) {
		$optionDefinition = null;

		$sections = $this->get_optionDefinitions();
		if ( is_array( $sections ) ) {
			$opts = $sections[ $section ];

			if ( isset( $opts ) ) {
				foreach ( $opts['options'] as $option ) {
					if ( $option['name'] != $name ) {
						continue;
					}

					$optionDefinition = $option;
					break;
				}
			}
		}

		if ( $optionDefinition == null ) {
			throw new InvalidArgumentException( WPU_String::format( 'No option definition found with the specified name ({0}) and section ({1}).', array(
						$name,
						$section
					) ) );
		}

		$options = get_option( $this->get_name() );
		if ( ! $options || ! isset( $options[ $section ] ) || ! isset( $options[ $section ][ $name ] ) ) {
			return $optionDefinition['default'];
		}

		$value = $options[ $section ][ $name ];
		switch ( $optionDefinition['type'] ) {
			case WPU_DataType::Text:
				return $value;

			case WPU_DataType::Integer:
				return (int) $value;

			case WPU_DataType::Checkbox:
				return $value == 'on';
		}

		return $value;
	}

	private function __createOptionDefinitions() {
		$options['general'] = array(
			'title'       => __( 'General', WPU_Plugin::$textDomain ),
			'description' => __( 'General settings for the WordPress Utilities plugin.', WPU_Plugin::$textDomain ),
			'options'     => array(
				array(
					'name'        => 'debug',
					'label'       => __( 'Enable Debug Mode', WPU_Plugin::$textDomain ),
					'description' => __( 'Enables the debug mode for the plugin.', WPU_Plugin::$textDomain ),
					'type'        => WPU_DataType::Checkbox,
					'default'     => false
				),
				array(
					'name'        => 'enable_lightbox',
					'label'       => __( 'Enable lightbox', WPU_Plugin::$textDomain ),
					'description' => __( 'Images that link to the original will be displayed in a lightbox.', WPU_Plugin::$textDomain ),
					'type'        => WPU_DataType::Checkbox,
					'default'     => false
				)
			)
		);

		$options['gallery'] = array(
			'title'       => __( 'Gallery', WPU_Plugin::$textDomain ),
			'description' => __( 'Provides settings for the image gallery.', WPU_Plugin::$textDomain ),
			'options'     => array(
				array(
					'name'        => 'randomize',
					'label'       => __( 'Randomize', WPU_Plugin::$textDomain ),
					'description' => __( 'Enable to randomize the images in the gallery.', WPU_Plugin::$textDomain ),
					'type'        => WPU_DataType::Checkbox,
					'default'     => false
				),
				array(
					'name'        => 'autoplay_speed',
					'label'       => __( 'Auto Play Speed', WPU_Plugin::$textDomain ),
					'description' => __( 'The auto play speed in milliseconds. Specify 0 to disable.', WPU_Plugin::$textDomain ),
					'type'        => WPU_DataType::Integer,
					'default'     => 0
				),
				array(
					'name'        => 'max_width',
					'label'       => __( 'Maximum Width', WPU_Plugin::$textDomain ),
					'description' => __( 'The maximum width of the images in the gallery.', WPU_Plugin::$textDomain ),
					'type'        => WPU_DataType::Integer,
					'default'     => 250
				),
				array(
					'name'        => 'max_height',
					'label'       => __( 'Maximum Height', WPU_Plugin::$textDomain ),
					'description' => __( 'The maximum height of the images in the gallery.', WPU_Plugin::$textDomain ),
					'type'        => WPU_DataType::Integer,
					'default'     => 250
				)
			)
		);

		$options['archives'] = array(
			'title'       => __( 'Archives', WPU_Plugin::$textDomain ),
			'description' => __( 'Provides settings for the archives.', WPU_Plugin::$textDomain ),
			'options'     => array(
				array(
					'name'        => 'excluded_categories',
					'label'       => __( 'Excluded Categories', WPU_Plugin::$textDomain ),
					'description' => __( 'A comma separated list of category ids to exclude on the archive listing.', WPU_Plugin::$textDomain ),
					'type'        => WPU_DataType::Text,
					'default'     => ''
				)
			)
		);

		return $options;
	}

	/**
	 * Gets the option definitions.
	 * @return array An associative array containing the option definitions for each section.
	 */
	public function get_optionDefinitions() {
		return $this->optionDefinitions;
	}

	/**
	 * Gets the name of the setting.
	 * @return string The name of the setting.
	 */
	public function get_name() {
		return $this->name;
	}
} 
