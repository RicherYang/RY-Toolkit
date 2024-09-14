<?php

class RY_Toolkit_Admin
{
    protected static $_instance = null;

    private $instance = [];

    public function __get(string $name)
    {
        if (isset($this->instance[$name])) {
            return $this->instance[$name];
        }
    }

    public static function instance(): RY_Toolkit_Admin
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        include_once RY_TOOLKIT_PLUGIN_DIR . 'admin/page/abstracts-page.php';
        include_once RY_TOOLKIT_PLUGIN_DIR . 'admin/page/cron.php';
        include_once RY_TOOLKIT_PLUGIN_DIR . 'admin/page/opcache.php';
        include_once RY_TOOLKIT_PLUGIN_DIR . 'admin/page/options.php';
        include_once RY_TOOLKIT_PLUGIN_DIR . 'admin/page/tools.php';

        include_once RY_TOOLKIT_PLUGIN_DIR . 'admin/plugins.php';
        $this->instance['plugins'] = RY_Toolkit_Admin_Plugins::instance();

        include_once RY_TOOLKIT_PLUGIN_DIR . 'admin/site-health.php';
        $this->instance['site-health'] = RY_Toolkit_Admin_Site_Health::instance();

        add_action('load-options.php', [$this, 'add_options']);
        add_action('load-options-media.php', [$this, 'add_options']);

        add_action('admin_post_ry-toolkit-action', [$this, 'do_action']);
        add_action('all_admin_notices', [$this, 'show_notices']);

        add_action('admin_init', [$this, 'register_style_script']);
        add_action('admin_init', [$this, 'init_frontend']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);

        add_action('admin_menu', [$this, 'admin_menu']);
    }

    public function add_options(): void
    {
        include_once RY_TOOLKIT_PLUGIN_DIR . 'admin/options.php';
        $this->instance['options'] = RY_Toolkit_Admin_Options::instance();
    }

    public function do_action(): void
    {
        if (wp_verify_nonce(wp_unslash($_REQUEST['_wpnonce'] ?? ''), 'ry-toolkit-action')) {
            $redirect = apply_filters('ry-toolkit/admin_action', '');
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
            add_menu_page(__('RY Tool', 'ry-toolkit'), __('RY Tool', 'ry-toolkit'), 'manage_options', $menu_list[0]['slug'], '', 'dashicons-admin-tools');
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

    public function the_action_form(string $page, string $action, string $submit_text, array $hidden_value = []): void
    {
        $post_url = add_query_arg([
            'ry-toolkit-page' => $page,
        ], admin_url('admin-post.php'));

        $hidden_value = array_merge($hidden_value, [
            'action' => 'ry-toolkit-action',
            'ry-toolkit-action' => $action,
        ]);

        include RY_TOOLKIT_PLUGIN_DIR . 'admin/html/action_form.php';
    }

    public function the_action_link(string $page, string $action, array $add_args = []): string
    {
        $add_args = array_merge($add_args, [
            'ry-toolkit-page' => $page,
            'action' => 'ry-toolkit-action',
            'ry-toolkit-action' => $action,
            '_wpnonce' => wp_create_nonce('ry-toolkit-action'),
            '_ry_toolkit_action_nonce' => wp_create_nonce($action),
        ]);

        return add_query_arg($add_args, admin_url('admin-post.php'));
    }

    public static function the_bool_option_checkbox(string $option_name, string $label, string $sub_name = null): void
    {
        $id = esc_attr(RY_Toolkit::get_option_name($option_name));
        $name = esc_attr(RY_Toolkit::get_option_name($option_name));
        if (null === $sub_name) {
            $value = RY_Toolkit::get_option($option_name);
        } else {
            $id .= '-' . esc_attr($sub_name);
            $name .= '[' . esc_attr($sub_name) . ']';
            $value = RY_Toolkit::get_option($option_name)[$sub_name] ?? 0;
        }
        printf(
            '<label for="%1$s"><input type="checkbox" id="%1$s" name="%2$s" value="1" %3$s /> %4$s</label>',
            $id,
            $name,
            checked('1', $value, false),
            esc_html($label)
        );
    }
}
