<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

function apgf_edit_model(){
	$apgf = new acf_plugin_photo_gallery();
	if(!empty($_GET['post_id']) and !empty($_GET['attachment_id']) and $_GET['nonce'] and !empty($apgf->settings['nonce_name']) and wp_verify_nonce( $_GET['nonce'], $apgf->settings['nonce_name'])){
		$post_id = preg_replace('/\D/', '', $_GET['post_id']);
		$attachment_id = preg_replace('/\D/', '', $_GET['attachment_id']);
		$acf_key = sanitize_text_field($_GET['acf_key']);
		$post = get_post($attachment_id);
		if($post->post_type != "attachment"){
			die(status_header(400, "The post type is not an attachment."));
		}
		$args = array();
		$args['url'] = $post->guid;
		$args['title'] = $post->post_title;
		$args['caption'] = $post->post_excerpt;
		$args['target'] = 'true';
		$fields = apply_filters('acf_photo_gallery_image_fields', $args, $attachment_id, $acf_key);
?>
<div class="acf_pgf_modal">
	<div class="acf_pgf_modal-content">
		<div class="acf-edit-photo-gallery">
			<div class="acf_pgf_modal-header"><h2>Edit Image</h2></div>
			<div class="acf_pgf_modal-body">
				<form method="post">
					<input type="hidden" name="attachment_id" value="<?php echo $attachment_id; ?>"/>
					<?php
						foreach( $fields as $key => $item ){
							$type = esc_attr($item['type']) ? $item['type'] : null;
							$label = esc_attr($item['label']) ? $item['label'] : null;
							$name = esc_attr($item['name']) ? $item['name'] : null;
							$value = esc_attr($item['value']) ? $item['value'] : null;
					?>
						<?php if( in_array($type, array('text', 'date', 'color', 'datetime-local', 'email', 'number', 'tel', 'time', 'url', 'week', 'range')) ){ ?>
							<label><?php echo esc_attr($label); ?></label>
							<input class="acf-photo-gallery-edit-field" type="<?php echo esc_attr($type); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>"/>
						<?php } ?>
						<?php if( $type == 'checkbox' ){ ?>
							<label>
								<input class="acf-photo-gallery-edit-field" type="checkbox" name="<?php echo esc_attr($name); ?>" value="true" <?php echo ($value=='true')?'checked':''; ?>/>
								<?php echo esc_attr($label); ?>
							</label>
						<?php } ?>
						<?php if( $type == 'radio' ){ ?>
							<label>
								<input class="acf-photo-gallery-edit-field" type="radio" name="<?php echo esc_attr($name); ?>" value="true" <?php echo ($value=='true')?'checked':''; ?>/>
								<?php echo esc_attr($label); ?>
							</label>
						<?php } ?>
						<?php if( $type == 'textarea' ){ ?>
							<label><?php echo esc_attr($label); ?></label>
							<textarea class="acf-photo-gallery-edit-field" name="<?php echo esc_attr($name); ?>" rows="3"><?php echo @esc_textarea($value); ?></textarea>
						<?php } ?>
						<?php if( $type == 'select' ){ ?>
							<label><?php echo esc_attr($label); ?></label>
							<select class="acf-photo-gallery-edit-field" name="<?php echo esc_attr($name); ?>">
								<?php foreach($value[0] as $key => $item){ ?>
									<option value="<?php echo esc_attr($key); ?>" <?php echo esc_attr($key==$value[1]?'selected':''); ?>><?php echo esc_attr($item); ?></option>
								<?php } ?>
							</select>
						<?php } ?>
					<?php } ?>
					<div class="acf_pgf_modal-footer">
						<button class="button button-primary button-large" type="submit">Save</button>
						<button class="button button-large cancel" type="button">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
	}
	die();
}
add_action('wp_ajax_apgf_edit_model', 'apgf_edit_model');