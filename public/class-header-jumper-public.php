<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Header_Jumper
 * @subpackage Header_Jumper/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Header_Jumper
 * @subpackage Header_Jumper/public
 * @author     Your Name <email@example.com>
 */
class Header_Jumper_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $header_jumper    The ID of this plugin.
	 */
	private $header_jumper;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $header_jumper       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $header_jumper, $version ) {

		$this->header_jumper = $header_jumper;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Header_Jumper_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Header_Jumper_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->header_jumper, plugin_dir_url( __FILE__ ) . 'css/header-jumper-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Header_Jumper_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Header_Jumper_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->header_jumper, plugin_dir_url( __FILE__ ) . 'js/header-jumper-public.js', array( 'jquery' ), $this->version, false );

	}

}
