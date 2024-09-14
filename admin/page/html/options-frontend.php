<table id="ry-toolkit-options-frontend" class="form-table ry-toolkit-options">
    <tr>
        <th scope="row"><?php esc_html_e('WordPress version', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('WordPress version', 'ry-toolkit'); ?></span></legend>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('hide_wp_version', __('Hide WordPress version in generator info', 'ry-toolkit')); ?>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Emoji', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Emoji', 'ry-toolkit'); ?></span></legend>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_emoji', __('Disable convert emoji to a static image come from WordPress', 'ry-toolkit')); ?>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Shortlink', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Shortlink', 'ry-toolkit'); ?></span></legend>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_shortlink', __('Disable show shortlink link', 'ry-toolkit')); ?>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('oEmbed', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('oEmbed', 'ry-toolkit'); ?></span></legend>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_oembed', __('Disable show oEmbed link', 'ry-toolkit')); ?>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Feed', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Feed', 'ry-toolkit'); ?></span></legend>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_feed_link', __('Disable show "ALL" feed link', 'ry-toolkit'), 'all'); ?>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_feed_link', __('Disable show "posts" feed link', 'ry-toolkit'), 'posts'); ?>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_feed_link', __('Disable show "comments" feed link', 'ry-toolkit'), 'comments'); ?>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_feed_link', __('Disable show "post comments" feed link', 'ry-toolkit'), 'postcomments'); ?>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_feed_link', __('Disable show "post type archive" feed link', 'ry-toolkit'), 'archive'); ?>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_feed_link', __('Disable show "category" feed link', 'ry-toolkit'), 'category'); ?>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_feed_link', __('Disable show "tag" feed link', 'ry-toolkit'), 'tag'); ?>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_feed_link', __('Disable show "taxonomy" feed link', 'ry-toolkit'), 'tax'); ?>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_feed_link', __('Disable show "author" feed link', 'ry-toolkit'), 'author'); ?>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('REST', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('REST', 'ry-toolkit'); ?></span></legend>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_rest_link', __('Disable show REST link', 'ry-toolkit')); ?>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Windows Live Writer', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Windows Live Writer', 'ry-toolkit'); ?></span></legend>
                <?php RY_Toolkit_Admin::the_bool_option_checkbox('disable_wlw', __('Disable show Windows Live Writer manifest file', 'ry-toolkit')); ?>
            </fieldset>
        </td>
    </tr>
</table>
