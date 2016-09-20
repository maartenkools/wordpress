<?php


/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           AntoniusBavo_Agenda
 *
 * @wordpress-plugin
 * Plugin Name:       H.H. Antonius en Bavo Parochie Agenda
 * Description:       Een WordPress plugin voor de agenda van de H.H. Antonius en Bavo parochie.
 * Version:           {wp-major}.{wp-minor}.{build}.{revision}
 * Author:            Maarten Kools
 * License:           The MIT License (MIT)
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       antoniusbavo-agenda
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_antoniusbavo_agenda() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-antoniusbavo-agenda-activator.php';
	AntoniusBavo_Agenda_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-antoniusbavo-agenda-deactivator.php
 */
function deactivate_antoniusbavo_agenda() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-antoniusbavo-agenda-deactivator.php';
	AntoniusBavo_Agenda_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_antoniusbavo_agenda' );
register_deactivation_hook( __FILE__, 'deactivate_antoniusbavo_agenda' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-antoniusbavo-agenda.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_antoniusbavo_agenda() {

	$plugin = new AntoniusBavo_Agenda(  );
	$plugin->run();

}

run_antoniusbavo_agenda();
