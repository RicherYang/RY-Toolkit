<?php

namespace RY\Toolkit\Admin\Dashboard;

defined('ABSPATH') or exit;

use RY\Toolkit\Main;

final class ActionScheduler
{
    private static ?self $_instance = null;

    public function __construct()
    {
        wp_add_dashboard_widget(Main::get_prefix_name('action_scheduler'), __('Scheduled Actions Status', 'ry-toolkit'), [$this, 'status_widget']);
    }

    public static function instance(): ActionScheduler
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void {}

    public function status_widget()
    {
        $store = \ActionScheduler::store();
        $counts = $store->action_counts() + $store->extra_action_counts();

        $status_list_items = [];
        foreach ($counts as $status_name => $count) {
            if ($count === 0) {
                continue;
            }

            $status_url = admin_url('tools.php?page=action-scheduler');
            if ('all' !== $status_name) {
                $status_url = add_query_arg(['status' => $status_name], $status_url);
            }
            $status_list_items[] = sprintf('<li><a href="%s">%s</a> ( %d )</li>', esc_url($status_url), esc_html(ucfirst($status_name)), absint($count));
        }

        if (count($status_list_items)) {
            echo '<ul class="subsubsub" style="float:none">';
            echo implode(' | ', $status_list_items); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '</ul>';
        }
    }
}
