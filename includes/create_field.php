<?php
    // create Field HTML
    global $post;
    $nonce_acf_photo_gallery = wp_create_nonce( 'nonce_acf_photo_gallery' );
    $_name = get_post_meta($post->ID, str_replace('acf-field-', '', $field['id']), true );
?>
<div id="acf-photo-gallery-metabox">
    <input type="hidden" name="acf-photo-gallery-edit-modal" value="<?php echo empty($field['edit_modal']) ? 'Default' : $field['edit_modal']; ?>" />
    <input type="hidden" name="acf-photo-gallery-field" value="<?php echo $field['key']; ?>"/>
    <input type="hidden" name="acf-photo-gallery-field-id[]" value="<?php echo str_replace('acf-field-', '', $field['id']); ?>"/>
    <!---<input type="hidden" name="acf-photo-gallery-field-max" value="<?php //echo $field['max']; ?>"/>-->
    <div id="acf-photo-gallery-metabox-edit">
        <?php
            if( $_name ):
                $acf_photo_gallery_editbox_caption_from_attachment = apply_filters( 'acf_photo_gallery_editbox_caption_from_attachment', $field);
                $acf_photo_gallery_attachments =  $_name;
                $acf_photo_gallery_attachments = explode(',', $acf_photo_gallery_attachments);
                $args = array( 'post_type' => 'attachment', 'posts_per_page' => -1, 'post__in' => $acf_photo_gallery_attachments );
                $acf_photo_gallery_attachments = get_posts( $args );
                $nonce = wp_create_nonce('acf_photo_gallery_edit_save');
                foreach($acf_photo_gallery_attachments as $attachment):
                    $id = $attachment->ID;
                    $url = get_post_meta($id, $field['_name'] . '_url', true);
                    $target = get_post_meta($id, $field['_name'] . '_target', true);
                    $title = $attachment->post_title;
                    if( $acf_photo_gallery_editbox_caption_from_attachment == 1 ){
                        $caption = wp_get_attachment_caption( $id );
                    } else {
                        $caption = $attachment->post_content;
                    }
                    acf_photo_gallery_edit($field['_name'], $nonce, $id, $url, $title, $caption, $target);
                endforeach;
            endif;
        ?>
    </div>
    <ul id="acf-photo-gallery-metabox-list" class="acf-photo-gallery-metabox-list">
        <?php
            if( $_name ):
                $acf_photo_gallery_attachments =  $_name;
                $acf_photo_gallery_attachments = explode(',', $acf_photo_gallery_attachments);
                foreach($acf_photo_gallery_attachments as $image):
        ?>
        <li id="acf-photo-gallery-mediabox-<?php echo $image; ?>" data-id="<?php echo $image; ?>">
            <a class="dashicons dashicons-edit" href="#" title="Edit" data-id="<?php echo $image; ?>" data-field="<?php echo $field['_name']; ?>"></a>
            <a class="dashicons dashicons-dismiss" href="<?php echo admin_url('admin-ajax.php'); ?>?action=acf_photo_gallery_remove_photo&_wpnonce=<?php echo $nonce_acf_photo_gallery; ?>&post=<?php echo $post->ID; ?>&photo=<?php echo $image; ?>&field=<?php echo $field['key']; ?>&id=<?php echo $field['id']; ?>" data-id="<?php echo $image; ?>" data-field="<?php echo $field['_name']; ?>" title="Remove this photo from the gallery"></a>
            <input type="hidden" name="<?php echo $field['_name']; ?>[]" value="<?php echo $image; ?>"/>
            <img src="<?php echo wp_get_attachment_thumb_url( $image ); ?>"/>
        </li>
        <?php endforeach; else: ?><li class="acf-photo-gallery-media-box-placeholder"><span class="dashicons dashicons-format-image"></span></li><?php endif; ?>
    </ul>
    <button class="button button-primary button-large" id="acf-photo-gallery-metabox-add-images" type="button" data-id="<?php echo $field['_name']; ?>">Add Images</button>
</div>