<?php

$providers = wp_get_sitemap_providers();

$post_types = get_post_types(['public' => true], 'objects');
unset($post_types['attachment']);
$post_types = array_filter($post_types, 'is_post_type_viewable');

$taxonomies = get_taxonomies(['public' => true], 'objects');
$taxonomies = array_filter($taxonomies, 'is_taxonomy_viewable');

?>

<p><a href="<?php echo esc_url(get_sitemap_url('index')); ?>"><?php esc_html_e('View XML sitemap index.', 'ry-toolkit'); ?></a></p>

<table id="ry-toolkit-options-sitemap" class="form-table">
    <tr>
        <th scope="row"><label for="<?php echo esc_attr(RY_Toolkit::get_option_name('sitemap_urls_pre_file')); ?>"><?php esc_html_e('Maximum number of URLs for a sitemap', 'ry-toolkit'); ?></label></th>
        <td>
            <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('sitemap_urls_pre_file')); ?>" type="number" step="1" min="1" max="50000" id="<?php echo esc_attr(RY_Toolkit::get_option_name('sitemap_urls_pre_file')); ?>" value="<?php echo esc_attr(RY_Toolkit::get_option('sitemap_urls_pre_file', 2000)); ?>" class="small-text" />
            <?php esc_html_e('urls', 'ry-toolkit'); ?>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Sitemap entry', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Sitemap entry', 'ry-toolkit'); ?></span></legend>
                <?php foreach ($providers as $provider_name => $type_object) { ?>
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('sitemap_disable_provider')); ?>-<?php echo esc_attr($provider_name); ?>">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('sitemap_disable_provider')); ?>[<?php echo esc_attr($provider_name); ?>]" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('sitemap_disable_provider')); ?>-<?php echo esc_attr($provider_name); ?>" value="1" <?php checked('1', RY_Toolkit::get_option('sitemap_disable_provider')[$provider_name] ?? 0); ?> />
                    <?php
                    /* translators: %s: sitemap entry name. */
                    echo esc_html(sprintf(__('Disable generation "%s" entry sitemap', 'ry-toolkit'), $sitemap_provider_name[$provider_name] ?? $provider_name));
                    ?>
                </label><br />
                <?php } ?>
            </fieldset>
        </td>
    </tr>
    <tr class="sitemap-posts-options">
        <th scope="row"><?php esc_html_e('Additional tags to sitemap', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Additional tags to sitemap', 'ry-toolkit'); ?></span></legend>
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('sitemap_add_tag')); ?>-lastmod">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('sitemap_add_tag')); ?>[lastmod]" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('sitemap_add_tag')); ?>-lastmod" value="1" <?php checked('1', RY_Toolkit::get_option('sitemap_add_tag')['lastmod'] ?? 0); ?> />
                    <?php esc_html_e('Add last modification date for posts sitemap', 'ry-toolkit'); ?>
                </label><br />
            </fieldset>
        </td>
    </tr>

    <?php if (count($post_types)) { ?>
    <tr class="sitemap-posts-options">
        <th scope="row"><?php esc_html_e('Posts sitemap', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Posts sitemap', 'ry-toolkit'); ?></span></legend>
                <?php foreach ($post_types as $post_type => $type_object) { ?>
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('sitemap_disable_post_type')); ?>-<?php echo esc_attr($post_type); ?>">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('sitemap_disable_post_type')); ?>[<?php echo esc_attr($post_type); ?>]" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('sitemap_disable_post_type')); ?>-<?php echo esc_attr($post_type); ?>" value="1" <?php checked('1', RY_Toolkit::get_option('sitemap_disable_post_type')[$post_type] ?? 0); ?> />
                    <?php
                    /* translators: %s: sitemap entry name. */
                    echo esc_html(sprintf(__('Disable generation "%s" post type sitemap', 'ry-toolkit'), $type_object->labels->name));
                    ?>
                </label><br />
                <?php } ?>
            </fieldset>
        </td>
    </tr>
    <?php } ?>
    <?php if (count($taxonomies)) { ?>
    <tr class="sitemap-taxonomies-options">
        <th scope="row"><?php esc_html_e('Taxonomies sitemap', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Taxonomies sitemap', 'ry-toolkit'); ?></span></legend>
                <?php foreach ($taxonomies as $taxonomy => $type_object) { ?>
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('sitemap_disable_taxonomy')); ?>-<?php echo esc_attr($taxonomy); ?>">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('sitemap_disable_taxonomy')); ?>[<?php echo esc_attr($taxonomy); ?>]" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('sitemap_disable_taxonomy')); ?>-<?php echo esc_attr($taxonomy); ?>" value="1" <?php checked('1', RY_Toolkit::get_option('sitemap_disable_taxonomy')[$taxonomy] ?? 0); ?> />
                    <?php
                    /* translators: %s: sitemap entry name. */
                    echo esc_html(sprintf(__('Disable generation "%s" taxonomy sitemap', 'ry-toolkit'), $type_object->labels->name));
                    ?>
                </label><br />
                <?php } ?>
            </fieldset>
        </td>
    </tr>
    <?php } ?>
</table>
