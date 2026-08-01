<?php

namespace RY\Toolkit\Admin;

defined('ABSPATH') or exit;

use RY\General\V20260801\AbstractAdmin;
use RY\Toolkit\Admin\Page\Cron as PageCron;
use RY\Toolkit\Admin\Page\Opcache as PageOpcache;
use RY\Toolkit\Admin\Page\Options as PageOptions;
use RY\Toolkit\Admin\Page\PostType as PagePostType;
use RY\Toolkit\Admin\Page\Tools as PageTools;

final class Admin extends AbstractAdmin
{
    private static ?self $_instance = null;

    public static function instance(): Admin
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        PageCron::init_menu();
        PageOpcache::init_menu();
        PageOptions::init_menu();
        PagePostType::init_menu();
        PageTools::init_menu();

        Plugins::instance();
        Post::instance();
        SiteHealth::instance();

        add_action('load-options.php', [$this, 'add_options']);
        add_action('load-options-media.php', [$this, 'add_options']);

        add_action('all_admin_notices', [$this, 'show_notices']);

        add_action('admin_init', [$this, 'register_style_script']);
        add_action('admin_init', [$this, 'init_frontend']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);

        add_action('admin_menu', [$this, 'admin_menu']);
    }

    public function add_options(): void
    {
        Options::instance();
    }

    public function show_notices(): void
    {
        $notice = get_transient('ry-notice');
        if (is_array($notice) && count($notice)) {
            foreach ($notice as $status => $message) {
                echo '<div class="notice notice-' . esc_attr($status) . ' is-dismissible">';
                echo '<p><strong>' . implode('</strong></p><p><strong>', array_map('esc_html', $message)) . '</strong></p>';
                echo '</div>';
            }

            set_transient('ry-notice', []);
        }
    }

    public function register_style_script(): void
    {
        $asset_info = include RY_TOOLKIT_PLUGIN_DIR . 'assets/admin/main.asset.php';
        wp_register_style('ry-toolkit-admin', RY_TOOLKIT_PLUGIN_URL . 'assets/admin/main.css', $asset_info['dependencies'], $asset_info['version']);

        $asset_info = include RY_TOOLKIT_PLUGIN_DIR . 'assets/admin/options.asset.php';
        wp_register_script('ry-toolkit-options', RY_TOOLKIT_PLUGIN_URL . 'assets/admin/options.js', $asset_info['dependencies'], $asset_info['version'], true);

        $asset_info = include RY_TOOLKIT_PLUGIN_DIR . 'assets/admin/tools.asset.php';
        wp_register_script('ry-toolkit-tools', RY_TOOLKIT_PLUGIN_URL . 'assets/admin/tools.js', $asset_info['dependencies'], $asset_info['version'], true);
    }

    public function init_frontend()
    {
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');
    }

    public function admin_enqueue_scripts(): void
    {
        wp_enqueue_style('ry-toolkit-admin');
    }

    public function admin_menu(): void
    {
        $menu_list = apply_filters('ry-toolkit/menu_list', []);

        if (count($menu_list)) {
            add_menu_page(__('RY Tool', 'ry-toolkit'), __('RY Tool', 'ry-toolkit'), 'manage_options', $menu_list[0]['slug'], '', 'dashicons-admin-tools', 101);
            foreach ($menu_list as $menu_item) {
                $hook_suffix = add_submenu_page($menu_list[0]['slug'], $menu_item['name'], $menu_item['name'], 'manage_options', $menu_item['slug'], $menu_item['function'], $menu_item['position'] ?? null);
                do_action('ry-toolkit/add_page-' . $menu_item['slug'], $hook_suffix);
            }
        }
    }

    public function add_notice(string $status, string $message): void
    {
        $notice = get_transient('ry-notice');
        if (!is_array($notice)) {
            $notice = [];
        }
        if (!isset($notice[$status])) {
            $notice[$status] = [];
        }
        $notice[$status][] = $message;

        set_transient('ry-notice', $notice);
    }
}
