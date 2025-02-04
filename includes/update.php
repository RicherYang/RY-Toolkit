<?php

class RY_Toolkit_Update
{
    public static function update(): void
    {
        $now_version = RY_Toolkit::get_option('version');

        if (RY_TOOLKIT_VERSION === $now_version) {
            return;
        }

        if (version_compare($now_version, '1.3.0', '<')) {
            $options = [
                'sitemap_urls_pre_file',
                'sitemap_disable_provider',
                'sitemap_add_tag',
                'sitemap_disable_post_type',
                'sitemap_disable_taxonomy',
            ];
            if (!function_exists('wp_set_options_autoload')) {
                wp_set_options_autoload(array_map(['RY_Toolkit', 'get_option_name'], $options), false);
            } else {
                foreach ($options as $name) {
                    $value = RY_Toolkit::get_option($name, null);
                    if (null !== $value) {
                        RY_Toolkit::delete_option($name);
                        RY_Toolkit::update_option($name, $value, false);
                    }
                }
            }

            RY_Toolkit::delete_option('sitemap_add_tag');
            RY_Toolkit::update_option('sitemap_skip_page', [], false);

            RY_Toolkit::update_option('version', '1.3.0', true);
        }

        if (version_compare($now_version, '1.3.2', '<')) {
            RY_Toolkit::update_option('version', '1.3.2', true);
        }
    }
}
