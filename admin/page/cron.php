<?php

final class RY_Toolkit_Admin_Page_Cron extends RY_Toolkit_Admin_Page
{
    protected static $page_type = 'cron';

    public static function init_page()
    {
        add_filter('ry-toolkit/menu_list', [__CLASS__, 'add_menu']);
    }

    public static function add_menu($menu_list)
    {
        $menu_list[] = [
            'name' => __('Cron', 'ry-toolkit'),
            'slug' => 'ry-toolkit-cron',
            'function' => [__CLASS__, 'pre_show_page']
        ];

        return $menu_list;
    }

    protected function do_init(): void
    {
        include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
        include_once RY_TOOLKIT_PLUGIN_DIR . 'admin/includes/cron-event-list-table.php';
    }

    public function show_page(): void
    {
        $table = new RY_Toolkit_Cron_Event_List_Table();
        $table->prepare_items();

        echo '<div class="wrap"><h1>' . esc_html(__('Cron', 'ry-toolkit')) . '</h1>';

        include RY_TOOLKIT_PLUGIN_DIR . 'admin/page/html/cron-event.php';

        echo '</div>';
    }
}

RY_Toolkit_Admin_Page_Cron::init_page();
