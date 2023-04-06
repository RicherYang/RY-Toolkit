<?php

final class RY_Toolkit_Admin_Page_Options extends RY_Toolkit_Admin_Page
{
    protected static $page_type = 'tools';

    public static function init_page()
    {
        add_filter('ry-toolkit/menu_list', [__CLASS__, 'add_menu'], 1);
        add_action('ry-toolkit/admin_action', [__CLASS__, 'admin_action']);
    }

    public static function add_menu($menu_list)
    {
        $menu_list[] = [
            'name' => __('Options', 'ry-toolkit'),
            'slug' => 'ry-toolkit-options',
            'function' => [__CLASS__, 'pre_show_page'],
            'position' => 0
        ];

        return $menu_list;
    }

    protected function do_init(): void
    {
    }

    public function show_page(): void
    {
        $type_list = [
            'frontend' => __('Website frontend', 'ry-toolkit'),
            'core' => __('WordPress core function', 'ry-toolkit')
        ];

        if(has_action('init', 'wp_sitemaps_get_server')) {
            $sitemaps = wp_sitemaps_get_server();
            if ($sitemaps->sitemaps_enabled()) {
                $type_list['sitemap'] = __('WordPress sitemap', 'ry-toolkit');
                $sitemap_provider_name = [
                    'posts' => _x('posts', 'sitemap provider', 'ry-toolkit'),
                    'taxonomies' => _x('taxonomies', 'sitemap provider', 'ry-toolkit'),
                    'users' => _x('users', 'sitemap provider', 'ry-toolkit')
                ];
            }
        }

        $show_type = sanitize_key(wp_unslash($_GET['type'] ?? ''));
        if (empty($show_type)) {
            $show_type = 'frontend';
        }

        wp_enqueue_script('ry-toolkit-options');

        echo '<div class="wrap"><h1>' . esc_html(__('Options', 'ry-toolkit')) . '</h1>';
        require ABSPATH . 'wp-admin/options-head.php';

        include RY_TOOLKIT_PLUGIN_DIR . 'admin/page/html/options-nav.php';

        if (!isset($type_list[$show_type])) {
            echo '</div>';
            return;
        }

        $load_file = RY_TOOLKIT_PLUGIN_DIR . "admin/page/html/options-{$show_type}.php";
        if (!is_file($load_file)) {
            echo '</div>';
            return;
        }

        echo '<form action="options.php" method="post">';
        settings_fields('ry-toolkit-options-' . $show_type);

        include $load_file;

        submit_button();
        echo '</form>';
        echo '</div>';
    }
}

RY_Toolkit_Admin_Page_Options::init_page();
