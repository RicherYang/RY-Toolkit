<?php

$providers = wp_get_sitemap_providers();

$post_types = get_post_types(['public' => true], 'objects');
unset($post_types['attachment']);
$post_types = array_filter($post_types, 'is_post_type_viewable');

$taxonomies = get_taxonomies(['public' => true], 'objects');
$taxonomies = array_filter($taxonomies, 'is_taxonomy_viewable');

?>

<table class="form-table">
    <tr>
        <th scope="row"><?php esc_html_e('.htaccess', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('.htaccess', 'ry-toolkit'); ?></span></legend>
                <?php foreach ($htaccess_name as $key => $name) { ?>
                <label for="<?php echo esc_attr(RY_Toolkit::get_option_name('wp_rocket_htaccess')); ?>-<?php echo esc_attr($key); ?>">
                    <input name="<?php echo esc_attr(RY_Toolkit::get_option_name('wp_rocket_htaccess')); ?>[<?php echo esc_attr($key); ?>]" type="checkbox" id="<?php echo esc_attr(RY_Toolkit::get_option_name('wp_rocket_htaccess')); ?>-<?php echo esc_attr($key); ?>" value="1" <?php checked('1', RY_Toolkit::get_option('wp_rocket_htaccess')[$key] ?? 0); ?> />
                    <?php
                    /* translators: %1$s: rule key %1$s: rule description */
                    echo esc_html(sprintf(__('Disable rules "%1$s" (%2$s)', 'ry-toolkit'), esc_attr($key), $name));
                    ?>
                </label><br />
                <?php } ?>
            </fieldset>
        </td>
    </tr>
    </tbody>
</table>
