<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       github.com/peterhsteele/heading-jumper
 * @since      1.0.0
 *
 * @package    Heading_Jumper
 * @subpackage Heading_Jumper/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name and version, registers the widget, and creates the settings fields.
 *
 * @package    Heading_Jumper
 * @subpackage Heading_Jumper/admin
 * @author     Your Name <email@example.com>
 */
if ( ! class_exists( 'Heading_Jumper_Admin' ) ){
class Heading_Jumper_Admin {

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
	 * Store an instance of Heading_Jumper_Options
	 *
	 * @since    1.0.0
	 * @var  $string 	$heading_jumper_options  	the instance
	 */

	private $heading_jumper_options;

	/**
	 * Initialize the class, load dependencies, and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $heading_jumper       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	public function __construct( $heading_jumper, $version ) {
		$this->heading_jumper = $heading_jumper;
		$this->version = $version;
		$this->load_dependencies();
	}

	/**
	*	Loads dependencies for this class
	*
	*	@since 1.0.0
	*/

	public function load_dependencies(){
		/*
		* instance of options class allows us to register and read settings
		*/
		
		//require_once( dirname( __DIR__ ) . '/admin/class-heading-jumper-options.php' );
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-heading-jumper-options.php';
		
		$this->heading_jumper_options = new Heading_Jumper_Options( $this->$heading_jumper, $this->version );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->heading_jumper, plugin_dir_url( __FILE__ ) . 'css/heading-jumper-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->heading_jumper, plugin_dir_url( __FILE__ ) . 'js/heading-jumper-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Registers custom widget
	 *
	 * @since    1.0.0
	 */

	public function load_heading_jumper_widget(){
		
		register_widget( 'heading_jumper_widget' );
	
	}

	/**
	 * Outputs html for a radio group input in the heading jumper settings section
	 *
	 * @since    1.0.0
	 *
	 * @param    array 		$args 		attributes for the radio <input>s
	 */

	public function radio_group( $args ){
		[
			'setting' => $setting,
			'name' => $name,
			'description' => $description,
			'values' => $values,
		] = $args;

		$html = '';
		$value = isset( $setting ) ? $setting : '' ;

		$html .= '<p>'.$description.'</p>';

		foreach ( $values as $val ){
	
			$html .= '<label>';
			$html .= sprintf( 
				'<input type="radio" value="%s" name="heading_jumper_settings[%s]" id="heading_jumper_%s_%s" %s >',
				$val,
				$name,
				$name,
				$val,
				checked( $val, $value, false)
			);
			$html .= '<span>' . $val . ' </span></label>';	
		}

		echo $html;
	}

	/**
	 * Outputs html for a text input in the heading jumper settings section
	 *
	 * @since    1.0.0
	 *
	 * @param    array 		$args 		attributes for the text <input>
	 */

	public function text_input( $args ) {
		$setting = $args['setting'];
		$name=$args['name'];
		$description = $args['description'];

		$html = '';
		$value = isset( $setting ) ? $setting : '' ;

		$html .= $description ? '<p>' . $description . '</p>' : ''; 
		$html .= sprintf(
			'<input type="text" style="width:100%%" id="heading_jumper_%s" value="%s" name="heading_jumper_settings[%s]">',
			$name,
			$value,
			$name
			);
		echo $html;
	}

	/**
	*	Returns data for Heading Jumper settings section and fields
	*
	*	@since 	1.0.0
	* 	@return data which will be used to populate the heading jumper settings section
	*/

	public function get_settings_attrs(){
		$sections = array(
			array(
				'id' 		=> 'settings-section',
				'title' 	=> __( 'Heading Jumper Settings', 'heading-jumper'),
				'callback'  => '__return_true',
				'page' 		=> 'reading'
			)
		);

		$fields = array(
			array(
				'id' 		=> 'display-location',
				'title' 	=> __( 'Display Location', 'heading-jumper'),
				'callback' 	=> array( $this, 'radio_group' ),
				'page' 		=> 'reading',
				'section' 	=> 'settings-section',
				'args' 		=> array(
					'name' 		  => 'display_location',
					'description' => __( 'Where should the table of contents be displayed?', 'heading-jumper'),
					'values' 	  => array( 
						__( 'widget', 'heading-jumper' ),
						__( 'content', 'heading-jumper' )  
					)
				)
			),
			array(
				'id' 		=> 'title',
				'title' 	=> __( 'Title', 'heading-jumper' ),
				'callback'  => array( $this, 'text_input' ),
				'page' 		=> 'reading',
				'section'   => 'settings-section',
				'args' 		=> array(
					'name'  	  => 'title',
					'description' => __( 'A title for the table of contents. Only applies if display location 
					is set to "content". Otherwise, the title can be set in Dashboard > Widgets.', 'heading-jumper' )
				)
			),
			array(
				'id' 	  	=> 'whitelist-pages',
				'title' 	=> __( 'Included pages', 'heading-jumper' ),
				'callback'  => array( $this, 'text_input' ),
				'page' 		=> 'reading',
				'section'   => 'settings-section',
				'args' 		=> array(
					'name' 		  => 'pages',
					'description' => __( 'Comma-separated list of page slugs on which to include heading jumper. 
					If none are listed, the plugin will appear on all single pages or posts by default.', 'heading-jumper' )
				)
			),
		);

		return array( 'sections' => $sections, 'fields' => $fields);

	}

	/**
	*	Registers Heading Jumper settings section and fields in reading settings
	*
	*	@since 	1.0.0
	*/

	public function register_heading_jumper_settings(){

		//register the settings
		$this->heading_jumper_options->register(
			'reading',
			'heading_jumper_settings',
			array( $this, 'sanitize_heading_jumper_settings')
		);

		//get current values of settings
		$current_settings = array();
		foreach ( [ 'display_location', 'title', 'pages' ] as $setting ){
			$current_settings[] = $this->heading_jumper_options->get( $setting );
		}

		$settings_attrs = $this->get_settings_attrs(); 
		//add the sections
		foreach ( $settings_attrs['sections'] as $section){

			add_settings_section(
				$this->heading_jumper . '-' . $section['id'],
				$section['title'],
				$section['callback'],
				$section['page']
			);

		}

		//add the fields, passing in current value of each setting
		foreach ( $settings_attrs['fields'] as $key => $field ){
			$field['args']['setting'] = $current_settings[$key];

			add_settings_field(
				$this->heading_jumper . '-' . $field['id'],
				$field['title'],
				$field['callback'],
				$field['page'],
				$this->heading_jumper . '-' . $field['section'],
				$field['args']  
			);

		}
	}

	/**
	*	Sanitize values of settings fields before saving to the database
	*
	*	@since 	1.0.0
	*	@param 	array 	$input 		the settings array
	*/

	public function sanitize_heading_jumper_settings( $input ){
		$input['pages'] = sanitize_text_field( $input['pages'] );
		$input['display_location'] = $input['display_location'] == 'content' ? 'content' : 'widget';
		$input['title'] = sanitize_text_field( $input['title'] );
		return $input;
	}
}
}
