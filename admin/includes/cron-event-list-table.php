<?php

class RY_Toolkit_Cron_Event_List_Table extends WP_List_Table
{
    protected $all_events;

    protected $search;
    protected $orderby;
    protected $order;
    protected $view_type;

    protected $schedules;

    private $wp_core_hook = [
        'delete_expired_transients',
        'do_pings',
        'importer_scheduled_cleanup',
        'publish_future_post',
        'recovery_mode_clean_expired_keys',
        'update_network_counts',
        'upgrader_scheduled_cleanup',

        'wp_https_detection',
        'wp_maybe_auto_update',
        'wp_privacy_delete_old_export_files',
        'wp_scheduled_auto_draft_delete',
        'wp_scheduled_delete',
        'wp_site_health_scheduled_check',
        'wp_split_shared_term_batch',
        'wp_update_comment_type_batch',
        'wp_update_plugins',
        'wp_update_themes',
        'wp_update_user_counts',
        'wp_version_check',
    ];

    public function __construct()
    {
        parent::__construct([
            'singular' => 'ry_toolkit_cron_event',
            'plural' => 'ry_toolkit_cron_events',
            'ajax' => false
        ]);

        $this->search = $_GET['s'] ?? '';
        $this->orderby = strtolower($_GET['orderby'] ?? '');
        $this->order = ('desc' === strtolower($_GET['order'] ?? '')) ? 'desc' : 'asc';
        $this->view_type = strtolower($_GET['viewtype'] ?? 'all');

        $this->schedules = wp_get_schedules();
        uasort($this->schedules, [$this, 'sort_schedule']);
    }

    public function prepare_items()
    {
        global $wp_filter;

        $wp_events = _get_cron_array();

        $this->all_events = [];
        foreach ($wp_events as $time => $cron) {
            foreach ($cron as $hook => $dings) {
                if (!empty($this->search)) {
                    if (false === strpos($hook, $this->search)) {
                        continue;
                    }
                }
                foreach ($dings as $sig => $data) {
                    $actions = [];
                    if (isset($wp_filter[$hook])) {
                        foreach ($wp_filter[$hook] as $priority => $callbacks) {
                            foreach ($callbacks as $callback) {
                                $actions[] = (object) [
                                    'priority' => $priority,
                                    'callback' => $callback,
                                ];
                            }
                        }
                    }
                    usort($actions, [$this, 'sort_action']);

                    $this->all_events[] = (object) [
                        'hook' => $hook,
                        'sig' => $sig,
                        'time' => (int) $time,
                        'args' => $data['args'],
                        'schedule' => $data['schedule'],
                        'interval' => (int) ($data['interval'] ?? 0),
                        'actions' => $actions,
                    ];
                }
            }
        }
        $all_items = array_filter($this->all_events, [$this, 'filter_view_type']);
        usort($all_items, [$this, 'sort_event']);

        $per_page = $this->get_items_per_page('ry_toolkit_cron_event_per_page');

        $offset = ($this->get_pagenum() - 1) * $per_page;
        $this->items = array_slice($all_items, $offset, $per_page);

        $this->_column_headers = [$this->get_columns(), [], $this->get_sortable_columns()];

        $this->set_pagination_args([
            'total_items' => count($all_items),
            'per_page' => $per_page,
        ]);
    }

    protected function get_table_classes()
    {
        $mode = get_user_setting('posts_list_mode', 'list');
        $mode_class = esc_attr('table-view-' . $mode);

        return ['widefat', 'striped', $mode_class, $this->_args['plural']];
    }

    public function get_views()
    {
        $links = [];
        $basic_url = admin_url('admin.php');
        $url_args = [
            'page' => 'ry-toolkit-cron'
        ];
        $current_view_type = $this->view_type;

        $count = count($this->all_events);
        $links['all'] = array(
            'url' => esc_url(add_query_arg($url_args, $basic_url)),
            'label' => sprintf(
                /* translators: %s: number of events. */
                _n('All <span class="count">(%s)</span>', 'All <span class="count">(%s)</span>', $count, 'ry-toolkit'),
                number_format_i18n($count)
            ),
            'current' => 'all' === $current_view_type,
        );

        $this->view_type = 'noaction';
        $count = count(array_filter($this->all_events, [$this, 'filter_view_type']));
        if (0 < $count) {
            $url_args['viewtype'] = $this->view_type;
            $links[$this->view_type] = array(
                'url' => esc_url(add_query_arg($url_args, $basic_url)),
                'label' => sprintf(
                    /* translators: %s: number of events. */
                    _n('NO action <span class="count">(%s)</span>', 'NO action <span class="count">(%s)</span>', $count, 'ry-toolkit'),
                    number_format_i18n($count)
                ),
                'current' => $this->view_type === $current_view_type,
            );
        }

        foreach($this->schedules as $schedule => $schedule_info) {
            $this->view_type = 'schedule_' . $schedule;
            $count = count(array_filter($this->all_events, [$this, 'filter_view_type']));
            if (0 < $count) {
                $url_args['viewtype'] = $this->view_type;
                $links[$this->view_type] = array(
                    'url' => esc_url(add_query_arg($url_args, $basic_url)),
                    'label' => sprintf(
                        /* translators: %1$s: schedule name %2$s: number of events. */
                        _n('%1$s <span class="count">(%2$s)</span>', '%1$s <span class="count">(%2$s)</span>', $count, 'ry-toolkit'),
                        $schedule_info['display'] ?? $schedule,
                        number_format_i18n($count)
                    ),
                    'current' => $this->view_type === $current_view_type,
                );
            }
        }

        $this->view_type = $current_view_type;

        return $this->get_views_links($links);
    }

