<fieldset>
    <legend class="screen-reader-text"><span><?php esc_html_e('Disable generated size', 'ry-toolkit'); ?></span></legend>
    <?php foreach ($all_sizes as $size_name => $size_data) { ?>
    <?php $show_size_name = isset($size_names[$size_name]) ? $size_names[$size_name] . ' ( ' . $size_name . ' )' : $size_name; ?>
    <label for="ry_disable_subsize_<?php echo esc_attr($size_name); ?>">
        <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_subsize')); ?>[]" type="checkbox" id="ry_disable_subsize_<?php echo esc_attr($size_name); ?>" value="<?php echo esc_attr($size_name); ?>" <?php checked(in_array($size_name, $disable_subsize)); ?>>
        <?php echo esc_html($show_size_name); ?> ( <?php echo esc_html($size_data['width']); ?> * <?php echo esc_html($size_data['height']); ?> )
    </label>
    <br>
    <?php } ?>
</fieldset>
