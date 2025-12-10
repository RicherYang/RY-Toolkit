<?php

class RY_Toolkit_Cron
{
    protected static $_instance = null;

    public static function instance(): RY_Toolkit_Cron
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
            $limit_time = intval($_GET['ry-toolkit-limit-event'] ?? ''); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            if ($limit_time > 0) {
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
