<?php

final class RY_Toolkit_Admin_Page_Cron extends RY_Toolkit_Admin_Page
{
    protected static $page_type = 'cron';

    protected $limit_event;

    public static function init_page(): void
    {
        add_filter('ry-toolkit/menu_list', [__CLASS__, 'add_menu']);
        add_action('ry-toolkit/admin_action', [__CLASS__, 'admin_action']);
        add_action('ry-toolkit/add_page-ry-toolkit-cron', [__CLASS__, 'set_page_load']);
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

    public static function set_page_load($hook_suffix)
    {
        add_action('load-' . $hook_suffix, [__CLASS__, 'process_admin_ui']);
    }

    public static function process_admin_ui()
    {
        self::instance();
    }

    protected function do_init(): void
    {
        include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
        include_once RY_TOOLKIT_PLUGIN_DIR . 'admin/includes/cron-event-list-table.php';

        add_screen_option('per_page', [
            'default' => 15,
            'option' => 'ry_toolkit_cron_event_per_page'
        ]);

        add_filter('set_screen_option_ry_toolkit_cron_event_per_page', [$this, 'set_screen_option'], 10, 3);
        set_screen_options();

        $screen = get_current_screen();
        if ($screen) {
            $screen->add_help_tab([
                'id' => 'screen-content',
                'title' => esc_html__('Screen Content', 'ry-toolkit'),
                'content' =>
                    '<p>' . esc_html__('You can customize the display of this screen&#8217;s contents in a number of ways:', 'ry-toolkit') . '</p>' .
                    '<ul>' .
                        '<li>' . esc_html__('You can decide how many events to list per screen using the Screen Options tab.', 'ry-toolkit') . '</li>' .
                    '</ul>',
            ]);
            $screen->add_help_tab([
                'id' => 'action-links',
                'title' => esc_html__('Available Actions', 'ry-toolkit'),
                'content' =>
                    '<p>' . esc_html__('Hovering over a row in the events list will display action links that allow you to manage event. You can perform the following actions:', 'ry-toolkit') . '</p>' .
                    '<ul>' .
                        '<li><strong>' . esc_html__('Execute now') . '</strong> ' . esc_html__('execute event, the next execution time of schedule event will be calculated based on the current time.', 'ry-toolkit') . '</li>' .
                        '<li><strong>' . esc_html__('Delete') . '</strong> ' . esc_html__('unschedule event, some event will be automatically create after you unscheduled.', 'ry-toolkit') . '</li>' .
                    '</ul>',
            ]);
        }
    }

    public function show_page(): void
    {
        $list_table = new RY_Toolkit_Cron_Event_List_Table();
        $list_table->prepare_items();

        echo '<div class="wrap"><h1>' . esc_html__('Cron', 'ry-toolkit') . '</h1>';

        include RY_TOOLKIT_PLUGIN_DIR . 'admin/page/html/cron-event.php';

        echo '</div>';
    }

    public function set_screen_option($status, $option, $value)
    {
        return $value;
    }

    protected function execute_cron(): string
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You are not allowed to do that.', 'ry-toolkit'), 401);
        }

        $time = wp_unslash($_GET['time'] ?? '');
        $hook = wp_unslash($_GET['hook'] ?? '');
        $sig = wp_unslash($_GET['sig'] ?? '');

        $wp_events = _get_cron_array();

        if (isset($wp_events[$time][$hook][$sig])) {
            ksort($wp_events);
            $this->limit_event = array_key_first($wp_events) - MINUTE_IN_SECONDS * 5;

            $wp_events[$this->limit_event] = [
                $hook => [
                    $sig => $wp_events[$time][$hook][$sig]
                ]
            ];
            unset($wp_events[$time][$hook][$sig]);
            if (empty($wp_events[$time][$hook])) {
                unset($wp_events[$time][$hook]);
                if (empty($wp_events[$time])) {
                    unset($wp_events[$time]);
                }
            }
            ksort($wp_events);
            $set_cron = _set_cron_array($wp_events);
            if (true === $set_cron) {
                delete_transient('doing_cron');

                add_filter('cron_request', function ($cron_request_array) {
                    $cron_request_array['url'] = add_query_arg('ry-toolkit-limit-event', $this->limit_event, $cron_request_array['url']);
                    $cron_request_array['args']['timeout'] = 15;
                    $cron_request_array['args']['blocking'] = true;

                    return $cron_request_array;
                });

                $result = spawn_cron();
                if($result) {
                    RY_Toolkit()->admin->add_notice('success', sprintf(
                        /* translators: Event hook name. */
                        __('Executed event "%s".', 'ry-toolkit'),
                        $hook
                    ));
                } else {
                    RY_Toolkit()->admin->add_notice('error', __('Failed to start cron job.', 'ry-toolkit'));
                }
            } else {
                RY_Toolkit()->admin->add_notice('error', sprintf(
                    /* translators: Event hook name. */
                    __('Failed to schedule the cron event "%s".', 'ry-toolkit'),
                    $hook
                ));
            }
        } else {
            RY_Toolkit()->admin->add_notice('error', sprintf(
                /* translators: Event hook name. */
                __('Cron event "%s" not found.', 'ry-toolkit'),
                $hook
            ));
        }

        return admin_url('admin.php?page=ry-toolkit-cron');
    }


    protected function delete_cron(): string
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You are not allowed to do that.', 'ry-toolkit'), 401);
        }

        $time = wp_unslash($_GET['time'] ?? '');
        $hook = wp_unslash($_GET['hook'] ?? '');
        $sig = wp_unslash($_GET['sig'] ?? '');


        $wp_events = _get_cron_array();
        if (isset($wp_events[$time][$hook][$sig])) {
            if (wp_unschedule_event($time, $hook, $wp_events[$time][$hook][$sig]['args'])) {
                RY_Toolkit()->admin->add_notice('success', sprintf(
                    /* translators: Event hook name. */
                    __('Cron event "%s" has been deleted.', 'ry-toolkit'),
                    $hook
                ));
            } else {
                RY_Toolkit()->admin->add_notice('error', sprintf(
                    /* translators: Event hook name. */
                    __('Failed to the delete the cron event "%s".', 'ry-toolkit'),
                    $hook
                ));
            }
        } else {
            RY_Toolkit()->admin->add_notice('error', sprintf(
                /* translators: Event hook name. */
                __('Cron event "%s" not found.', 'ry-toolkit'),
                $hook
            ));
        }

        return admin_url('admin.php?page=ry-toolkit-cron');
    }
}

RY_Toolkit_Admin_Page_Cron::init_page();
