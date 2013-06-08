<?php
/*
Plugin Name: Tapestry2
Plugin URI: knightlab.dhrumilmehta.com
Description: Tapestry Skeleton
Version: 0.2
Author: Dhrumil Mehta
Author URI: http://www.dhrumilmehta.com
License: GPL2
*/
/*
Copyright 2013  Dhrumil Mehta  (email : dhrumil.mehta@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



if(!class_exists('Tapestry2'))
{
	class Tapestry2
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
        	// Initialize Settings
            require_once(sprintf("%s/settings.php", dirname(__FILE__)));
            $Tapestry2_Settings = new Tapestry2_Settings();

            // Initialize Widget
			require_once dirname( __FILE__ ) .'/tapestry-widget.php';

        	// Register custom post types
            require_once(sprintf("%s/post-types/living_story_post.php", dirname(__FILE__)));
            $Living_Story_Post = new Living_Story_Post();
		} // END public function __construct

		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate()
		{
			// Do nothing
		} // END public static function deactivate
	} // END class Tapestry2
} // END if(!class_exists('Tapestry2'))

if(class_exists('Tapestry2'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('Tapestry2', 'activate'));
	register_deactivation_hook(__FILE__, array('Tapestry2', 'deactivate'));

	// instantiate the plugin class
	$tapestry2 = new Tapestry2();

    // Add a link to the settings page onto the plugin page
    if(isset($tapestry2))
    {
		/* ------ Fire our meta box setup function on the post editor screen. */
			add_action( 'load-post.php', 'smashing_post_meta_boxes_setup' );
			add_action( 'load-post-new.php', 'smashing_post_meta_boxes_setup' );

			// register Foo_Widget widget
			add_action( 'widgets_init', create_function( '', 'register_widget( "tapestry_Widget" );' ) );

			// register jquery and style on initialization
			add_action('init', 'register_script');
			function register_script(){
			    wp_register_style( 'new_style', plugins_url('/jquery/css/smoothness/jquery-ui-1.10.2.custom.css', __FILE__), false, '1.9.1', 'all');
			    wp_register_style('timeliner_colorbox_css', plugins_url('/timeliner/inc/colorbox.css',__FILE__));
			    wp_register_style('timeliner_screen_css', plugins_url('/timeliner/css/screen.css',__FILE__));
			}

			// use the registered jquery and style above
			add_action('init', 'enqueue_style');
			function enqueue_style(){
				wp_enqueue_script('jquery');
				wp_enqueue_script('jquery-ui-core');
				wp_enqueue_script('jquery-ui-sortable');
			}

			/* Meta box setup function. */
			function smashing_post_meta_boxes_setup() {

				/* Add meta boxes on the 'add_meta_boxes' hook. */
				add_action( 'add_meta_boxes', 'smashing_add_post_meta_boxes' );

				/* Save post meta on the 'save_post' hook. */
				add_action( 'save_post', 'smashing_save_post_class_meta', 10, 2 );

			}

			/* Create one or more meta boxes to be displayed on the post editor screen. */
			function smashing_add_post_meta_boxes() {

				add_meta_box(
					'tapestry_in_stream_info',			// Unique ID
					esc_html__( 'Tapestry Menu', 'example' ),		// Title
					'smashing_post_class_meta_box',		// Callback function
					'post',					// Admin page (or post type)
					'normal',					// Context
					'default'					// Priority
				);

			}

			/* Display the post meta box. */
			function smashing_post_class_meta_box( $object, $box ) {
				wp_nonce_field( basename( __FILE__ ), 'smashing_post_class_nonce' );
				wp_enqueue_script('stream', plugins_url('/tapestry2/stream.js'), array('jquery','jquery-ui-core'));

				echo "<p>";
				echo "<label for ='stream_dropdownbox'>" . _e( "Select the Stream for this Post", 'default' ) . "</label>";
				echo "<br />";
				echo "<select id='stream_dropdownbox' name='stream_dropdownbox'>";

				//Get my stream name
				$my_streams = wp_get_post_terms(get_the_ID(), 'stream', array("fields" => "names"));
				$my_stream_name = $my_streams[0];

				$post_type = 'post';
				$taxonomy = 'stream';

			    $streams = get_terms( $taxonomy, array( 'hide_empty' => 0 ) );

		      	foreach( $streams as $stream ) :
			      	if($stream->name == $my_stream_name)
			      		$dropdownbox = "<option value = \"" . $stream->name ."\" selected> " . $stream->name . " </option>";
			      	else
			      		$dropdownbox = "<option value = \"" . $stream->name ."\" > " . $stream->name . " </option>";
			      	echo $dropdownbox;
		      	endforeach;
			    

				echo "</select>";
				echo "<br>";


				wp_enqueue_style('timeliner_colorbox_css');
				wp_enqueue_style('timeliner_screen_css');
   			    wp_enqueue_script( 'timeliner_js',  plugins_url('/timeliner/js/timeliner.js',__FILE__), array('jquery'));

		        echo "<ul id='streamdisplayer'></ul>";

			}
			/*------*/

			/* Save the meta box's post metadata. */
			function smashing_save_post_class_meta( $post_id, $post ) {

				/*Set the Stream for this post to be in*/
				wp_set_post_terms( $post_id, $terms, 'stream', $append = 'false' );

				/* Verify the nonce before proceeding. */
				if ( !isset( $_POST['smashing_post_class_nonce'] ) || !wp_verify_nonce( $_POST['smashing_post_class_nonce'], basename( __FILE__ ) ) )
					return $post_id;

				/* Get the post type object. */
				$post_type = get_post_type_object( $post->post_type );

				/* Check if the current user has permission to edit the post. */
				if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
					return $post_id;

				/* Get the posted data and sanitize it for use as an HTML class. */
				// caution, not santized
				$new_meta_value1 = ( isset( $_POST['tapestry_summary'] ) ? $_POST['tapestry_summary'] : '' );
				$new_meta_value2 = ( isset( $_POST['tapestry_headline'] ) ? $_POST['tapestry_headline'] : '' );
				$new_meta_value3 = ( isset( $_POST['tapestry_altdate'] ) ? $_POST['tapestry_altdate'] : '' );
				$new_meta_value4 = ( isset( $_POST['priority'] ) ? $_POST['priority'] : '' );

				/*save the stream from the dropdown box selection*/
				$new_stream = ( isset( $_POST['stream_dropdownbox'] ) ? sanitize_text_field( $_POST['stream_dropdownbox'] ) : '' );
				wp_set_post_terms(get_the_ID(), $new_stream, 'stream');


				/* Get the meta key. */
				$meta_key1 = 'tapestry_summary';
				$meta_key2 = 'tapestry_headline';
				$meta_key3 = 'tapestry_altdate';
				$meta_key4 = 'tapestry_priority';


				/* Get the meta value of the custom field key. */
				$meta_value1 = get_post_meta( $post_id, $meta_key, true );
				$meta_value2 = get_post_meta( $post_id, $meta_key, true );
				$meta_value3 = get_post_meta( $post_id, $meta_key, true );
				$meta_value4 = get_post_meta( $post_id, $meta_key, true );

				/*tapestry_summary*/
				/* If a new meta value was added and there was no previous value, add it. */
				if ( $new_meta_value1 && '' == $meta_value1 )
					add_post_meta( $post_id, $meta_key1, $new_meta_value1, true );
				/* If the new meta value does not match the old value, update it. */
				elseif ( $new_meta_value1 && $new_meta_value1 != $meta_value1 )
					update_post_meta( $post_id, $meta_key1, $new_meta_value1 );
				/* If there is no new meta value but an old value exists, delete it. */
				elseif ( '' == $new_meta_value1 && $meta_value1 )
					delete_post_meta( $post_id, $meta_key1, $meta_value1 );

				/* tapestry_headline */
				if ( $new_meta_value2 && '' == $meta_value2 )
					add_post_meta( $post_id, $meta_key2, $new_meta_value2, true );
				elseif ( $new_meta_value2 && $new_meta_value2 != $meta_value2 )
					update_post_meta( $post_id, $meta_key2, $new_meta_value2 );
				elseif ( '' == $new_meta_value2 && $meta_value2 )
					delete_post_meta( $post_id, $meta_key2, $meta_value2 );

				/*tapestry_altdate*/
				if ( $new_meta_value3 && '' == $meta_value3 )
					add_post_meta( $post_id, $meta_key3, $new_meta_value3, true );
				elseif ( $new_meta_value3 && $new_meta_value3 != $meta_value3 )
					update_post_meta( $post_id, $meta_key3, $new_meta_value3 );
				elseif ( '' == $new_meta_value3 && $meta_value3 )
					delete_post_meta( $post_id, $meta_key3, $meta_value3 );

				/*tapestry_priority*/
				if ( $new_meta_value && '' == $meta_value )
					add_post_meta( $post_id, $meta_key4, $new_meta_value4, true );
				elseif ( $new_meta_value4 && $new_meta_value4 != $meta_value4 )
					update_post_meta( $post_id, $meta_key4, $new_meta_value4 );
				elseif ( '' == $new_meta_value4 && $meta_value4 )
					delete_post_meta( $post_id, $meta_key4, $meta_value4 );

			}

