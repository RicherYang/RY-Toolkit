<?php

final class RY_Toolkit_Admin_Page_Tools extends RY_Toolkit_Admin_Page
{
    public const TRANSIENT_KEYS = ['_transient_', '_site_transient_'];

    protected static $page_type = 'tools';

    public static function init_page(): void
    {
        add_filter('ry-toolkit/menu_list', [__CLASS__, 'add_menu'], 5);
        add_action('admin_post_ry-toolkit-action', [__CLASS__, 'admin_post_action']);
    }

    public static function add_menu($menu_list)
    {
        $menu_list[] = [
            'name' => __('Tools', 'ry-toolkit'),
            'slug' => 'ry-toolkit-tools',
            'function' => [__CLASS__, 'pre_show_page'],
        ];

        return $menu_list;
    }

    protected function do_init(): void {}

    public function show_page(): void
    {
        global $wpdb;

        $transients = 0;
        foreach (self::TRANSIENT_KEYS as $transient_key) {
            $transients += (int) $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(option_id) FROM {$wpdb->options} WHERE option_name LIKE %s",
                $wpdb->esc_like($transient_key) . '%'
            ));
        }

        echo '<div class="wrap"><h1>' . esc_html__('Tools', 'ry-toolkit') . '</h1>';

        include RY_TOOLKIT_PLUGIN_DIR . 'admin/page/html/tools.php';

        echo '</div>';
    }

    protected function analyze_tables(): string
    {
        global $wpdb;

        check_ajax_referer('ry-toolkit-action/analyze-tables', '_ry_toolkit_nonce');

        $start = time();

        $analyzed_table = get_transient('ry_analyzed_table');
        if (!is_array($analyzed_table)) {
            $analyzed_table = [];
        }

        $tables = $wpdb->get_col($wpdb->prepare(
            'SHOW TABLES LIKE %s',
            $wpdb->esc_like($wpdb->prefix) . '%'
        ));
        foreach ($tables as $table) {
            if (isset($analyzed_table[$table])) {
                continue;
            }

            $wpdb->query("ANALYZE TABLE `$table`"); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
            $analyzed_table[$table] = true;
            set_transient('ry_analyzed_table', $analyzed_table, 600);

            if (1 < time() - $start) {
                return RY_Toolkit()->admin->the_action_link('tools', 'analyze-tables', [
                    '_wp_http_referer' => urlencode(sanitize_url(wp_unslash($_REQUEST['_wp_http_referer'] ?? ''))), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                ]);
            }
        }

        delete_transient('ry_analyzed_table');
        RY_Toolkit()->admin->add_notice('success', __('Database tables analyzed successfully.', 'ry-toolkit'));

        return '';
    }

    protected function optimize_tables(): string
    {
        global $wpdb;

        check_ajax_referer('ry-toolkit-action/optimize-tables', '_ry_toolkit_nonce');

        $start = time();

        $optimized_table = get_transient('ry_optimized_table');
        if (!is_array($optimized_table)) {
            $optimized_table = [];
        }

        $tables = $wpdb->get_col($wpdb->prepare(
            'SHOW TABLES LIKE %s',
            $wpdb->esc_like($wpdb->prefix) . '%'
        ));
        foreach ($tables as $table) {
            if (isset($optimized_table[$table])) {
                continue;
            }

            $wpdb->query("OPTIMIZE TABLE `$table`"); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
            $optimized_table[$table] = true;
            set_transient('ry_optimized_table', $optimized_table, 600);

            if (1 < time() - $start) {
                return RY_Toolkit()->admin->the_action_link('tools', 'optimize-tables', [
                    '_wp_http_referer' => urlencode(sanitize_url(wp_unslash($_REQUEST['_wp_http_referer'] ?? ''))), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                ]);
            }
        }

        delete_transient('ry_optimized_table');
        RY_Toolkit()->admin->add_notice('success', __('Database tables optimized successfully.', 'ry-toolkit'));

        return '';
    }

    protected function clear_transient(): string
    {
        global $wpdb;

        check_ajax_referer('ry-toolkit-action/clear-transient', '_ry_toolkit_nonce');

        foreach (self::TRANSIENT_KEYS as $transient_key) {
            $transients = $wpdb->get_col($wpdb->prepare(
                "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
                $wpdb->esc_like($transient_key) . '%'
            ));
            if ($transients) {
                foreach ($transients as $transient) {
                    if ('_transient_' === $transient_key) {
                        delete_transient(str_replace('_transient_', '', $transient));
                    } else {
                        delete_site_transient(str_replace('_site_transient_', '', $transient));
                    }
                }
            }
        }
        RY_Toolkit()->admin->add_notice('success', __('Clear transient option successfully.', 'ry-toolkit'));

        return '';
    }
}

RY_Toolkit_Admin_Page_Tools::init_page();
