<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

    // vars
    $this->name = 'photo_gallery';
    $this->label = __('Photo Gallery','navz-photo-gallery');
    $this->category = __("Content",'navz-photo-gallery'); // Basic, Content, Choice, etc
    $this->defaults = array(
        // add default here to merge into your field.
        // This makes life easy when creating the field options as you don't need to use any if( isset('') ) logic. eg:
        'edit_modal' => 'Default',
        //'preview_size' => 'thumbnail'
    );


    // do not delete!
    parent::__construct();

    // settings
    $this->settings = $settings;
