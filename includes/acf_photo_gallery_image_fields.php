<?php
function acf_photo_gallery_image_fields( $args, $attachment_id, $field){
	return array(
		'url' => array('type' => 'text', 'label' => 'URL', 'name' => 'url', 'value' => ($args['url'])?$args['url']:null),
		'target' => array('type' => 'checkbox', 'label' => 'Open in new tab', 'name' => 'target', 'value' => ($args['target'])?$args['target']:null),
		'title' => array('type' => 'text', 'label' => 'Title', 'name' => 'title', 'value' => ($args['title'])?$args['title']:null),
		'caption' => array('type' => 'textarea', 'label' => 'Caption', 'name' => 'caption', 'value' => ($args['caption'])?$args['caption']:null)
	);
}
add_filter( 'acf_photo_gallery_image_fields', 'acf_photo_gallery_image_fields', 10, 3 );
