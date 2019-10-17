<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       github.com/peterhsteele/heading-jumper
 * @since      1.0.0
 *
 * @package    Heading_Jumper
 * @subpackage Heading_Jumper/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Heading_Jumper
 * @subpackage Heading_Jumper/includes
 * @author     Peter Steele steele.peter.3@gmail.com
 */
if ( ! class_exists( 'Heading_Jumper' ) ){
class Heading_Jumper {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Heading_Jumper_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $heading_jumper    The string used to uniquely identify this plugin.
	 */
	protected $heading_jumper;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * instance of Heading_Jumper_Options used to retrieve settings
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $options   instance of options class
	 */
	private $options;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'HEADING_JUMPER_VERSION' ) ) {
			$this->version = HEADING_JUMPER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->heading_jumper = 'heading-jumper';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Heading_Jumper_Loader. Orchestrates the hooks of the plugin.
	 * - Heading_Jumper_i18n. Defines internationalization functionality.
	 * - Heading_Jumper_Admin. Defines all hooks for the admin area.
	 * - Heading_Jumper_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		//require_once( dirname( __FILE__ ) . '/class-heading-jumper-loader.php');
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-heading-jumper-loader.php';


		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		//require_once( dirname( __FILE__ ) . '/class-heading-jumper-i18n.php' );
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-heading-jumper-i18n.php';

		/**
		* Class responsible for outputting the content of the plugin in a widget
		*/

		//require_once( dirname( __DIR__, 1) . '/widgets/class-heading-jumper-widget.php' );
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/class-heading-jumper-widget.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		//require_once( dirname( __DIR__, 1) . '/admin/class-heading-jumper-admin.php');
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-heading-jumper-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		//require_once( dirname( __DIR__, 1) . '/public/class-heading-jumper-public.php' );
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-heading-jumper-public.php';

		/**
		 * Options class to interact with wp_options
		 */

		//require_once( dirname( __DIR__, 1) . '/admin/class-heading-jumper-options.php' );
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-heading-jumper-options.php';

		$this->loader = new Heading_Jumper_Loader();
		$this->options = new Heading_Jumper_Options( $this->$version, $this->$heading_jumper );

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Heading_Jumper_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Heading_Jumper_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Heading_Jumper_Admin( $this->get_heading_jumper(), $this->get_version() );

		if ( $this->options->get( 'display_location' ) == 'widget' ){
			$this->loader->add_action( 'widgets_init', $plugin_admin, 'load_heading_jumper_widget', 1);
		}
		
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_heading_jumper_settings' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Heading_Jumper_Public( 
			$this->get_heading_jumper(), 
			$this->get_version(), 
			$this->options->get_pages(),
			$this->options->get( 'title' )
		);
		
		if ( $this->options->get( 'display_location' ) == 'content' ){
			$this->loader->remove_filter( 'get_the_excerpt', 'wp_trim_excerpt' );
			$this->loader->add_filter( 'get_the_excerpt', $plugin_public, 'hj_wp_trim_excerpt' );
			$this->loader->add_filter( 'the_content', $plugin_public, 'print_table_of_contents' );
			$this->loader->add_filter( 'get_the_excerpt', $plugin_public, 'trim_nav_from_excerpt' );
		}

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_heading_jumper() {
		return $this->heading_jumper;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Heading_Jumper_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
}