    public function get_columns()
    {
        $offset = get_option('gmt_offset', 0);

        $utc_info = '';
        if (!empty($offset)) {
            if (0 <= $offset) {
                $utc_info = '+' . (string) $offset;
            } else {
                $utc_info = (string) $offset;
            }
            $utc_info = str_replace(
                ['.25', '.5', '.75'],
                [':15', ':30', ':45'],
                $utc_info
            );
        }

        return [
            'hook' => __('Hook', 'ry-toolkit'),
            'args' => __('Arguments', 'ry-toolkit'),
            'actions' => __('Actions', 'ry-toolkit'),
            'next' => sprintf(
                /* translators: %s: UTC offset */
                __('Next execution (%s)', 'ry-toolkit'),
                'UTC' . $utc_info
            ),
            'recurrence' => __('Recurrence', 'ry-toolkit')
        ];
    }

    protected function get_sortable_columns()
    {
        return [
            'hook' => ['hook', 'asc'],
            'next' => ['next', 'asc']
        ];
    }

    protected function column_hook($event): void
    {
        echo esc_html($event->hook);

        if (in_array($event->hook, $this->wp_core_hook)) {
            echo '<span class="dashicons dashicons-wordpress" aria-hidden="true" style="font-size:.95rem;padding:5px;height:10px"></span>';
        }
    }

    protected function column_args($event): void
    {
        if (!empty($event->args)) {
            $html = [];
            foreach ($event->args as $key => $value) {
                $html[] .= sprintf(
                    '<code>%s => %s</code>',
                    esc_html(var_export($key, true)),
                    esc_html(var_export($value, true))
                );
            }
            echo implode('<br>', $html);
        }
    }

    protected function column_actions($event): void
    {
        if (!empty($event->actions)) {
            $html = [];
            foreach($event->actions as $action) {
                $html[] = sprintf(
                    '<code>%s => %s</code>',
                    esc_html($action->priority),
                    esc_html($this->get_callback_name($action->callback))
                );
            }
            echo implode('<br>', $html);
        }
    }

    protected function column_next($event): void
    {
        printf(
            '<time datetime="%1$s">%2$s</time>',
            esc_attr(gmdate('c', $event->time)),
            esc_html(date_i18n('Y-m-d H:i:s', $event->time + (int) (get_option('gmt_offset') * HOUR_IN_SECONDS)))
        );

        $diff = $event->time - time();
        if (0 < $diff) {
            echo '<br>' . esc_html(sprintf(
                /* translators: %s: interval text  */
                __('after %s', 'ry-toolkit'),
                $this->get_second_text($diff, 2)
            ));
        }
    }

    protected function column_recurrence($event): void
    {
        if (isset($this->schedules[$event->schedule])) {
            echo esc_html($this->schedules[$event->schedule]['display'] ?? $event->schedule);
        } else {
            echo esc_html($event->schedule);
        }

        if (0 < $event->interval) {
            echo '<br>' . esc_html(sprintf(
                /* translators: %s: interval text  */
                __('every %s', 'ry-toolkit'),
                $this->get_second_text($event->interval)
            ));
        }
    }

