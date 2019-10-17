<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://github.com/peterhsteele/heading-jumper
 * @since      1.0.0
 *
 * @package    Heading_Jumper
 * @subpackage Heading_Jumper/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Heading_Jumper
 * @subpackage Heading_Jumper/includes
 * @author     Peter Steele steele.peter.3@gmail.com
 */
class Heading_Jumper_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'heading-jumper',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
