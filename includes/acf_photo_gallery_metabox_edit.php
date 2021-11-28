<div id="acf-photo-gallery-metabox-edit-<?php echo esc_attr($attachment); ?>" class="acf-edit-photo-gallery">
    <h3>Edit Image</h3>
    <input class="acf-photo-gallery-edit-field" type="hidden" name="acf-pg-hidden-field" value="<?php echo $field; ?>"/>
    <input class="acf-photo-gallery-edit-field" type="hidden" name="acf-pg-hidden-post" value="<?php echo $_GET['post']; ?>"/>
    <input class="acf-photo-gallery-edit-field" type="hidden" name="acf-pg-hidden-attachment" value="<?php echo $attachment; ?>"/>
    <input class="acf-photo-gallery-edit-field" type="hidden" name="acf-pg-hidden-action" value="acf_photo_gallery_edit_save"/>
    <input class="acf-photo-gallery-edit-field" type="hidden" name="acf-pg-hidden-nonce" value="<?php echo $nonce; ?>"/>
    <?php
        foreach( $fields as $key => $item ){
            $type = sanitize_text_field($item['type'])?$item['type']:null;
            $label = sanitize_text_field($item['label'])?$item['label']:null;
            $name = sanitize_text_field($item['name'])?$item['name']:null;
            $value = sanitize_text_field($item['value'])?$item['value']:null;
    ?>
        <?php if( in_array($type, array('text', 'date', 'color', 'datetime-local', 'email', 'number', 'tel', 'time', 'url', 'week', 'range')) ){ ?>
            <label><?php echo $label; ?></label>
            <input class="acf-photo-gallery-edit-field" type="<?php echo $type; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>"/>
        <?php } ?>
        <?php if( $type == 'checkbox' ){ ?>
            <label>
                <input class="acf-photo-gallery-edit-field" type="checkbox" name="<?php echo $name; ?>" value="true" <?php echo ($value=='true')?'checked':''; ?>/>
                <?php echo $label; ?>
            </label>
        <?php } ?>
        <?php if( $type == 'radio' ){ ?>
            <label>
                <input class="acf-photo-gallery-edit-field" type="radio" name="<?php echo $name; ?>" value="true" <?php echo ($value=='true')?'checked':''; ?>/>
                <?php echo $label; ?>
            </label>
        <?php } ?>
        <?php if( $type == 'textarea' ){ ?>
            <label><?php echo $label; ?></label>
            <textarea class="acf-photo-gallery-edit-field" name="<?php echo $name; ?>"><?php echo $value; ?></textarea>
        <?php } ?>
        <?php if( $type == 'select' ){ ?>
            <label><?php echo $label; ?></label>
            <select class="acf-photo-gallery-edit-field" name="<?php echo $name; ?>">
                <?php foreach($value[0] as $key => $item){ ?>
                    <option value="<?php echo $key; ?>" <?php echo $key==$value[1]?'selected':''; ?>><?php echo $item; ?></option>
                <?php } ?>
            </select>
        <?php } ?>
    <?php } ?>
    <div class="save-changes-wrap">
        <button class="button button-primary button-large" type="submit" data-fieldname="<?php echo $acf_fieldkey; ?>" data-id="<?php echo esc_attr($attachment); ?>" data-ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>">Save Changes</button>
        <button class="button button-large button-close" type="button" data-close="<?php echo esc_attr($attachment); ?>">Close</button>
    </div>
</div>