<?php

namespace RY\Toolkit;

defined('ABSPATH') or exit;

use RY\Toolkit\Admin\Admin;

final class Cron
{
    private static ?self $_instance = null;

    public static function instance(): Cron
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_filter('pre_get_ready_cron_jobs', [$this, 'limit_ready_cron_jobs']);
    }

    public function limit_ready_cron_jobs($crons)
    {
        if ($crons === null) {
            $limit_time = intval($_GET['ry-toolkit-limit-event'] ?? '');
            if ($limit_time > 0) {
                $wp_events = _get_cron_array();

                $crons = [];
                if (isset($wp_events[$limit_time])) {
                    $crons[$limit_time] = $wp_events[$limit_time];
                } else {
                    Admin::instance()->add_notice('error', __('Cron event not found.', 'ry-toolkit'));
                }
            }
        }

        return $crons;
    }
}
