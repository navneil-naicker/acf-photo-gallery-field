<?php
/*
Plugin Name: ACF Photo Gallery Field
Plugin URI: http://www.navz.me/
Description: An extension for Advance Custom Fields which lets you add photo gallery functionality on your websites.
Version: 1.6.1
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
			'version'	=> '1.6.1',
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
	
	}
	
	function acf_photo_gallery_sortable($hook) {
		if ( 'post.php' == $hook ) {
			wp_enqueue_script( 'jquery-ui-sortable', 'jquery-ui-sortable', 'jquery', '9999', true);
		}
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
	
}

// initialize
new acf_plugin_photo_gallery();

// class_exists check
endif;

//Helper function for pulling the images
function acf_photo_gallery($field = null, $post_id){
	$images = get_post_meta($post_id, $field, true);
	$images = explode(',', $images);
	$args = array( 'post_type' => 'attachment', 'posts_per_page' => -1, 'post__in' => $images, 'orderby' => 'post__in' ); 
	$images = get_posts( $args );
	$images = array_filter($images);
	$array = array();
	if( count($images) ):
		foreach($images as $image):
			$title = $image->post_title;
			$content = $image->post_content;
			$full_url = wp_get_attachment_url($image->ID);
			$thumbnail_url = wp_get_attachment_thumb_url($image->ID);
			$meta_data = wp_get_attachment_metadata($image->ID);
			$large_srcset = wp_get_attachment_image_srcset( $image->ID,'large', $meta_data);
			$medium_srcset = wp_get_attachment_image_srcset( $image->ID,'medium', $meta_data);
			$url = get_post_meta($image->ID, $field . '_url', true);
			$target = get_post_meta($image->ID, $field . '_target', true);
			$array[] = array(
				'id' => $image->ID,
				'title' => $title,
				'caption' => $content,
				'full_image_url' => $full_url,
				'thumbnail_image_url' => $thumbnail_url,
				'large_srcset' => $large_srcset,
				'medium_srcset' => $medium_srcset,
				'url' => $url,
				'target' => $target
			);
		endforeach;
	endif;
	return $array;
}

//Resizes the image
function acf_photo_gallery_resize_image( $img_url, $width = 150, $height = 150){
	if( !function_exists('aq_resize') ){
		require_once( dirname(__FILE__) . '/aq_resizer.php');
	}
	$extension = explode('.', $img_url);
	$extension = strtolower(end($extension));
	if( $extension != 'svg' ){
		$img_url = aq_resize( $img_url, $width, $height, true, true, true);
	}
	return $img_url;
}

function acf_photo_gallery_image_fields( $args, $attachment_id, $field){
	return array(
		'url' => array('type' => 'text', 'label' => 'URL', 'name' => 'url', 'value' => ($args['url'])?$args['url']:null),
		'target' => array('type' => 'checkbox', 'label' => 'Open in new tab', 'name' => 'target', 'value' => ($args['target'])?$args['target']:null),
		'title' => array('type' => 'text', 'label' => 'Title', 'name' => 'title', 'value' => ($args['title'])?$args['title']:null),
		'caption' => array('type' => 'textarea', 'label' => 'Caption', 'name' => 'caption', 'value' => ($args['caption'])?$args['caption']:null)
	);
}
add_filter( 'acf_photo_gallery_image_fields', 'acf_photo_gallery_image_fields', 10, 3 );


















	
?>