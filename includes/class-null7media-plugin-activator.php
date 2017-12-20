<?php

/**
 * Fired during plugin activation
 *
 * @link       http://bullyard.no
 * @since      1.0.0
 *
 * @package    Null7media_Plugin
 * @subpackage Null7media_Plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Null7media_Plugin
 * @subpackage Null7media_Plugin/includes
 * @author     Rodrigo <Perez>
 */
class Null7media_Plugin_Activator {

	/**
	 * Creates table for logging when plugin is activated
	 *
	 *
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		$table_name = $wpdb->prefix . BY_CONF_tablename;
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE ".$table_name." (
			id int(9) NOT NULL AUTO_INCREMENT,
			log_query varchar(255) DEFAULT NULL,
			timestamp datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
			) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'by_db_version', BY_CONF_table_version );
	}

}
