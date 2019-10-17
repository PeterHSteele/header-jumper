<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://github.com/peterhsteele/heading-jumper
 * @since      1.0.0
 *
 * @package    Heading_Jumper
 * @subpackage Heading_Jumper/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name and version, enqueues styles and javascript, and defines a few functions
 * that collectively print the navigation.
 *
 * @package    Heading_Jumper
 * @subpackage Heading_Jumper/public
 * @author     Peter Steele steele.peter.3@gmail.com
 */
if ( ! class_exists('Heading_Jumper_Public') ){
class Heading_Jumper_Public {

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
	 * An array of pages on which to display the table of contents.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $pages    The whitelisted pages
	 */
	private $pages;

	/**
	* The title for the table of contents
	*
	* @since 1.0.0
	* @access private
	* @var 	string 		$title 		The user-supplied title
	*/

	private $title;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $heading_jumper       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	public function __construct( $heading_jumper, $version, $pages = null, $title = null ) {

		$this->heading_jumper = $heading_jumper;
		$this->version = $version;
		$this->pages = $pages;
		$this->title = $title;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		//check to make sure user has not disabled css
		$disabled = apply_filters( 'disable_heading_jumper_css', false );

		if ( $disabled ){ 
			return; 
		}

		wp_enqueue_style( $this->heading_jumper, plugin_dir_url( __FILE__ ) . 'css/heading-jumper-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->heading_jumper, plugin_dir_url( __FILE__ ) . 'js/heading-jumper-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	* Return either the largest of smallest tpye of <h*> in content.
	*
	* @since 1.0.0
	* 
	* @param str 	$text 		the page content to search
	* @param int 	$lvl 		the current level being searched for
	* @param bool   $largest  	if true, search for largest type of heading, else search for smallest 	
	*/

	
	public function find_heading_range( $text, $lvl, $largest ){
		preg_match('/<h'.$lvl.'["=\'\w\s]?>[\w\s.,:\'“”‘’]+<\/h'.$lvl.'>/', $text, $match );
		if ( $match ){	
			return (int)substr( $match[0], 2, 1 );
		} elseif ( $largest && $lvl <= 6  ){
			return $this->find_heading_range( $text, $lvl+1, $largest);
		} elseif ( ! $largest && $lvl >=2 ){
			return $this->find_heading_range( $text, $lvl-1, $largest);
		} else {
			return false;
		}
	}

	/**
	*	function to find the text of the headings in the article and return them as multi-level array,
	*	with the h2s in outermost layer, h3s one level down, etc.
	*
	*	e.g.,
	*
	*	Array(
	*
	*		[0] =>'first h2',
	*		[1] => Array( [0] => 'an h3', [1] => 'another h3' ),
	*		[2] =>'second h2'
	*	)
	*	@since 		1.0.0
	*	
	*	@param 		string 		$text 		html content to search
	*	@param 		int   		$lvl        type of heading to look for (ie, 2 = <h2>)
	*	@param 		int 		$smallest 	smallest type of heading in the $text
	*
	*/

	public function find_headings( $text , $lvl = null, $smallest = null ){
			// on first iteration, find largest and smallest <h*>
			if ( !$lvl ){
				$lvl = $this->find_heading_range( $text, 2, true );
				$smallest = $this->find_heading_range( $text, 6 , false );
			}
			//matches <h*...>...</h>
			$regex = '/(<h'.$lvl.'["=\'\w\s]?>)[\w\s.,:\'“”‘’]+<\/h'.$lvl.'>/';
			
			preg_match_all( $regex, $text, $matches, PREG_PATTERN_ORDER);
			//return if no headings in the text
			if (! $matches[0]){
				return false;
			}

			$headings = array();

			$firstHeadingIndex = strpos( $text , $matches[0][0] );
			//if first major heading isn't first element in content
			if ( $firstHeadingIndex > 0 ){
				//get text that comes before 1st major heading  
				$pre_first_top_level = substr( $text, 0, $firstHeadingIndex );
				//search for subheadings in that text
				$subheads = $this->find_headings( $pre_first_top_level, $lvl +1, $smallest);
				
				if ( $inner ){
					$headings[]=$subheads;
				}
			}
			//for headings of the current type
			foreach ( $matches[0] as $key => $headingWithTags ){
				
				$headingLengthWithTags = strlen( $headingWithTags );
				$openingTagLength = strlen( $matches[1][$key] );
				$headingLength = strlen($headingWithTags)-$openingTagLength-5;//'-5' because 5 of the characters are part of the closing </h*> tag.
				$heading = substr( $headingWithTags, $openingTagLength, $headingLength );
				//add the heading
				$headings[] = $heading;
				/*grab the chunk of text immediately after current heading and before next heading
				so we can search for subheadings. if the current level is the smallest,
				we already know there won't be any, so skip this step.*/
				if ( $lvl < $smallest ){
					
					$index_to_truncate = strpos( $text, $headingWithTags ) + $headingLengthWithTags;

					if ( $key < count( $matches[0] ) - 1 ){
						
						$next_index = strpos( $text, $matches[0][$key+1] ); 
						$new_text = substr( $text, $index_to_truncate, $next_index );
					
					} else{
						
						$new_text = substr( $text, $index_to_truncate );
					}
					//search for subheadings in between current level headings
					$next_level = $this->find_headings( $new_text, $lvl + 1, $smallest );
					
					if ( $next_level ){
						$headings[] = $next_level;
					}

				}
			}
			return $headings;	
	}

		/**
		*	Returns html for a button with arrow icon.
		*
		*	@since 1.0.0
		*	@return html for arrow button
		*/

		public function arrow_button(){
			$arrow = '<span class="dashicons dashicons-arrow-down-alt"></span>';
			return sprintf(
				'<button aria-pressed="false" class="header-jumper-arrow-button" role="button" type="button">%s</button>',
				$arrow
			);
		}

		/**
		*
		* build html for the nav with links to each heading on the page
		*
		* @since 1.0.0
		*
		* @param 	array	$headings 	a multi-level array composed of 
		*								a. the text of headings of a given type (ie, <h2> or <h3>) 
		*								b. arrays representing all subheadings between headings of the current type
		* @param	int 	$lvl        How many heading levels removed from the largest heading type  
		* @return 	string 				html string representing contents of ul.heading-jumper-toc  
		*/

		public function print_listitems_and_submenus( $headings, $lvl = 0 ){
			global $post;
			$html = '';
			$index = -1;
			foreach ( $headings as $heading ){
				$index+=1;
				//if this index of $headings is a link
				if ( is_string( $heading ) ){
					$button = '';
					//if next index in $headings is an array, there's a submenu
					if ( is_array( $headings[$index+1] ) ){
						//add arrow button to pop open the submenu
						$button .= $this->arrow_button();
					}
					//generate li for current menu item
					$html .= sprintf('<li class="clearfix"><a href="%s">%s</a>%s',
							esc_url( '#' . str_replace(' ','_',$heading) ),
							esc_html( $heading ),
							$button
						);
				} else {
					$sublist = '';	
					//If we have a subheading before an actual heading
					if ( $index==0 ){
						//As long as it's not the first li of the entire table of contents, use default text
						if ( $lvl > 0 ){
							$sublist .= '<li><span style="font-style:italic;">[Nested Headings]</span>' . $this->arrow_button();
						//if it's first li, use the title of the page
						}else{
							$sublist .= '<li>' . get_the_title() . $this->arrow_button();
						}
					}	
					//this index of $headings is a submenu, so add a new ul to house it
					$sublist .= '<ul aria-expanded="false" class="sublist heading-jumper-no-display">';
					//generate the links and submenus for this submenu
					$lis = $this->print_listitems_and_submenus( $heading, $lvl +1 );
					$sublist .= $lis;
					$sublist .= '</ul>';
					$html .= $sublist;
					//close the containing li
					$html .= '</li>';
				}
			}
			return $html;
		}

		/**
		*	print final html output of plugin: a nav with links to all headings on page.
		*
		*	This method can either filter the content or display the body of a widget - $widget is a
		*	boolean that keeps track of which one it's doing currently.
		*
		*	@since 1.0.0
		*
		*	@param string 	$content 	the content of the post to generate the TOC from.
		*/

		public function print_table_of_contents( $content = null ){
			global $post;
			$widget = false;
			$title = $this->title;
			
			if ( ! $content ) {
				$widget = true; //if content is null, the plugin is displaying a widget.
				$content = get_the_content();
			}
			
			$headings = $this->find_headings( $content );
			//return if no headings or page is not on whitelist
			if ( ! $headings || $this->pages && ! in_array(  $post->post_name, $this->pages, true ) ){
				if ( $widget ){
					return false;
				} else {
					return $content;
				}
			}

			$nav  = '<div id="hj-container" class="hj-container">';
			if ( $title ){ 
				$nav .= sprintf( '<h4>%s</h4>',  esc_html( $title ) );
			}
			$nav .= '<nav><ul class="heading-jumper-toc">';
			$nav .= $this->print_listitems_and_submenus( $headings ) ;
			$nav .= '</ul></nav></div>';
	
			//if we're filtering, return nav + content, else return just the nav
			if ( $widget ){
				return $nav;
			} else {
				return $nav . $content;
			}

		}

		/**
		* Generate an excerpt (if user doesn't supply one) in such a way that the text of the
		* heading-jumper table of contents can be removed from the beginning. Minimally changed from this SO post: 
		* https://stackoverflow.com/questions/24151161/how-to-prevent-wordpress-from-stripping-html-tags-in-excerpt
		*
		* @since 1.0.0
		* 
		* @param string 	$excerpt 	the user-supplied excerpt for the given post, if any
		*/

		function hj_wp_trim_excerpt( $excerpt ) {
		    global $post;
		    $raw_excerpt = $excerpt;
		    if ( '' == $excerpt ) {

		        $excerpt = get_the_content('');
		        $excerpt = strip_shortcodes( $excerpt );
		        $excerpt = apply_filters( 'the_content', $excerpt);
		        $excerpt = str_replace(']]>', ']]&gt;', $excerpt);
		        $excerpt = strip_tags( $excerpt, '<nav>' ); 
		        //Set the excerpt word count and only break after sentence is complete.
		        $excerpt_word_count = 55;
		        $excerpt_length = apply_filters('excerpt_length', $excerpt_word_count); 
		        $tokens = array();
		        $excerpt_output = '';
		        $count = 0;

		        // Divide the string into tokens; HTML tags, or words, followed by any whitespace
		        preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $excerpt, $tokens);

		        foreach ($tokens[0] as $token) { 

		            if ($count >= $excerpt_word_count && preg_match('/[\,\;\?\.\!]\s*$/uS', $token)) { 
		            // Limit reached, continue until , ; ? . or ! occur at the end
		                $excerpt_output .= trim($token);
		                break;
		            }

		            // Add words to complete sentence
		            $count++;

		            // Append what's left of the token
		            $excerpt_output .= trim( $token ) . ' ';
		        }

		        $excerpt = trim( force_balance_tags ($excerpt_output) );

		        $excerpt_end = ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Read More', 'heading-jumper' ). '</a>'; 
		        $excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end); 

		              
		        $excerpt .= $excerpt_end; /*Add read more in new paragraph */

		        return $excerpt;   
			}
		    
		    return apply_filters( 'hj_wp_trim_excerpt', $excerpt, $raw_excerpt);
		}

		/**
		* Prevents text of the table of contents from appearing in excerpt
		*
		* @since 1.0.0
		* 
		* @param string 	$excerpt 	the post excerpt
		*/

		public function trim_nav_from_excerpt( $excerpt ){
			$hj_nav = array();
			preg_match( '/<nav>[\w\d\s]+<\/nav>/', $excerpt, $hj_nav );
			return substr( $excerpt, strlen( $hj_nav[0] ) );
		}
}
}