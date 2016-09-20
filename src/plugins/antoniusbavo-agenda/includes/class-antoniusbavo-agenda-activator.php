<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    AntoniusBavo_Agenda
 * @subpackage AntoniusBavo_Agenda/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    AntoniusBavo_Agenda
 * @subpackage AntoniusBavo_Agenda/includes
 * @author     Your Name <email@example.com>
 */
class AntoniusBavo_Agenda_Activator {

	private static $db_version = 1;

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		AntoniusBavo_Agenda_Activator::install_table();
	}

	private static function install_table() {
		//https://codex.wordpress.org/Creating_Tables_with_Plugins
		global $wpdb;

		$table_name      = $wpdb->prefix . 'agenda';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table_name} (
				id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
				location BIT NOT NULL,
				event TEXT NOT NULL,
				start_date DATE NOT NULL,
				start_time TIME NULL,
				end_time TIME NULL,
				end_date DATE NOT NULL,
				UNIQUE KEY id (id)
				) {$charset_collate};";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'antoniusbavo_agenda_db_version', AntoniusBavo_Agenda_Activator::$db_version );
	}

}
