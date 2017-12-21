<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://bullyard.no
 * @since             1.0.0
 * @package           Null7media_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       null7media-plugin
 * Plugin URI:        http://07media.no
 * Description:       Enkel plugin som registrerer en sidetemplate slik at dette kan velges for enkelte sider. Templaten beytter seg av ajax for å innhente valutakurs og vise dette på siden. Pluginen er basert på en plugin-boilerplate som forenkler og effektiviserer utviklingen av plugins.
 * Version:           1.0.0
 * Author:            Rodrigo
 * Author URI:        http://bullyard.no
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       null7media-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently pligin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('PLUGIN_NAME_VERSION', '1.0.0' );
define('BY_CONF_tablename', 'by_query_log');
define('BY_CONF_table_version', '1.0.0');


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-null7media-plugin-activator.php
 */
function activate_null7media_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-null7media-plugin-activator.php';
	Null7media_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-null7media-plugin-deactivator.php
 */
function deactivate_null7media_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-null7media-plugin-deactivator.php';
	Null7media_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_null7media_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_null7media_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-null7media-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_null7media_plugin() {

	$plugin = new Null7media_Plugin();
	$plugin->run();

}
run_null7media_plugin();
