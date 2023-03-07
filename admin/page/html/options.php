<table class="form-table">
    <tr>
        <th scope="row"><?php esc_html_e('XML-RPC', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('XML-RPC'); ?></span></legend>
                <label for="<?php echo esc_attr(RY::get_option_name('disable_xmlrpc')); ?>">
                    <input name="<?php echo esc_attr(RY::get_option_name('disable_xmlrpc')); ?>" type="checkbox" id="<?php echo esc_attr(RY::get_option_name('disable_xmlrpc')); ?>" value="1" <?php checked('1', RY::get_option('disable_xmlrpc')); ?> />
                    <?php esc_html_e('Disable XML-RPC', 'ry-toolkit'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
</table>

<h2><?php esc_html_e('Website frontend', 'ry-toolkit'); ?></h2>
<table class="form-table">
    <tr>
        <th scope="row"><?php esc_html_e('WordPress version', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('WordPress version'); ?></span></legend>
                <label for="<?php echo esc_attr(RY::get_option_name('hide_wp_version')); ?>">
                    <input name="<?php echo esc_attr(RY::get_option_name('hide_wp_version')); ?>" type="checkbox" id="<?php echo esc_attr(RY::get_option_name('hide_wp_version')); ?>" value="1" <?php checked('1', RY::get_option('hide_wp_version')); ?> />
                    <?php esc_html_e('Hide WordPress version in generator info', 'ry-toolkit'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Emoji', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Emoji'); ?></span></legend>
                <label for="<?php echo esc_attr(RY::get_option_name('disable_emoji')); ?>">
                    <input name="<?php echo esc_attr(RY::get_option_name('disable_emoji')); ?>" type="checkbox" id="<?php echo esc_attr(RY::get_option_name('disable_emoji')); ?>" value="1" <?php checked('1', RY::get_option('disable_emoji')); ?> />
                    <?php esc_html_e('Disable convert emoji to a static image come from WordPress', 'ry-toolkit'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Shortlink', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Shortlink'); ?></span></legend>
                <label for="<?php echo esc_attr(RY::get_option_name('disable_shortlink')); ?>">
                    <input name="<?php echo esc_attr(RY::get_option_name('disable_shortlink')); ?>" type="checkbox" id="<?php echo esc_attr(RY::get_option_name('disable_shortlink')); ?>" value="1" <?php checked('1', RY::get_option('disable_shortlink')); ?> />
                    <?php esc_html_e('Disable show shortlink link', 'ry-toolkit'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('oEmbed', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('oEmbed'); ?></span></legend>
                <label for="<?php echo esc_attr(RY::get_option_name('disable_oembed')); ?>">
                    <input name="<?php echo esc_attr(RY::get_option_name('disable_oembed')); ?>" type="checkbox" id="<?php echo esc_attr(RY::get_option_name('disable_oembed')); ?>" value="1" <?php checked('1', RY::get_option('disable_oembed')); ?> />
                    <?php esc_html_e('Disable show oEmbed link', 'ry-toolkit'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Feed', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Feed'); ?></span></legend>
                <label for="<?php echo esc_attr(RY::get_option_name('disable_feed_link')); ?>">
                    <input name="<?php echo esc_attr(RY::get_option_name('disable_feed_link')); ?>" type="checkbox" id="<?php echo esc_attr(RY::get_option_name('disable_feed_link')); ?>" value="1" <?php checked('1', RY::get_option('disable_feed_link')); ?> />
                    <?php esc_html_e('Disable show feed link', 'ry-toolkit'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('REST', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('REST'); ?></span></legend>
                <label for="<?php echo esc_attr(RY::get_option_name('disable_rest_link')); ?>">
                    <input name="<?php echo esc_attr(RY::get_option_name('disable_rest_link')); ?>" type="checkbox" id="<?php echo esc_attr(RY::get_option_name('disable_rest_link')); ?>" value="1" <?php checked('1', RY::get_option('disable_rest_link')); ?> />
                    <?php esc_html_e('Disable show REST link', 'ry-toolkit'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Windows Live Writer', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Windows Live Writer'); ?></span></legend>
                <label for="<?php echo esc_attr(RY::get_option_name('disable_wlw')); ?>">
                    <input name="<?php echo esc_attr(RY::get_option_name('disable_wlw')); ?>" type="checkbox" id="<?php echo esc_attr(RY::get_option_name('disable_wlw')); ?>" value="1" <?php checked('1', RY::get_option('disable_wlw')); ?> />
                    <?php esc_html_e('Disable show Windows Live Writer manifest file', 'ry-toolkit'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
</table>
