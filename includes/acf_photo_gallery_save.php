<?php

//Fires off when the WordPress update button is clicked
function acf_photo_gallery_save( $post_id ){
	
	// If this is a revision, get real post ID
	if ( $parent_id = wp_is_post_revision( $post_id ) )
	$post_id = $parent_id;
	// unhook this function so it doesn't loop infinitely
	remove_action( 'save_post', 'acf_photo_gallery_save' );

	$field = isset($_POST['acf-photo-gallery-groups'])? $_POST['acf-photo-gallery-groups']: null;
	if( !empty($field) ){
		foreach($field as $k => $v ){
			$field_id = isset($_POST['acf-photo-gallery-groups'][$k])? $_POST['acf-photo-gallery-groups'][$k]: null;
            if (!empty($field_id)) {
                $ids = !empty($field) && isset($_POST[$field_id])? $_POST[$field_id]: null;
                if (!empty($ids)) {
                    $ids = implode(',', $ids);
                    update_post_meta($post_id, $field_id, $ids);
                } else {
                    delete_post_meta($post_id, $v);
                }
            }
		}
	}

	// re-hook this function
	add_action( 'save_post', 'acf_photo_gallery_save' );
}
add_action( 'save_post', 'acf_photo_gallery_save' );
