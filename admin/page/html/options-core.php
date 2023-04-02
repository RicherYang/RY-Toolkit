<table class="form-table">
    <tr>
        <th scope="row"><?php esc_html_e('XML-RPC', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('XML-RPC', 'ry-toolkit'); ?></span></legend>
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_xmlrpc')); ?>">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_xmlrpc')); ?>" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_xmlrpc')); ?>" value="1" <?php checked('1', RY_Toolkit::get_option('disable_xmlrpc')); ?> />
                    <?php esc_html_e('Disable XML-RPC', 'ry-toolkit'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Comment', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Comment', 'ry-toolkit'); ?></span></legend>
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_comment')); ?>">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_comment')); ?>" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_comment')); ?>" value="1" <?php checked('1', RY_Toolkit::get_option('disable_comment')); ?> />
                    <?php esc_html_e('Disable comment', 'ry-toolkit'); ?>
                </label>
                <p class="description"><?php esc_html_e('If you set disabled it will override the post settings.', 'ry-toolkit'); ?></p>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Trackback', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Trackback', 'ry-toolkit'); ?></span></legend>
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_ping')); ?>">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_ping')); ?>" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_ping')); ?>" value="1" <?php checked('1', RY_Toolkit::get_option('disable_ping')); ?> />
                    <?php esc_html_e('Disable trackbacks and pingbacks', 'ry-toolkit'); ?>
                </label>
                <p class="description"><?php esc_html_e('If you set disabled it will override the post settings.', 'ry-toolkit'); ?></p>
            </fieldset>
        </td>
    </tr>
</table>