    protected function handle_row_actions($event, $column_name, $primary)
    {
        if ($primary !== $column_name) {
            return '';
        }

        $actions = [];

        $url_args = [
            'time' => urlencode($event->time),
            'hook' => urlencode($event->hook),
            'sig' => urlencode($event->sig)
        ];

        $actions['execute'] = sprintf(
            '<a href="%s" aria-label="%s">%s</a>',
            esc_url(RY_Toolkit()->admin->the_action_link('cron', 'execute-cron', $url_args)),
            esc_attr(sprintf(
                /* translators: %s: Event hook name. */
                __('Execute %s', 'ry-toolkit'),
                $event->hook
            )),
            __('Execute now', 'ry-toolkit')
        );

        $actions['delete'] = sprintf(
            '<a href="%s" class="delete" aria-label="%s">%s</a>',
            esc_url(RY_Toolkit()->admin->the_action_link('cron', 'delete-cron', $url_args)),
            esc_attr(sprintf(
                /* translators: %s: Event hook name. */
                __('Delete %s', 'ry-toolkit'),
                $event->hook
            )),
            __('Delete', 'ry-toolkit')
        );

        return $this->row_actions($actions) . parent::handle_row_actions($event, $column_name, $primary);
    }

    private function filter_view_type($event): bool
    {
        $keep = true;
        if ('noaction' === $this->view_type) {
            $keep = empty($event->actions);
        } elseif (0 === strpos($this->view_type, 'schedule_')) {
            $keep = $event->schedule === substr($this->view_type, 9);
        }

        return $keep;
    }

    private function sort_schedule($a, $b): int
    {
        return $a['interval'] <=> $b['interval'];
    }

    private function sort_action($a, $b): int
    {
        return $a->priority <=> $b->priority;
    }

    private function sort_event($a, $b): int
    {
        switch ($this->orderby) {
            case 'hook':
                if ('asc' === $this->order) {
                    $compare = strcasecmp($a->hook, $b->hook);
                } else {
                    $compare = strcasecmp($b->hook, $a->hook);
                }
                break;
            default:
                if ('asc' === $this->order) {
                    $compare = $a->time <=> $b->time;
                } else {
                    $compare = $b->time <=> $a->time;
                }
                break;
        }

        return $compare;
    }

    protected function get_callback_name($callback): string
    {
        if (is_string($callback['function'])) {
            return $callback['function'] . '()';
        }

        if (is_array($callback['function'])) {
            if (is_object($callback['function'][0])) {
                $class = get_class($callback['function'][0]);
                $access = '->';
            } else {
                $class = $callback['function'][0];
                $access = '::';
            }

            return $class . $access . $callback['function'][1] . '()';
        }

        if (is_object($callback['function'])) {
            if (is_a($callback['function'], 'Closure')) {
                return __('Anonymous function', 'ry-toolkit');
            }

            $class = get_class($callback['function']);
            return $class . '->__invoke()';
        }

        return '';
    }

    protected function get_second_text(int $seconds, int $period_limit = 6): string
    {
        static $second_text = [];

        if (!isset($second_text[$seconds])) {
            $second_text[$seconds] = [];
        }
        if (!isset($second_text[$seconds][$period_limit])) {
            $time_periods = [
                [
                    'seconds' => YEAR_IN_SECONDS,
                    /* translators: %d: amount of time */
                    'names' => _n_noop('%d year', '%d years', 'ry-toolkit'),
                ],
                [
                    'seconds' => MONTH_IN_SECONDS,
                    /* translators: %d: amount of time */
                    'names' => _n_noop('%d month', '%d months', 'ry-toolkit'),
                ],
                [
                    'seconds' => DAY_IN_SECONDS,
                    /* translators: %d: amount of time */
                    'names' => _n_noop('%d day', '%d days', 'ry-toolkit'),
                ],
                [
                    'seconds' => HOUR_IN_SECONDS,
                    /* translators: %d: amount of time */
                    'names' => _n_noop('%d hour', '%d hours', 'ry-toolkit'),
                ],
                [
                    'seconds' => MINUTE_IN_SECONDS,
                    /* translators: %d: amount of time */
                    'names' => _n_noop('%d minute', '%d minutes', 'ry-toolkit'),
                ],
                [
                    'seconds' => 1,
                    /* translators: %d: amount of time */
                    'names' => _n_noop('%d second', '%d seconds', 'ry-toolkit'),
                ],
            ];

            $second_text[$seconds][$period_limit] = [];
            $time_remaining = $seconds;
            foreach($time_periods as $time_period) {
                $time_show = floor($time_remaining / $time_period['seconds']);
                if (0 < $time_show) {
                    $second_text[$seconds][$period_limit][] = sprintf(
                        translate_nooped_plural($time_period['names'], $time_show, 'ry-toolkit'),
                        $time_show
                    );
                    $time_remaining -= $time_show * $time_period['seconds'];

                    if ($period_limit <= count($second_text[$seconds][$period_limit])) {
                        break;
                    }
                }
            }

            $second_text[$seconds][$period_limit] = implode(' ', $second_text[$seconds][$period_limit]);
        }

        return $second_text[$seconds][$period_limit];
    }
}
