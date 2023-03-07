<fieldset>
    <legend class="screen-reader-text"><span><?php esc_html_e('Max size', 'ry-toolkit'); ?></span></legend>
    <label for="ry_big_size_"><?php esc_html_e('Max Width / Heigth', 'ry-toolkit'); ?></label>
    <input name="<?php echo esc_attr(RY::get_option_name('big_image_size')); ?>" type="number" step="1" min="0" id="ry_big_size_" value="<?php echo esc_attr(RY::get_option('big_image_size', 2560)); ?>" class="small-text" />
</fieldset>
