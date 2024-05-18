<?php

/*
Plugin Name: ACF Photo Gallery Field
Plugin URI: http://www.navz.me/
Description: An extension for Advance Custom Fields which lets you add photo gallery functionality on your websites.
Version: 3.0
Author: Navneil Naicker
Author URI: http://www.navz.me/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_plugin_photo_gallery') ) :

	class acf_plugin_photo_gallery{
			
		// vars
		var $settings;

		//defined('current_elementor-pro_version', null);

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
			$this->settings = array(
				'version' => '3.0',
				'url' => plugin_dir_url( __FILE__ ),
				'path' => plugin_dir_path( __FILE__ ),
				'elementor_pro_vesion' => $this->get_elementor_pro_version(),
				'nonce_name' => 'acf-photo-gallery-field\navz-photo-gallery-nonce'
			);
			load_plugin_textdomain('acf-photo_gallery', false, plugin_basename( dirname( __FILE__ ) ) . '/lang'); 
			add_action('admin_enqueue_scripts', array($this, 'acf_photo_gallery_sortable'));			
			add_action('acf/include_field_types', array($this, 'include_field_types')); // v5
			add_action('acf/register_fields', array($this, 'include_field_types')); // v4
			add_action('rest_api_init', array($this, 'rest_api_init'));
			add_filter('acf_photo_gallery_caption_from_attachment', '__return_false');
			if($this->settings['elementor_pro_vesion'] > 3.15){
				add_action('elementor/dynamic_tags/register', array($this, 'register_tags'));
			} else {
				add_action('elementor/dynamic_tags/register_tags', array($this, 'register_tags'));
			}
			add_filter('plugin_row_meta', array($this, 'acf_pgf_donation_link'), 10, 4);
			add_action('admin_head', array($this, 'apgf_admin_head'));
		}

		function acf_pgf_donation_link( $links_array, $plugin_file_name, $plugin_data, $status ) {
			if ( strpos( $plugin_file_name, basename(__FILE__) ) ) {
				$links_array[] = '<a href="https://www.buymeacoffee.com/navzme" target="_blank"><span class="dashicons dashicons-heart" style="color:red;"></span> Donate</a>';
			}
		
			return $links_array;
		}

		function register_tags( $dynamic_tags ){
			if (class_exists('ElementorPro\Modules\DynamicTags\Tags\Base\Data_Tag')) {
				\Elementor\Plugin::$instance->dynamic_tags->register_group( 'acf-photo-gallery', [
					'title' => 'ACF' 
				]);
				include(__DIR__ . '/includes/elementor_register_tag.php');
				if($this->settings['elementor_pro_vesion'] > 3.15){
					$dynamic_tags->register(new register_tag());
				} else {
					$dynamic_tags->register_tag('register_tag');
				}
			}
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

		function rest_prepare_post( $data, $post, $request ){
			$images = array();
			$field_groups = acf_get_field_groups(array('post_id' => $post->ID));
			foreach ( $field_groups as $group ){
				$fields = get_posts(array(
					'posts_per_page' => -1,
					'post_type' => 'acf-field',
					'orderby' => 'menu_order',
					'order' => 'ASC',
					'suppress_filters' => true,
					'post_parent' => $group['ID'],
					'post_status' => 'publish',
					'update_post_meta_cache' => false
				));
				foreach ( $fields as $field ) {
					$object = get_field_object($field->post_name);
					if( $object['type'] == 'photo_gallery' ){
						$images[] = acf_photo_gallery($object['name'], $post->ID);
						$data->data['acf']['photo_gallery'][$object['name']] = $images;
					}
				}
			}
			return $data;
		}

		function rest_api_init() {
			foreach (get_post_types() as $name) {
				add_filter("rest_prepare_$name", array($this, 'rest_prepare_post'), 10, 3);
			}
		}

		function get_elementor_pro_version(){
			$elementor_pro_vesion = 0;
			$file = dirname(dirname(__FILE__)) . '/elementor-pro/elementor-pro.php';
			if(file_exists($file)){
				$plugin_data = get_file_data($file, array('Version' => 'Version'), false);
				if(!empty($plugin_data['Version'])){
					$elementor_pro_vesion = floatval($plugin_data['Version']);
				}
			}
			return $elementor_pro_vesion;
		}

		function apgf_admin_head()
		{
			if(current_user_can('administrator')){
?>
		<script>
			let apgf_show_donation = true;
			jQuery.get("<?php echo admin_url('admin-ajax.php'); ?>?action=apgf_update_donation", function( data ) {
				data = JSON.parse(data);
				if(data){
					apgf_show_donation = data.show;
				}
			});
		</script>
<?php
			}
?>
		<script>
			const apgf_nonce = "<?php echo wp_create_nonce($this->settings['nonce_name']) ?>";
		</script>
<?php
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
