<?php

final class RY_Admin_Page_Options extends RY_Admin_Page
{
    protected static $page_type = 'tools';

    public static function init_page()
    {
        add_filter('ry/menu_list', [__CLASS__, 'add_menu'], 1);
        add_action('ry/admin_action', [__CLASS__, 'admin_action']);
    }

    public static function add_menu($menu_list)
    {
        $menu_list[] = [
            'name' => __('Options', 'ry-toolkit'),
            'slug' => 'ry-options',
            'function' => [__CLASS__, 'pre_show_page']
        ];

        return $menu_list;
    }

    protected function do_init(): void
    {
    }

    public function show_page(): void
    {
        wp_enqueue_script('ry-options');

        echo '<div class="wrap"><h1>' . esc_html(__('Options', 'ry-toolkit')) . '</h1>';
        echo '<form action="options.php" method="post">';
        settings_fields('ry-options');

        include RY_PLUGIN_DIR . 'admin/page/html/options.php';

        submit_button();
        echo '</form>';
        echo '</div>';
    }
}

RY_Admin_Page_Options::init_page();
