<table id="ry-toolkit-options-frontend" class="form-table">
    <tr>
        <th scope="row"><?php esc_html_e('WordPress version', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('WordPress version', 'ry-toolkit'); ?></span></legend>
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('hide_wp_version')); ?>">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('hide_wp_version')); ?>" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('hide_wp_version')); ?>" value="1" <?php checked('1', RY_Toolkit::get_option('hide_wp_version')); ?> />
                    <?php esc_html_e('Hide WordPress version in generator info', 'ry-toolkit'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Emoji', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Emoji', 'ry-toolkit'); ?></span></legend>
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_emoji')); ?>">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_emoji')); ?>" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_emoji')); ?>" value="1" <?php checked('1', RY_Toolkit::get_option('disable_emoji')); ?> />
                    <?php esc_html_e('Disable convert emoji to a static image come from WordPress', 'ry-toolkit'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Shortlink', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Shortlink', 'ry-toolkit'); ?></span></legend>
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_shortlink')); ?>">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_shortlink')); ?>" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_shortlink')); ?>" value="1" <?php checked('1', RY_Toolkit::get_option('disable_shortlink')); ?> />
                    <?php esc_html_e('Disable show shortlink link', 'ry-toolkit'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('oEmbed', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('oEmbed', 'ry-toolkit'); ?></span></legend>
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_oembed')); ?>">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_oembed')); ?>" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_oembed')); ?>" value="1" <?php checked('1', RY_Toolkit::get_option('disable_oembed')); ?> />
                    <?php esc_html_e('Disable show oEmbed link', 'ry-toolkit'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Feed', 'ry-toolkit'); ?></th>
        <td id="disable-feed-link">
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Feed', 'ry-toolkit'); ?></span></legend>
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-all">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>[all]" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-all" value="1" <?php checked('1', RY_Toolkit::get_option('disable_feed_link')['all'] ?? 0); ?> />
                    <?php esc_html_e('Disable show ALL feed link', 'ry-toolkit'); ?>
                </label><br />
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-posts">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>[posts]" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-posts" value="1" <?php checked('1', RY_Toolkit::get_option('disable_feed_link')['posts'] ?? 0); ?> />
                    <?php esc_html_e('Disable show posts feed link', 'ry-toolkit'); ?>
                </label><br />
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-comments">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>[comments]" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-comments" value="1" <?php checked('1', RY_Toolkit::get_option('disable_feed_link')['comments'] ?? 0); ?> />
                    <?php esc_html_e('Disable show comments feed link', 'ry-toolkit'); ?>
                </label><br />
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-postcomments">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>[postcomments]" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-postcomments" value="1" <?php checked('1', RY_Toolkit::get_option('disable_feed_link')['postcomments'] ?? 0); ?> />
                    <?php esc_html_e('Disable show post comments feed link', 'ry-toolkit'); ?>
                </label>
                <br />
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-archive">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>[archive]" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-archive" value="1" <?php checked('1', RY_Toolkit::get_option('disable_feed_link')['archive'] ?? 0); ?> />
                    <?php esc_html_e('Disable show post type archive feed link', 'ry-toolkit'); ?>
                </label><br />
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-category">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>[category]" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-category" value="1" <?php checked('1', RY_Toolkit::get_option('disable_feed_link')['category'] ?? 0); ?> />
                    <?php esc_html_e('Disable show category feed link', 'ry-toolkit'); ?>
                </label><br />
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-tag">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>[tag]" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-tag" value="1" <?php checked('1', RY_Toolkit::get_option('disable_feed_link')['tag'] ?? 0); ?> />
                    <?php esc_html_e('Disable show tag feed link', 'ry-toolkit'); ?>
                </label><br />
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-tax">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>[tax]" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-tax" value="1" <?php checked('1', RY_Toolkit::get_option('disable_feed_link')['tax'] ?? 0); ?> />
                    <?php esc_html_e('Disable show taxonomy feed link', 'ry-toolkit'); ?>
                </label><br />
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-author">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>[author]" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_feed_link')); ?>-author" value="1" <?php checked('1', RY_Toolkit::get_option('disable_feed_link')['author'] ?? 0); ?> />
                    <?php esc_html_e('Disable show author feed link', 'ry-toolkit'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('REST', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('REST', 'ry-toolkit'); ?></span></legend>
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_rest_link')); ?>">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_rest_link')); ?>" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_rest_link')); ?>" value="1" <?php checked('1', RY_Toolkit::get_option('disable_rest_link')); ?> />
                    <?php esc_html_e('Disable show REST link', 'ry-toolkit'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Windows Live Writer', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Windows Live Writer', 'ry-toolkit'); ?></span></legend>
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_wlw')); ?>">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_wlw')); ?>" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('disable_wlw')); ?>" value="1" <?php checked('1', RY_Toolkit::get_option('disable_wlw')); ?> />
                    <?php esc_html_e('Disable show Windows Live Writer manifest file', 'ry-toolkit'); ?>
                </label>
            </fieldset>
        </td>
    </tr>
</table>
