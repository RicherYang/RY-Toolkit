<?php

class RY_Admin
{
    protected static $_instance = null;

    public static function instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        include_once RY_PLUGIN_DIR . 'admin/page/abstracts-page.php';
        include_once RY_PLUGIN_DIR . 'admin/page/opcache.php';
        include_once RY_PLUGIN_DIR . 'admin/page/tools.php';

        add_action('admin_post_ry-action', [$this, 'do_action']);
        add_action('all_admin_notices', [$this, 'show_notices']);

        add_action('admin_init', [$this, 'register_style_script']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);

        add_action('admin_menu', [$this, 'admin_menu']);

        add_action('load-options.php', [$this, 'add_options']);
        add_action('load-options-media.php', [$this, 'add_options']);
    }

    public function do_action(): void
    {
        if (wp_verify_nonce($_REQUEST['_wpnonce'] ?? '', 'ry-action')) {
            $redirect = apply_filters('ry/admin_action', '');
        }

        if (empty($redirect)) {
            $redirect = admin_url();
        }
        wp_safe_redirect($redirect);
    }

    public function show_notices(): void
    {
        $notice = get_transient('ry-notice');
        if (is_array($notice) && count($notice)) {
            foreach ($notice as $status => $message) {
                echo '<div class="notice notice-' . esc_attr($status) . ' is-dismissible">';
                echo '<p><strong>' . implode('</strong></p><p><strong>', $message) . '</strong></p>';
                echo '</div>';
            }

            set_transient('ry-notice', []);
        }
    }

    public function register_style_script(): void
    {
        $suffix = SCRIPT_DEBUG ? '' : '.min';

        wp_register_style('ry-admin', RY_PLUGIN_URL . 'assets/css/admin/main' . $suffix . '.css', [], RY_VERSION);
    }

    public function admin_enqueue_scripts()
    {
        wp_enqueue_style('ry-admin');
    }

    public function admin_menu(): void
    {
        $menu_list = apply_filters('ry/menu_list', []);

        if (count($menu_list)) {
            $main_slug = $menu_list[0]['slug'];
            add_menu_page(__('RY Tool', 'ry-tools'), __('RY Tool', 'ry-tools'), 'manage_options', $main_slug, false, 'dashicons-admin-tools');
            foreach ($menu_list as $menu_item) {
                add_submenu_page($main_slug, $menu_item['name'], $menu_item['name'], 'manage_options', $menu_item['slug'], $menu_item['function']);
            }
        }
    }

    public function add_options(): void
    {
        include_once RY_PLUGIN_DIR . 'admin/options.php';
        RY_Admin_Options::instance();
    }

    public function add_notice(string $status, string $message): void
    {
        $notice = get_transient('ry-notice');
        if (!isset($notice[$status])) {
            $notice[$status] = [];
        }
        $notice[$status][] = $message;

        set_transient('ry-notice', $notice);
    }

    public function the_action_form(string $page, string $action, string $submit_text, $hidden_value = []): void
    {
        $post_url = add_query_arg([
            'ry-page' => $page
        ], admin_url('admin-post.php'));

        $hidden_value = array_merge($hidden_value, [
            'action' => 'ry-action',
            'ry-action' => $action
        ]);

        include RY_PLUGIN_DIR . 'admin/html/action_form.php';
    }
}
