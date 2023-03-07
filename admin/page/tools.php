<?php

final class RY_Admin_Page_Tools extends RY_Admin_Page
{
    private $transient_key = ['_transient_', '_site_transient_'];

    protected static $page_type = 'tools';

    public static function init_page()
    {
        add_filter('ry/menu_list', [__CLASS__, 'add_menu'], 0);
        add_action('ry/admin_action', [__CLASS__, 'admin_action']);
    }

    public static function add_menu($menu_list)
    {
        $menu_list[] = [
            'name' => __('Tools', 'ry-tools'),
            'slug' => 'ry-tools',
            'function' => [__CLASS__, 'pre_show_page']
        ];

        return $menu_list;
    }

    protected function do_init(): void
    {
    }

    public function show_page(): void
    {
        global $wpdb;

        $transients = 0;
        foreach ($this->transient_key as $transient_key) {
            $transients += (int) $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(option_id) FROM {$wpdb->options} WHERE option_name LIKE %s",
                $wpdb->esc_like($transient_key) .'%'
            ));
        }

        echo '<div class="wrap"><h1>' . esc_html(__('Tools', 'ry-tools')) . '</h1>';

        include RY_PLUGIN_DIR . 'admin/page/html/tools.php';

        echo '</div>';
    }

    protected function analyze_tables(): string
    {
        global $wpdb;

        $start = time();

        $analyzed_table = get_transient('ry_analyzed_table');
        if (!is_array($analyzed_table)) {
            $analyzed_table = [];
        }

        $tables = $wpdb->get_col($wpdb->prepare(
            'SHOW TABLES LIKE %s',
            $wpdb->esc_like($wpdb->prefix) .'%'
        ));
        foreach ($tables as $table) {
            if (isset($analyzed_table[$table])) {
                continue;
            }

            $wpdb->query("ANALYZE TABLE `$table`");
            $analyzed_table[$table] = true;
            set_transient('ry_analyzed_table', $analyzed_table, 600);

            if (time() - $start > 1) {
                return add_query_arg([
                    'ry-page' => 'tools',
                    'action' => 'ry-action',
                    'ry-action' => 'analyze-tables',
                    '_wp_http_referer' => wp_unslash($_REQUEST['_wp_http_referer'] ?? ''),
                    '_wpnonce' => wp_create_nonce('ry-action'),
                    '_ry_action_nonce' => wp_create_nonce('analyze-tables')
                ], admin_url('admin-post.php'));
            }
        }

        delete_transient('ry_analyzed_table');
        RY()->admin->add_notice('success', __('Database tables analyzed successfully.', 'ry-tools'));

        return '';
    }

    protected function optimize_tables(): string
    {
        global $wpdb;

        $start = time();

        $optimized_table = get_transient('ry_optimized_table');
        if (!is_array($optimized_table)) {
            $optimized_table = [];
        }

        $tables = $wpdb->get_col($wpdb->prepare(
            'SHOW TABLES LIKE %s',
            $wpdb->esc_like($wpdb->prefix) .'%'
        ));
        foreach ($tables as $table) {
            if (isset($optimized_table[$table])) {
                continue;
            }

            $wpdb->query("OPTIMIZE TABLE `$table`");
            $optimized_table[$table] = true;
            set_transient('ry_optimized_table', $optimized_table, 600);

            if (time() - $start > 1) {
                return add_query_arg([
                    'ry-page' => 'tools',
                    'action' => 'ry-action',
                    'ry-action' => 'optimize-tables',
                    '_wp_http_referer' => wp_unslash($_REQUEST['_wp_http_referer'] ?? ''),
                    '_wpnonce' => wp_create_nonce('ry-action'),
                    '_ry_action_nonce' => wp_create_nonce('optimize-tables')
                ], admin_url('admin-post.php'));
            }
        }

        delete_transient('ry_optimized_table');
        RY()->admin->add_notice('success', __('Database tables optimized successfully.', 'ry-tools'));

        return '';
    }

    protected function clear_transient(): string
    {
        global $wpdb;

        foreach ($this->transient_key as $transient_key) {
            $transients = $wpdb->get_col($wpdb->prepare(
                "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
                $wpdb->esc_like($transient_key) . '%'
            ));
            if ($transients) {
                foreach ($transients as $transient) {
                    if ('_transient_' == $transient_key) {
                        delete_transient(str_replace('_transient_', '', $transient));
                    } else {
                        delete_site_transient(str_replace('_site_transient_', '', $transient));
                    }
                }
            }
        }

        return '';
    }
}

RY_Admin_Page_Tools::init_page();
