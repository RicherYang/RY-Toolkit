<table id="ry-toolkit-options-core" class="form-table ry-toolkit-options">
    <tr>
        <th scope="row"><?php esc_html_e('XML-RPC', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('XML-RPC', 'ry-toolkit'); ?></span></legend>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_xmlrpc', __('Disable XML-RPC', 'ry-toolkit')); ?>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Comment', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Comment', 'ry-toolkit'); ?></span></legend>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_comment', __('Disable comment', 'ry-toolkit')); ?>
                <p class="description"><?php esc_html_e('If you set disabled it will override the post settings.', 'ry-toolkit'); ?></p>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Trackback', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Trackback', 'ry-toolkit'); ?></span></legend>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_ping', __('Disable trackbacks and pingbacks', 'ry-toolkit')); ?>
                <p class="description"><?php esc_html_e('If you set disabled it will override the post settings.', 'ry-toolkit'); ?></p>
            </fieldset>
        </td>
    </tr>
</table>
