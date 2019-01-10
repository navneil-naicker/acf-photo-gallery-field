<?php
    // create Field HTML
    global $post;    
    $nonce_acf_photo_gallery = wp_create_nonce( 'nonce_acf_photo_gallery' );
    if( ACF_VERSION >= 4 and ACF_VERSION < 5 ){
        $filename = $field['_name'];
        $value = $field['value'];
        $key = $field['key'];
        $edit_model = (!empty($field['edit_modal']))?$field['edit_modal']:'Default';
    } else if( ACF_VERSION >= 5 and ACF_VERSION < 6 ){
        $filename = $field['_name'];
        $value = $field['value'];
        $key = $field['key'];
        $edit_model = (!empty($field['fields[' . $filename]['edit_modal']))?$field['fields[' . $filename]['edit_modal']:'Default';    
    }
?>
<div class="acf-photo-gallery-group-<?php echo $key; ?>">
    <input type="hidden" name="acf-photo-gallery-edit-modal" value="<?php echo $edit_model; ?>" />
    <input type="hidden" name="acf-photo-gallery-groups[]" value="<?php echo $field['_name']; ?>"/>
    <div id="acf-photo-gallery-metabox-edit">
        <?php
            if( $value ):
                $acf_photo_gallery_editbox_caption_from_attachment = apply_filters( 'acf_photo_gallery_editbox_caption_from_attachment', $field);
                $acf_photo_gallery_attachments =  $value;
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
            if( $value ):
                $acf_photo_gallery_attachments =  $value;
                $acf_photo_gallery_attachments = explode(',', $acf_photo_gallery_attachments);
                foreach($acf_photo_gallery_attachments as $image):
        ?>
        <li class="acf-photo-gallery-mediabox-<?php echo $image; ?>">
            <a class="dashicons dashicons-edit" href="#" title="Edit" data-id="<?php echo $image; ?>" data-field="<?php echo $key; ?>"></a>
            <a class="dashicons dashicons-dismiss" href="#" data-id="<?php echo $image; ?>" data-field="<?php echo $key; ?>" title="Remove this photo from the gallery"></a>
            <input type="hidden" name="<?php echo $field['_name']; ?>[]" value="<?php echo $image; ?>"/>
            <img src="<?php echo wp_get_attachment_thumb_url( $image ); ?>"/>
        </li>
        <?php endforeach; else: ?><li class="acf-photo-gallery-media-box-placeholder"><span class="dashicons dashicons-format-image"></span></li><?php endif; ?>
    </ul>
    <button class="button button-primary button-large acf-photo-gallery-metabox-add-images" type="button" data-field="<?php echo htmlspecialchars(json_encode($field), ENT_QUOTES, 'UTF-8'); ?>">Add Images</button>
</div>