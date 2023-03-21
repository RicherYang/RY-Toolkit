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
        wp_enqueue_script('ry-toolkit-options');

        require ABSPATH . 'wp-admin/options-head.php';

        echo '<div class="wrap"><h1>' . esc_html(__('Options', 'ry-toolkit')) . '</h1>';
        echo '<form action="options.php" method="post">';
        settings_fields('ry-toolkit-options');

        include RY_TOOLKIT_PLUGIN_DIR . 'admin/page/html/options.php';

        submit_button();
        echo '</form>';
        echo '</div>';
    }
}

RY_Toolkit_Admin_Page_Options::init_page();
