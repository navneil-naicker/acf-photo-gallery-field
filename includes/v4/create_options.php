<?php

    // exit if accessed directly
    if( ! defined( 'ABSPATH' ) ) exit;

    $field = array_merge($this->defaults, $field);
    $key = esc_attr($field['name']);
?>
<tr class="field_option field_option_<?php echo esc_attr($this->name); ?>">
    <td class="label">
        <label><?php esc_html_e("Edit modal", 'navz-photo-gallery'); ?></label>
        <p class="description"><?php esc_html_e("Native lets you delete permanently or select another, but is heavier", 'navz-photo-gallery'); ?></p>
    </td>
    <td>
    <?php
        do_action('acf/create_field', array(
            'type' => 'select',
            'name' => 'fields['.$key.'][edit_modal]',
            'value' => $field['edit_modal'],
            'choices' => array('Default' => 'Default', 'Native' => 'Native')
        ));
    ?>
    </td>
</tr>