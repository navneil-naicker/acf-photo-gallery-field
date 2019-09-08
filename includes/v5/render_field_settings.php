<?php
    /*
    *  acf_render_field_setting
    *
    *  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
    *  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
    *
    *  More than one setting can be added by copy/paste the above code.
    *  Please note that you must also have a matching $defaults value for the field name (font_size)
    */

    $name = $field['name'];
    $value = $field['fields['.$name];

    acf_render_field_setting( $field, array(
        'label'			=> __('Edit modal','TEXTDOMAIN'),
        'type'          => 'select',
        'name'          => 'fields['.$name.'][edit_modal]',
        'value'         => $value['edit_modal'],
        'choices'       => array('Default' => 'Default', 'Native' => 'Native')
    ));

    acf_render_field_setting( $field, array(
        'label'			=> __('Images Limit','TEXTDOMAIN'),
        'type'          => 'number',
        'name'          => 'fields['.$name.'][images_limit]',
        'value'         => $value['images_limit']
    ));