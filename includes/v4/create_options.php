<?php
    $field = array_merge($this->defaults, $field);
    $key = $field['name'];
?>
<tr class="field_option field_option_<?php echo $this->name; ?>">
    <td class="label">
        <label><?php _e("Edit modal",'acf'); ?></label>
        <p class="description"><?php _e("Native lets you delete permanently or select another, but is heavier",'acf'); ?></p>
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