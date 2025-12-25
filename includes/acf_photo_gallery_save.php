<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function acf_photo_gallery_save( $post_id ) {

    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }

    $nonce = sanitize_text_field( wp_unslash( $_POST['acf_photo_gallery_nonce'] ?? '' ) );

    if ( ! $nonce || ! wp_verify_nonce( $nonce, 'acf-photo-gallery-field\navz-photo-gallery-nonce' ) ) {
        return;
    }

    remove_action( 'save_post', 'acf_photo_gallery_save' );

	$raw_groups = sanitize_text_field( wp_unslash( $_POST['acf-photo-gallery-groups'] ?? array() ) );
    $groups_unslashed = is_array( $raw_groups ) ? array_map( 'wp_unslash', $raw_groups ) : array();
    $groups = array_map( 'sanitize_text_field', $groups_unslashed );

    $field_key = sanitize_text_field( wp_unslash( $raw_field_key ) );

    if ( ! empty( $groups ) ) {
        foreach ( $groups as $k => $group_value ) {

            $field_id = sanitize_text_field( $group_value );
            if ( ! $field_id ) {
                continue;
            }

            $raw_ids = sanitize_text_field( wp_unslash($_POST[ $field_id ] ?? array()) );
            $ids_unslashed = is_array( $raw_ids ) ? array_map( 'wp_unslash', $raw_ids ) : array();
            $ids = array_map( 'sanitize_text_field', $ids_unslashed );

            if ( ! empty( $ids ) ) {
                $ids_string = implode( ',', $ids );
                update_post_meta( $post_id, $field_id, $ids_string );
                acf_update_metadata( $post_id, $field_id, $field_key, true );
            } else {
                delete_post_meta( $post_id, $group_value );
                acf_delete_metadata( $post_id, $field_id, true );
            }
        }
    }

    add_action( 'save_post', 'acf_photo_gallery_save' );
}
add_action( 'save_post', 'acf_photo_gallery_save' );
