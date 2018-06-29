<?php
/*
Plugin Name: ACF Photo Gallery Field
Plugin URI: http://www.navz.me/
Description: An extension for Advance Custom Fields which lets you add photo gallery functionality on your websites.
Version: 1.6.3
Author: Navneil Naicker
Author URI: http://www.navz.me/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_plugin_photo_gallery') ) :

	class acf_plugin_photo_gallery {
			
		// vars
		var $settings;

		/*
		*  __construct
		*
		*  This function will setup the class functionality
		*
		*  @type	function
		*  @date	17/02/2016
		*  @since	1.0.0
		*
		*  @param	n/a
		*  @return	n/a
		*/
		
		function __construct() {
			
			// vars
			$this->settings = array(
				'version'	=> '1.6.3',
				'url'		=> plugin_dir_url( __FILE__ ),
				'path'		=> plugin_dir_path( __FILE__ )
			);
			
			// set text domain
			// https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
			load_plugin_textdomain( 'acf-photo_gallery', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' ); 
			
			add_action( 'admin_enqueue_scripts', array($this, 'acf_photo_gallery_sortable') );			
			
			// include field
			add_action('acf/include_field_types', 	array($this, 'include_field_types')); // v5
			add_action('acf/register_fields', 		array($this, 'include_field_types')); // v4

			add_filter( 'acf_photo_gallery_caption_from_attachment', '__return_false' );
		
			// Enable the option show in rest
			add_filter( 'acf/rest_api/field_settings/show_in_rest', '__return_true' );

			// Enable the option edit in rest
			add_filter( 'acf/rest_api/field_settings/edit_in_rest', '__return_true' );

			//Get ACF Photo Gallery images as JSON object
			add_filter( 'acf/rest_api/page/get_fields', array($this, 'acf_to_rest_api'), 10, 3 );

		}
		
		//Add in jquery-ui-sortable script
		function acf_photo_gallery_sortable($hook) {
			if ( 'post.php' == $hook ) { wp_enqueue_script( 'jquery-ui-sortable', 'jquery-ui-sortable', 'jquery', '9999', true); }
		}

		/*
		*  include_field_types
		*
		*  This function will include the field type class
		*
		*  @type	function
		*  @date	17/02/2016
		*  @since	1.0.0
		*
		*  @param	$version (int) major ACF version. Defaults to false
		*  @return	n/a
		*/
		
		function include_field_types( $version = false ) {
			
			// support empty $version
			if( !$version ) $version = 4;
			
			// include
			include_once('fields/acf-photo_gallery-v' . $version . '.php');
			
		}

		//Callback function that will get ACF Photo Gallery images as JSON object
		function acf_to_rest_api( $data, $request ) {
			$attributes = $request->get_params();
			if( !empty($attributes['type']) and  $attributes['type'] == 'photo_gallery' ){
				$post_id = $attributes['id'];
				$field = $attributes['field'];
				$type = $attributes['type'];
				$order = (!empty($attributes['order']))?$attributes['order']:null;
				$orderby = (!empty($attributes['orderby']))?$attributes['orderby']:null;
				$data = acf_photo_gallery_make_images($data[$field], $field, $post_id, $order, $orderby);
			}
			return $data;
		}
		
	}

	// initialize
	new acf_plugin_photo_gallery();

// class_exists check
endif;

//Helper function for pulling the images
require_once( dirname(__FILE__) . '/includes/acf_photo_gallery.php' );

//Resizes the image
require_once( dirname(__FILE__) . '/includes/acf_photo_gallery_resize_image.php' );

//Set the default fields for the edit gallery
require_once( dirname(__FILE__) . '/includes/acf_photo_gallery_image_fields.php' );

//Metabox for the photo edit
require_once( dirname(__FILE__) . '/includes/acf_photo_gallery_edit.php' );
