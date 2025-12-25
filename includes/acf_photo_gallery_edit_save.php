<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// Fires off when editing the details of the photo
function acf_photo_gallery_edit_save() {

    $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
    $attachment_id = isset($_POST['attachment_id']) ? absint(wp_unslash($_POST['attachment_id'])) : 0;
    $acf_field_key = isset($_POST['acf_field_key']) ? sanitize_text_field(wp_unslash($_POST['acf_field_key'])) : '';
    $post_id = isset($_POST['post_id']) ? absint(wp_unslash($_POST['post_id'])) : 0;
    $title = isset($_POST['title']) ? sanitize_text_field(wp_unslash($_POST['title'])) : '';
    $caption = isset($_POST['caption']) ? sanitize_textarea_field(wp_unslash($_POST['caption'])) : '';

    if ( ! $nonce || ! wp_verify_nonce( $nonce, 'acf-photo-gallery-field\navz-photo-gallery-nonce' ) ) {
        wp_send_json_error( 'Invalid nonce', 403 );
    }

    if ( ! $attachment_id ) {
        wp_send_json_error( 'Missing attachment ID', 400 );
    }

    if ( ! current_user_can( 'edit_post', $attachment_id ) ) {
        wp_send_json_error( 'Insufficient permissions', 403 );
    }

    $acf_field = acf_get_field( $acf_field_key );
    if ( ! $acf_field ) {
        wp_send_json_error( 'ACF Field not found', 403 );
    }

    $acf_field_name = $acf_field['name'] ?? '';

    $captionColumn = 'post_content';
    $caption_from_attachment = apply_filters('acf_photo_gallery_editbox_caption_from_attachment', $_POST);
    if ( $caption_from_attachment == 1 ) {
        $captionColumn = 'post_excerpt';
    }

    wp_update_post([
        'ID' => $attachment_id,
        'post_title' => $title,
        $captionColumn => $caption,
    ]);

    $unset_fields = ['acf_field_key', 'acf_field_name', 'post_id', 'attachment_id', 'action', 'nonce', 'title', 'caption'];
    foreach ( $unset_fields as $field ) {
        unset( $_POST[$field] );
    }

    foreach ( $_POST as $name => $value ) {
        $name  = sanitize_text_field(wp_unslash($name));
        $value = sanitize_text_field(wp_unslash($value));
        if ( ! empty($value) ) {
            update_post_meta( $attachment_id, $acf_field_name . '_' . $name, $value );
        } else {
            delete_post_meta( $attachment_id, $acf_field_name . '_' . $name );
        }
    }

    wp_send_json_success();
}
add_action( 'wp_ajax_acf_photo_gallery_edit_save', 'acf_photo_gallery_edit_save' );