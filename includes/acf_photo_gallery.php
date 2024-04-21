<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

//Helper function that makes the images into a resuable array
function acf_photo_gallery_make_images($attachment_ids, $field, $post_id = null, $order = 'ASC', $orderby = 'post__in'){
	global $wpdb;
	$attach_ids = explode(',', $attachment_ids ?? '');
	$args = array( 'post_type' => 'attachment', 'posts_per_page' => -1, 'post__in' => $attach_ids, 'order' => $order, 'orderby' => $orderby );
	$get_images = get_posts( $args );
	$images = array_filter($get_images);
	if( is_array($field) ){
		$fieldname = $field['name'];
	} else {
		$fieldname = $field;
	}
	$array = array();
	if( count($images) ):
		foreach($images as $image):
			$title = $image->post_title;
			$content = $image->post_content;
			$full_url = wp_get_attachment_url($image->ID);
			$thumbnail_url = wp_get_attachment_thumb_url($image->ID);
			$meta_data = wp_get_attachment_metadata($image->ID);
			foreach (array_keys($meta_data['sizes']) as $size) {
				$meta_data['sizes'][$size]['source_url'] = wp_get_attachment_image_url($image->ID, $size);
			}
			$alt_text = get_post_meta($image->ID, '_wp_attachment_image_alt', true);
			$large_srcset = wp_get_attachment_image_srcset( $image->ID,'large', $meta_data);
			$medium_srcset = wp_get_attachment_image_srcset( $image->ID,'medium', $meta_data);
			$url = get_post_meta($image->ID, $fieldname . '_url', true);
			$target = get_post_meta($image->ID, $fieldname . '_target', true);
			$array[] = array(
				'id' => $image->ID,
				'title' => $title,
				'caption' => $content,
				'full_image_url' => $full_url,
				'thumbnail_image_url' => $thumbnail_url,
				'large_srcset' => $large_srcset,
				'medium_srcset' => $medium_srcset,
				'media_details' => [
					'width' => $meta_data['width'],
					'height' => $meta_data['height'],
					'sizes' => $meta_data['sizes']
				],
				'alt_text' => $alt_text,
				'url' => $url,
				'target' => $target
			);
		endforeach;
	endif;
	return $array;
}

function acf_photo_gallery($field = null, $post_id = null, $order = 'ASC', $orderby = 'post__in'){
	$images = get_post_meta($post_id, $field, true);
	return acf_photo_gallery_make_images($images, $field, $post_id, $order, $orderby);
}

function apgf_update_donation(){
	if(!empty($_GET['nonce']) and wp_verify_nonce($_GET['nonce'], 'acf-photo-gallery-field\navz-photo-gallery-nonce') and !empty($_GET['option'])){
		$option = sanitize_text_field($_GET['option']);
		if(in_array($option, ['yes', 'no', 'already', 'later'])){
			global $wpdb;
			update_option("apgf_donation", serialize(
				array(
					"option" => $option,
					"timestamp" => date('Ymd', strtotime("+30 days"))
				)
			));
		}
	} else {
		$option = unserialize(get_option("apgf_donation"));
		if(is_array($option)){
			$currTime = date('Ymd');
			$time = $option['timestamp'];
			$result = [
				"show" => ($option["option"] === "later" && current_user_can('administrator') && $currTime > $time) ? true: false
			];
			die(json_encode($result));
		} else {
			die(json_encode([
				"show" => true
			]));
		}
	}
	die();
}
add_action('wp_ajax_apgf_update_donation', 'apgf_update_donation');