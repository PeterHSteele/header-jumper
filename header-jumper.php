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
 * @package           Header_Jumper
 *
 * @wordpress-plugin
 * Plugin Name:       Header Jumper
 * Plugin URI:        http://example.com/header-jumper-uri/
 * Description:       table of contents/intrapage navigation for longer articles
 * Version:           1.0.0
 * Author:            Peter Steele
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       header-jumper
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'HEADER_JUMPER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-header-jumper-activator.php
 */
function activate_header_jumper() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-header-jumper-activator.php';
	Header_Jumper_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-header-jumper-deactivator.php
 */
function deactivate_header_jumper() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-header-jumper-deactivator.php';
	Header_Jumper_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_header_jumper' );
register_deactivation_hook( __FILE__, 'deactivate_header_jumper' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-header-jumper.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_header_jumper() {

	$plugin = new Header_Jumper();
	$plugin->run();

}
run_header_jumper();
