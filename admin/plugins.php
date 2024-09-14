<?php

class RY_Toolkit_Admin_Plugins extends RY_Toolkit_Admin_Page
{
    protected static $page_type = 'ry-plugins';

    protected function do_init(): void
    {
        if (class_exists('ZipArchive')) {
            add_filter('network_admin_plugin_action_links', [$this, 'add_actions'], 10, 2);
            add_filter('plugin_action_links', [$this, 'add_actions'], 10, 3);

            add_action('ry-toolkit/admin_action', [__CLASS__, 'admin_action']);
        }
    }

    public function show_page(): void {}

    public function add_actions(array $actions, string $plugin_file, ?array $plugin_data): array
    {
        if ($this->check_user_can()) {
            $plugin_slug = $plugin_data['slug'] ?? sanitize_title($plugin_data['Name']);

            $actions['ry_download'] = sprintf(
                '<a href="%s" id="download-%s" class="edit" aria-label="%s" target="_blank">%s</a>',
                esc_url(RY_Toolkit()->admin->the_action_link('ry-plugins', 'plugin-download', [
                    'plugin_file' => urlencode($plugin_file),
                ])),
                esc_attr($plugin_slug),
                esc_attr(sprintf(
                    /* translators: %s: Plugin name. */
                    __('Download %s', 'ry-toolkit'),
                    $plugin_data['Name'],
                )),
                esc_html__('Download', 'ry-toolkit'),
            );
        }

        return $actions;
    }

    protected function plugin_download(): void
    {
        global $wp_filesystem;

        if (!$this->check_user_can()) {
            wp_die(esc_html__('Sorry, you are not allowed to download plugin.', 'ry-toolkit'));
        }

        $plugin_file = wp_unslash($_GET['plugin_file'] ?? '');
        if (validate_file($plugin_file)) {
            wp_die(esc_html__('Invalid plugin path.', 'ry-toolkit'));
        }

        include_once ABSPATH . 'wp-admin/includes/file.php';
        WP_Filesystem();

        $plugins_dir = $wp_filesystem->wp_plugins_dir();
        $plugins_dir = trailingslashit($plugins_dir);
        $real_plugin_dir = trailingslashit(dirname($plugins_dir . $plugin_file));
        if (!file_exists($real_plugin_dir)) {
            wp_die(esc_html__('Sorry, cannot find the plugin.', 'ry-toolkit'));
        }

        $tmp_zip_file = wp_tempnam('ry-toolkit-download');
        wp_delete_file($tmp_zip_file);
        $zip = new ZipArchive();
        if (true === $zip->open($tmp_zip_file, ZipArchive::CREATE)) {
            if (strpos($plugin_file, '/') && $real_plugin_dir !== $plugins_dir) {
                $plugin_name = pathinfo($real_plugin_dir, PATHINFO_BASENAME);
                $this->add_dir_to_zip($zip, $real_plugin_dir, strlen($plugins_dir));
            } else {
                $plugin_name = pathinfo($plugin_file, PATHINFO_FILENAME);
                $zip->addFile($plugins_dir . $plugin_file, $plugin_file);
            }
            $zip->close();

            if (is_file($tmp_zip_file) && 0 < filesize($tmp_zip_file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . sanitize_key($plugin_name) . '.zip"');
                header('Content-Length: ' . filesize($tmp_zip_file));
                readfile($tmp_zip_file);
                wp_delete_file($tmp_zip_file);
                exit();
            } else {
                wp_die(esc_html__('Unable to generate zip file.', 'ry-toolkit'));
            }
        } else {
            wp_die(esc_html__('Unable to open zip file for writing.', 'ry-toolkit'));
        }
    }

    protected function check_user_can(): bool
    {
        if (is_multisite()) {
            return is_super_admin();
        }

        return current_user_can('activate_plugins') && current_user_can('install_plugins') && current_user_can('delete_plugins');
    }

    private function add_dir_to_zip(ZipArchive &$zip, string $dir, int $cat_length): void
    {
        $file_list = list_files($dir, 1);
        foreach ($file_list as $file) {
            if (is_file($file)) {
                $zip->addFile($file, substr($file, $cat_length));
            } else {
                $this->add_dir_to_zip($zip, $file, $cat_length);
            }
        }
    }
}
