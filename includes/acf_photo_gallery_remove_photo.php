<?php

//Helper function that will remove photo from the gallery
function acf_photo_gallery_remove_photo(){
	if( wp_verify_nonce( $_GET['_wpnonce'], 'nonce_acf_photo_gallery') and !empty($_GET['post']) and !empty($_GET['photo']) ){
		$field = $_GET['field'];
		$post = $_GET['post'];
		$photo = $_GET['photo'];
		$photo = preg_replace('/\D/', '', $photo);
		$id = str_replace('acf-field-', '', $_GET['id']);
		$meta = get_post_meta($post, $id, true);
		$meta_arr = explode(',', $meta);
		if( in_array($photo, $meta_arr) ){
			foreach($meta_arr as $key => $value){
				if( $photo == $value ){
					unset($meta_arr[$key]);
					if( count($meta_arr) > 0 ){
						$meta_arr = implode(',', $meta_arr);
						update_post_meta( $post, $id, $meta_arr );
					} else {
						delete_post_meta( $post, $id );
					}
				}
			}
		}
	}
	die();
}
//add_action( 'wp_ajax_nopriv_acf_photo_gallery_remove_photo', 'acf_photo_gallery_remove_photo' );
add_action( 'wp_ajax_acf_photo_gallery_remove_photo', 'acf_photo_gallery_remove_photo' );
