<?php

class RY_Toolkit_Cron_Event_List_Table extends WP_List_Table
{
    protected $all_events;

    protected $orderby;
    protected $order;

    protected $schedules;

    public function __construct()
    {
        parent::__construct([
            'plural' => 'ry_toolkit_cron_event',
            'singular' => 'ry_toolkit_cron_events',
            'ajax' => false,
            'screen' => 'ry_toolkit_cron_events'
        ]);

        $this->orderby = $_GET['orderby'] ?? '';
        $this->order = 'desc' === strtolower($_GET['order'] ?? '') ? 'desc' : 'desc';

        $this->schedules = wp_get_schedules();
    }

    public function prepare_items()
    {
        global $wp_filter;

        $wp_events = _get_cron_array();

        if (!is_array($wp_events)) {
            $wp_events = [];
        }

        $this->all_events = [];
        foreach ($wp_events as $time => $cron) {
            foreach ($cron as $hook => $dings) {
                foreach ($dings as $sig => $data) {
                    $actions = [];
                    if (isset($wp_filter[$hook])) {
                        $action = $wp_filter[$hook];
                        foreach ($action as $priority => $callbacks) {
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
        usort($this->all_events, [$this, 'sort_event']);

        $per_page = $this->get_items_per_page('ry_toolkit_cron_event_per_page');

        $offset = ($this->get_pagenum() - 1) * $per_page;
        $this->items = array_slice($this->all_events, $offset, $per_page);

        $this->set_pagination_args([
            'total_items' => count($this->all_events),
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
        $view_type = strtolower($_GET['viewtype'] ?? 'all');
        $basic_url = admin_url('admin.php');
        $url_args = [
            'page' => 'ry-toolkit-cron'
        ];

        $count = count($this->all_events);
        $links['all'] = array(
            'url' => esc_url(add_query_arg($url_args, $basic_url)),
            'label' => sprintf(
                /* translators: %s: Number of events. */
                _n('All <span class="count">(%s)</span>', 'All <span class="count">(%s)</span>', $count, 'ry-toolkit'),
                number_format_i18n($count)
            ),
            'current' => 'all' === $view_type,
        );

        $url_args['viewtype'] = 'noaction';
        $count = count(array_filter($this->all_events, function ($event) {
            return empty($event->actions);
        }));
        $links['noaction'] = array(
            'url' => esc_url(add_query_arg($url_args, $basic_url)),
            'label' => sprintf(
                /* translators: %s: Number of events. */
                _n('NO action <span class="count">(%s)</span>', 'NO action <span class="count">(%s)</span>', $count, 'ry-toolkit'),
                number_format_i18n($count)
            ),
            'current' => 'noaction' === $view_type,
        );

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

    protected function column_hook($event)
    {
        echo esc_html($event->hook);
    }

    protected function column_args($event)
    {
        if (!empty($event->args)) {
            echo '<code>';
            foreach($event->args as $idx => $arg) {
                printf('%s => %s', $idx, esc_html($arg));
            }
            echo '</code>';
        }
    }

    protected function column_actions($event)
    {
        if (!empty($event->actions)) {
            foreach($event->actions as $action) {
                printf('<code>%s => %s</code>', esc_html($action->priority), esc_html($this->get_callback_name($action->callback)));
            }
        }
    }

    protected function column_next($event)
    {
        printf(
            '<time datetime="%1$s">%2$s</time>',
            esc_attr(gmdate('c', $event->time)),
            esc_html(date_i18n('Y-m-d H:i:s', $event->time + (int) (get_option('gmt_offset') * HOUR_IN_SECONDS)))
        );

        $diff = $event->time - time();
        if(0 < $diff) {
            /* translators: %s: interval text  */
            echo esc_html(sprintf(__(' (after %s)', 'ry-toolkit'), $this->get_second_text($diff, 2)));
        }
    }

    protected function column_recurrence($event)
    {
        if(isset($this->schedules[$event->schedule])) {
            if(isset($this->schedules[$event->schedule]['display'])) {
                echo esc_html($this->schedules[$event->schedule]['display']);
            } else {
                echo esc_html($event->schedule);
            }
        } else {
            echo esc_html($event->schedule);
        }

        if(0 < $event->interval) {
            /* translators: %s: interval text  */
            echo esc_html(sprintf(__(' (%s)', 'ry-toolkit'), $this->get_second_text($event->interval)));
        }
    }

    protected function sort_action($a, $b): int
    {
        return $a->priority <=> $b->priority;
    }

    protected function sort_event($a, $b): int
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
                return 'Closure';
            }

            $class = get_class($callback['function']);
            return $class . '->__invoke()';
        }

        return '';
    }

    protected function get_second_text($seconds, $period_limit = 6): string
    {
        static $second_text = [];

        if(!isset($second_text[$seconds])) {
            $second_text[$seconds] = [];
        }
        if(!isset($second_text[$seconds][$period_limit])) {
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
                if ($time_show > 0) {
                    $second_text[$seconds][$period_limit][] = sprintf(translate_nooped_plural($time_period['names'], $time_show, 'ry-toolkit'), $time_show);
                    $time_remaining -= $time_show * $time_period['seconds'];

                    if(count($second_text[$seconds][$period_limit]) >= $period_limit) {
                        break;
                    }
                }
            }

            $second_text[$seconds][$period_limit] = implode(' ', $second_text[$seconds][$period_limit]);
        }

        return $second_text[$seconds][$period_limit];
    }
}
