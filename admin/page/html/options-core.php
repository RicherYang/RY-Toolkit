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
    <tr>
        <th scope="row"><?php esc_html_e('Post list show thumbnail', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Post list show thumbnail', 'ry-toolkit'); ?></span></legend>
                <?php
        $post_types = array_filter(get_post_types([], 'names'), function ($post_type) {
            return post_type_supports($post_type, 'thumbnail');
        });
        foreach ($post_types as $post_type) {
            RY_Toolkit_Admin::the_bool_option_checkbox('show_thumbnails', get_post_type_labels(get_post_type_object($post_type))->name, $post_type);
        } ?>
            </select>
        </td>
    </tr>
</table>
