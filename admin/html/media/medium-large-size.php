<fieldset>
    <legend class="screen-reader-text"><span><?php esc_html_e('Medium large size', 'ry-toolkit'); ?></span></legend>
    <label for="medium_large_size_w"><?php esc_html_e('Max Width', 'ry-toolkit'); ?></label>
    <input name="medium_large_size_w" type="number" step="1" min="0" id="medium_large_size_w" value="<?php form_option('medium_large_size_w'); ?>" class="small-text" />
    <br />
    <label for="medium_large_size_h"><?php esc_html_e('Max Height', 'ry-toolkit'); ?></label>
    <input name="medium_large_size_h" type="number" step="1" min="0" id="medium_large_size_h" value="<?php form_option('medium_large_size_h'); ?>" class="small-text" />
</fieldset>
