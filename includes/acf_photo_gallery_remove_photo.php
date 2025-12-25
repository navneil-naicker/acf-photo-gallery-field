<?php

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Helper function that removes a photo from the gallery
function acf_photo_gallery_remove_photo() {
	
	$nonce = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';
	$field = isset($_GET['field']) ? sanitize_text_field(wp_unslash($_GET['field'])) : '';
	$post = isset($_GET['post']) ? absint(wp_unslash($_GET['post'])) : 0;
	$photo = isset($_GET['photo']) ? absint(wp_unslash($_GET['photo'])) : 0;
	$id = isset($_GET['id']) ? str_replace('acf-field-', '', sanitize_text_field(wp_unslash($_GET['id']))) : '';

    if ( wp_verify_nonce( $nonce, 'nonce_acf_photo_gallery' ) && $post && $photo && $id ) {

        $meta = get_post_meta( $post, $id, true );
        $meta_arr = explode( ',', $meta );

        if ( in_array( $photo, $meta_arr, true ) ) {
            foreach ( $meta_arr as $key => $value ) {
                if ( $photo == $value ) {
                    unset( $meta_arr[ $key ] );
                    if ( count( $meta_arr ) > 0 ) {
                        update_post_meta( $post, $id, implode( ',', $meta_arr ) );
                    } else {
                        delete_post_meta( $post, $id );
                    }
                }
            }
        }
    }

    wp_send_json_success();
}
add_action( 'wp_ajax_acf_photo_gallery_remove_photo', 'acf_photo_gallery_remove_photo' );