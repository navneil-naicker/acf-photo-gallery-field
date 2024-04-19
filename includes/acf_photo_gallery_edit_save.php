<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

//Fires off when ediitn the details of the photo
function acf_photo_gallery_edit_save(){
	if( wp_verify_nonce( $_POST['nonce'], 'acf-photo-gallery-field\navz-photo-gallery-nonce') and !empty($_POST['attachment_id']) ){
		$acf_field_key = sanitize_text_field($_POST['acf_field_key']);
		$acf_field_name = sanitize_text_field($_POST['acf_field_name']);
		$post_id = preg_replace('/\D/', '', $_POST['post_id']);
		$attachment_id = preg_replace('/\D/', '', $_POST['attachment_id']);
		$title = sanitize_text_field($_POST['title']);
		$caption = sanitize_textarea_field($_POST['caption']);

		$caption_from_attachment = apply_filters('acf_photo_gallery_editbox_caption_from_attachment', $_POST);
		if( $caption_from_attachment == 1 ){
			$captionColumn = 'post_excerpt';
		} else {
			$captionColumn = 'post_content';
		}

		wp_update_post(
			array(
				'ID' => $attachment_id,
				'post_title' => $title,
				$captionColumn => $caption
			)
		);

		$unset_fields = ['acf_field_key', 'acf_field_name', 'post_id', 'attachment_id', 'action', 'nonce', 'title', 'caption'];
		
		foreach($unset_fields as $field){
			unset( $_POST[$field] );
		}

		foreach( $_POST as $name => $value ){
			$name = sanitize_text_field( $name );
			$value = sanitize_text_field( $value );
			if( !empty($value) ){
				update_post_meta( $attachment_id, $acf_field_name . '_' . $name, $value);
			} else {
				delete_post_meta( $attachment_id, $acf_field_name . '_' . $name);
			}
		}
	}
	die();
}
add_action( 'wp_ajax_acf_photo_gallery_edit_save', 'acf_photo_gallery_edit_save' );
