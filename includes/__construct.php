<?php

    // vars
    $this->name = 'photo_gallery';
    $this->label = __('Photo Gallery');
    $this->category = __("Content",'acf'); // Basic, Content, Choice, etc
    $this->defaults = array(
        // add default here to merge into your field.
        // This makes life easy when creating the field options as you don't need to use any if( isset('') ) logic. eg:
        'edit_modal' => 'Default',
        //'preview_size' => 'thumbnail'
    );


    // do not delete!
    parent::__construct();

    // Enable the option show in rest
    add_filter( 'acf/rest_api/field_settings/show_in_rest', '__return_true' );

    // Enable the option edit in rest
    add_filter( 'acf/rest_api/field_settings/edit_in_rest', '__return_true' );

    //Get ACF Photo Gallery images as JSON object
    add_filter( 'acf/rest_api/page/get_fields', function( $data, $request ) {
        $attributes = $request->get_params();
        if( !empty($attributes['type']) and  $attributes['type'] == 'photo_gallery' ){
            $post_id = $attributes['id'];
            $field = $attributes['field'];
            $type = $attributes['type'];
            $order = (!empty($attributes['order']))?$attributes['order']:null;
            $orderby = (!empty($attributes['orderby']))?$attributes['orderby']:null;
            $data = acf_photo_gallery_make_images($data[$field], $field, $post_id, $order, $orderby);
        }
        return $data;
    }, 10, 3 );

    // settings
    $this->settings = $settings;
