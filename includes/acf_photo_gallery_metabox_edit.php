<div id="acf-photo-gallery-metabox-edit-<?php echo $attachment; ?>" class="acf-edit-photo-gallery">
    <h3>Edit Image</h3>
    <input class="acf-photo-gallery-edit-field" type="hidden" name="acf-pg-hidden-field" value="<?php echo $field; ?>"/>
    <input class="acf-photo-gallery-edit-field" type="hidden" name="acf-pg-hidden-post" value="<?php echo $_GET['post']; ?>"/>
    <input class="acf-photo-gallery-edit-field" type="hidden" name="acf-pg-hidden-attachment" value="<?php echo $attachment; ?>"/>
    <input class="acf-photo-gallery-edit-field" type="hidden" name="acf-pg-hidden-action" value="acf_photo_gallery_edit_save"/>
    <input class="acf-photo-gallery-edit-field" type="hidden" name="acf-pg-hidden-nonce" value="<?php echo $nonce; ?>"/>
    <?php
        foreach( $fields as $key => $item ){
            $type = ($item['type'])?$item['type']:null;
            $label = $item['label']?$item['label']:null;
            $name = $item['name']?$item['name']:null;
            $value = $item['value']?$item['value']:null;
    ?>
        <?php if( $type == 'text' ){ ?><label><?php echo $label; ?></label><input class="acf-photo-gallery-edit-field" type="<?php echo $type; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>"/><?php } ?>
        <?php if( $type == 'checkbox' ){ ?><label><input class="acf-photo-gallery-edit-field" type="checkbox" name="<?php echo $name; ?>" value="true" <?php echo (!empty($value=='true'))?'checked':''; ?>/><?php echo $label; ?></label><?php } ?>
        <?php if( $type == 'textarea' ){ ?><label><?php echo $label; ?></label><textarea class="acf-photo-gallery-edit-field" name="<?php echo $name; ?>"><?php echo $value; ?></textarea><?php } ?>
    <?php } ?>
    <div class="save-changes-wrap">
        <button class="button button-primary button-large" type="submit" data-id="<?php echo $attachment; ?>" data-ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>">Save Changes</button>
        <button class="button button-large button-close" type="button" data-close="<?php echo $attachment; ?>">Close</button>
    </div>
</div>