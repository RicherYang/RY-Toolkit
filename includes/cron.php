<?php

class RY_Toolkit_Cron
{
    protected static $_instance = null;

    public static function instance(): RY_Toolkit_Cron
    {
        if (null === self::$_instance) {
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
        if (null === $crons) {
            $limit_time = (int) wp_unslash($_GET['ry-toolkit-limit-event'] ?? 0);
            if (0 < $limit_time) {
                $wp_events = _get_cron_array();

                $crons = [];
                if (isset($wp_events[$limit_time])) {
                    $crons[$limit_time] = $wp_events[$limit_time];
                } else {
                    RY_Toolkit()->admin->add_notice('error', __('Cron event not found.', 'ry-toolkit'));
                }
            }
        }

        return $crons;
    }
}
