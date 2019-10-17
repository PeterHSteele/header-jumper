<?php

/**
 * Handles interactions with wp_options table
 *
 * A class definition with attributes and functions used to 
 * interact with the options api. Registers and reads
 * plugin settings.
 *
 * @link       github.com/peterhsteele/heading-jumper
 * @since      1.0.0
 *
 * @package    Heading_Jumper
 * @subpackage Heading_Jumper/includes
 * @author     Peter Steele steele.peter.3@gmail.com
 */
if ( ! class_exists( 'Heading_Jumper_Options' ) ){
class Heading_Jumper_Options {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $heading_jumper    The ID of this plugin.
	 */
	private $heading_jumper;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	* sets the plugin id and version.
	*
	* @since    1.0.0
	* @param      string    $heading_jumper       The name of this plugin.
	* @param      string    $version    The version of this plugin.
	*/

	public function __construct( $heading_jumper, $version ){
		$this->$heading_jumper = $heading_jumper;
		$this->version = $version;
	}

	/**
	* Retrieves an element of heading_jumper_settings array
	* 
	* @since 1.0.0
	* @param string 	name 	the name of the option to get
	*/

	public function get( $name ){
		return get_option( 'heading_jumper_settings' )[$name];
	}

	/**
	* Retrieves the list of pages on which the
	* plugin will display, as an array
	* 
	* @since 1.0.0
	* @param string 	name 	the name of the option to get
	*/
	
	public function get_pages( ){

		if ( ! function_exists('trim_slug')){
			function trim_slug( $slug ){
				return trim( $slug );
			}
		}

		$pages = $this->get( 'pages' );

		if ( ! $pages ){
			return null;
		}

		$slugs = explode( ',', $pages );
		
		return array_map( 'trim_slug', $slugs );
	}

	/**
	* Registers a plugin setting
	*
	* @since 1.0.0
	*
	* @param string 	$group 		the settings group name
	* @param string 	$name 		name of the setting to save
	* @param string 	$callback 	function to sanitize the setting
	* 
	*/

	public function register( $group, $name, $callback = null ){
		register_setting( $group, $name, $callback );
	}
}
}