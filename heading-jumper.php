<?php

/**
 *
 * @link              https://github.com/PeterHSteele/heading-jumper
 * @since             1.0.0
 * @package           Heading_Jumper
 *
 * @wordpress-plugin
 * Plugin Name:       Heading Jumper
 * Plugin URI:        http://github.com/peterhsteele/heading-jumper
 * Description:       intrapage navigation for longer articles
 * Version:           1.0.0
 * Author:            Peter Steele
 * Author URI:        https://github.com/peterhsteele
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       heading-jumper
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
define( 'HEADING_JUMPER_VERSION', '1.0.0' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-heading-jumper.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_heading_jumper() {

	$plugin = new Heading_Jumper();
	$plugin->run();

}
run_heading_jumper();