/*==================================================================================================================================*/


	        // Add the settings link to the plugins page
	        function plugin_settings_link($links)
	        {
	            $settings_link = '<a href="options-general.php?page=tapestry2">Settings</a>';
	            array_unshift($links, $settings_link);
	            return $links;
	        }

	        $plugin = plugin_basename(__FILE__);
	        add_filter("plugin_action_links_$plugin", 'plugin_settings_link');



	        /*STREAMS CUSTOM TAXONOMY ===========================================================================*/

			//hook into the init action and call create_book_taxonomies when it fires
			add_action( 'init', 'create_stream_taxonomies', 0 );

			//create two taxonomies, genres and writers for the post type "book"
			function create_stream_taxonomies()
			{

			  // Add new taxonomy, NOT hierarchical (like tags)
			  $labels = array(
			    'name'                         => _x( 'Streams', 'taxonomy general name' ),
			    'singular_name'                => _x( 'Stream', 'taxonomy singular name' ),
			    'search_items'                 => __( 'Search Streams' ),
			    'popular_items'                => __( 'Popular Streams' ),
			    'all_items'                    => __( 'All Streams' ),
			    'parent_item'                  => null,
			    'parent_item_colon'            => null,
			    'edit_item'                    => __( 'Edit Stream' ),
			    'update_item'                  => __( 'Update Stream' ),
			    'add_new_item'                 => __( 'Add New Stream' ),
			    'new_item_name'                => __( 'New Stream Name' ),
			    'separate_items_with_commas'   => __( 'Separate streams with commas' ),
			    'add_or_remove_items'          => __( 'Add or remove streams' ),
			    'choose_from_most_used'        => __( 'Choose from the most used streams' ),
			    'not_found'                    => __( 'No streams found.' ),
			    'menu_name'                    => __( 'Streams' )
			  );

			  $args = array(
			    'hierarchical'            => false,
			    'labels'                  => $labels,
			    'show_ui'                 => true,
			    'show_admin_column'       => true,
			    'update_count_callback'   => '_update_post_term_count',
			    'query_var'               => true,
			    'rewrite'                 => array( 'slug' => 'writer' )
			  );

			  register_taxonomy( 'stream', 'post', $args );
			}

	}
}

?>