<?php

namespace RY\Toolkit;

defined('ABSPATH') or exit;

final class Update
{
    public static function update(): void
    {
        $now_version = Main::get_option('version', '0.0.0');

        if (RY_TOOLKIT_VERSION === $now_version) {
            return;
        }

        if ($now_version === '0.0.0') {
            Main::update_option('version', RY_TOOLKIT_VERSION, true);
            return;
        }

        if (version_compare($now_version, '1.3.0', '<')) {
            $options = [
                'sitemap_urls_pre_file',
                'sitemap_disable_provider',
                'sitemap_disable_post_type',
                'sitemap_disable_taxonomy',
            ];
            if (!function_exists('wp_set_options_autoload')) {
                wp_set_options_autoload(array_map([Main::class, 'get_prefix_name'], $options), false); // phpcs:ignore wp_function_not_compatible_with_requires_wp
            } else {
                foreach ($options as $name) {
                    $value = Main::get_option($name, null);
                    if (null !== $value) {
                        Main::delete_option($name);
                        Main::update_option($name, $value, false);
                    }
                }
            }

            Main::delete_option('sitemap_add_tag');
            Main::update_option('sitemap_skip_page', [], false);

            Main::update_option('version', '1.3.0', true);
        }

        if (version_compare($now_version, '1.4.7', '<')) {
            Main::update_option('show_thumbnails', [], false);

            Main::update_option('version', '1.4.7', true);
        }

        if (version_compare($now_version, '2026.8.5', '<')) {
            add_action('init', function () {
                if (class_exists('\RY\General\V20260801\Logs')) {
                    $file_dir = \RY\General\V20260801\Logs::get_log_directory();
                    foreach (new \FilesystemIterator($file_dir, \FilesystemIterator::SKIP_DOTS) as $file) {
                        if ($file->isFile() && $file->isReadable()) {
                            if ($file->getExtension() === 'log') {
                                $file_name = $file->getBasename('.log');
                                $parts = explode('-', $file_name);
                                if (count($parts) > 4) {
                                    $hash_suffix = array_pop($parts);
                                    $date_suffix = implode('-', array_slice($parts, -3));
                                    $handle = implode('-', array_slice($parts, 0, -3));
                                    if (wp_hash($handle) === $hash_suffix) {
                                        $file_name = sanitize_file_name(implode('-', [$handle, $date_suffix, wp_hash($handle . $date_suffix)]) . '.log');
                                        rename($file->getPathname(), $file_dir . '/' . $file_name); // phpcs:ignore WordPress.WP.AlternativeFunctions.rename_rename
                                    }
                                }
                            }
                        }
                    }

                    Main::update_option('version', '2026.8.5', true);
                }
            });
        }

        if (version_compare($now_version, '2026.9.1', '<')) {
            Main::update_option('version', '2026.9.1', true);
        }
    }
}
