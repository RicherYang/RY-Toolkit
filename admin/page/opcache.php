<?php

final class RY_Admin_Page_Opcache extends RY_Admin_Page
{
    protected static $page_type = 'opcache';

    public static function init_page()
    {
        if (function_exists('opcache_get_status')) {
            add_filter('ry/menu_list', [__CLASS__, 'add_menu']);
            add_filter('ry/admin_action', [__CLASS__, 'admin_action']);
        }
    }

    public static function add_menu($menu_list)
    {
        $menu_list[] = [
            'name' => __('OPcache', 'ry-toolkit'),
            'slug' => 'ry-tools-opcache',
            'function' => [__CLASS__, 'pre_show_page']
        ];

        return $menu_list;
    }

    protected function do_init(): void
    {
    }

    public function show_page(): void
    {
        echo '<div class="wrap"><h1>' . esc_html(__('OPcache', 'ry-toolkit')) . '</h1>';

        $opcache_status = opcache_get_status(false);
        if (false === $opcache_status) {
            echo esc_html(__('OPcache disabled.', 'ry-toolkit'));
        } else {
            $opcache_total = [
                'hit' => $opcache_status['opcache_statistics']['hits'] + $opcache_status['opcache_statistics']['misses'] + $opcache_status['opcache_statistics']['blacklist_misses'],
                'restart' => $opcache_status['opcache_statistics']['oom_restarts'] + $opcache_status['opcache_statistics']['hash_restarts'] + $opcache_status['opcache_statistics']['manual_restarts'],
                'memory' => $opcache_status['memory_usage']['used_memory'] + $opcache_status['memory_usage']['free_memory'] + $opcache_status['memory_usage']['wasted_memory'],
                'buffer' => $opcache_status['interned_strings_usage']['buffer_size']
            ];

            include RY_PLUGIN_DIR . 'admin/page/html/opcache.php';
        }

        echo '</div>';
    }

    protected function flush_opcache(): string
    {
        if (function_exists('opcache_invalidate') && (!ini_get('opcache.restrict_api') || stripos(realpath($_SERVER['SCRIPT_FILENAME']), ini_get('opcache.restrict_api')) === 0)) {
            $opcache_status = opcache_get_status(true);
            if (isset($opcache_status['scripts'])) {
                $check_abspath = substr(ABSPATH, 0, -1);
                foreach ($opcache_status['scripts'] as $script) {
                    if (0 === strpos($script['full_path'], $check_abspath)) {
                        opcache_invalidate($script['full_path'], true);
                    }
                }
                RY()->admin->add_notice('success', __('OPcache flushed successfully.', 'ry-toolkit'));
            }
        } else {
            RY()->admin->add_notice('success', __('OPcache flush failed.', 'ry-toolkit'));
        }

        return '';
    }

    protected function restart_opcache(): string
    {
        if (function_exists('opcache_reset') && opcache_reset()) {
            RY()->admin->add_notice('success', __('OPcache restarted successfully.', 'ry-toolkit'));
        } else {
            RY()->admin->add_notice('success', __('OPcache restart failed.', 'ry-toolkit'));
        }

        return '';
    }
}

RY_Admin_Page_Opcache::init_page();
