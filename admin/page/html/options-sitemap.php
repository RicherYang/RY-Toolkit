<?php
$providers = wp_get_sitemap_providers();

$post_types = get_post_types(['public' => true], 'objects');
unset($post_types['attachment']);
$post_types = array_filter($post_types, 'is_post_type_viewable');

$taxonomies = get_taxonomies(['public' => true], 'objects');
$taxonomies = array_filter($taxonomies, 'is_taxonomy_viewable');

$skip_pages = [];
if (class_exists('WooCommerce', false)) {
    $skip_pages['wc_shop'] = __('WooCommerce Shop', 'ry-toolkit');
    $skip_pages['wc_cart'] = __('WooCommerce Cart', 'ry-toolkit');
    $skip_pages['wc_checkout'] = __('WooCommerce Checkout', 'ry-toolkit');
    $skip_pages['wc_myaccount'] = __('WooCommerce My account', 'ry-toolkit');
    $skip_pages['wc_terms'] = __('WooCommerce Terms and conditions', 'ry-toolkit');
}
?>

<p><a href="<?php echo esc_url(get_sitemap_url('index')); ?>"><?php esc_html_e('View XML sitemap index.', 'ry-toolkit'); ?></a></p>

<table id="ry-toolkit-options-sitemap" class="form-table ry-toolkit-options">
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
                <?php foreach ($providers as $provider_name => $type_object) {
                    RY_Toolkit_Admin::the_bool_option_checkbox('sitemap_disable_provider', sprintf(
                        /* translators: %s: sitemap entry name. */
                        __('Disable generation "%s" entry sitemap', 'ry-toolkit'),
                        $sitemap_provider_name[$provider_name] ?? $provider_name
                    ), $provider_name);
                } ?>
            </fieldset>
        </td>
    </tr>

    <?php if (count($post_types)) { ?>
    <tr class="sitemap-posts-options">
        <th scope="row"><?php esc_html_e('"Posts" entry sitemap', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('"Posts" entry sitemap', 'ry-toolkit'); ?></span></legend>
                <?php foreach ($post_types as $post_type => $type_object) {
                    RY_Toolkit_Admin::the_bool_option_checkbox('sitemap_disable_post_type', sprintf(
                        /* translators: %s: sitemap entry name. */
                        __('Disable generation "%s" post type sitemap', 'ry-toolkit'),
                        $type_object->labels->name
                    ), $post_type);
                } ?>
            </fieldset>
        </td>
    </tr>
    <?php if (count($skip_pages)) { ?>
    <tr class="sitemap-skip-page-options">
        <th scope="row"><?php esc_html_e('"Page" post type sitemap', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('"Page" post type sitemap', 'ry-toolkit'); ?></span></legend>
                <?php foreach ($skip_pages as $page => $page_name) {
                    RY_Toolkit_Admin::the_bool_option_checkbox('sitemap_skip_page', sprintf(
                        /* translators: %s: page name. */
                        __('Skip "%s" page', 'ry-toolkit'),
                        $page_name
                    ), $page);
                } ?>
            </fieldset>
        </td>
    </tr>
    <?php } ?>

    <?php } ?>

    <?php if (count($taxonomies)) { ?>
    <tr class="sitemap-taxonomies-options">
        <th scope="row"><?php esc_html_e('Taxonomies sitemap', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('Taxonomies sitemap', 'ry-toolkit'); ?></span></legend>
                <?php foreach ($taxonomies as $taxonomy => $type_object) {
                    RY_Toolkit_Admin::the_bool_option_checkbox('sitemap_disable_taxonomy', sprintf(
                        /* translators: %s: sitemap entry name. */
                        __('Disable generation "%s" taxonomy sitemap', 'ry-toolkit'),
                        $type_object->labels->name
                    ), $taxonomy);
                } ?>
            </fieldset>
        </td>
    </tr>
    <?php } ?>
</table>
