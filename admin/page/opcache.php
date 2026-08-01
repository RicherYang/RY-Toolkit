<?php

namespace RY\Toolkit\Admin\Page;

defined('ABSPATH') or exit;

use RY\General\V20260801\AbstractAdminPage;
use RY\General\V20260801\Utils;
use RY\Toolkit\Admin\Admin;

final class Opcache extends AbstractAdminPage
{
    public static function init_menu(): void
    {
        if (function_exists('opcache_get_status')) {
            add_filter('ry-toolkit/menu_list', [__CLASS__, 'add_menu']);
            add_filter('admin_post_ry-toolkit-opcache', [__CLASS__, 'admin_action']);
        }
    }

    public static function add_menu(array $menu_list): array
    {
        $menu_list[] = [
            'name' => __('OPcache', 'ry-toolkit'),
            'slug' => 'ry-toolkit-opcache',
            'function' => [__CLASS__, 'pre_show_page'],
        ];

        return $menu_list;
    }

    protected function do_init(): void {}

    public function output_page(): void
    {
        echo '<div class="wrap"><h1>' . esc_html__('OPcache', 'ry-toolkit') . '</h1>';

        $opcache_status = opcache_get_status(false);
        if ($opcache_status === false) {
            echo esc_html__('OPcache disabled.', 'ry-toolkit');
        } else {
            if ($opcache_status['memory_usage']['used_memory'] < 0) {
                $opcache_status['memory_usage']['used_memory'] = -1;
            }
            $opcache_total = [
                'hit' => $opcache_status['opcache_statistics']['hits'] + $opcache_status['opcache_statistics']['misses'] + $opcache_status['opcache_statistics']['blacklist_misses'],
                'restart' => $opcache_status['opcache_statistics']['oom_restarts'] + $opcache_status['opcache_statistics']['hash_restarts'] + $opcache_status['opcache_statistics']['manual_restarts'],
                'memory' => $opcache_status['memory_usage']['used_memory'] + $opcache_status['memory_usage']['free_memory'] + $opcache_status['memory_usage']['wasted_memory'],
                'buffer' => $opcache_status['interned_strings_usage']['buffer_size'],
            ];

            include __DIR__ . '/html/opcache.php';
        }

        echo '</div>';
    }

    protected function do_admin_action(string $action, string $real_action): void
    {
        if ('ry-toolkit-opcache' !== $action) {
            return;
        }

        if ($real_action !== '' && is_callable([$this, $real_action])) {
            $this->$real_action();
        }

        wp_safe_redirect(admin_url('admin.php?page=ry-toolkit-opcache'));
        exit;
    }

    private function flush_opcache(): void
    {
        check_ajax_referer('flush-opcache', '_ajax_nonce');

        if (function_exists('opcache_invalidate')) {
            try {
                $opcache_status = opcache_get_status(true);
                if ($opcache_status && isset($opcache_status['scripts'])) {
                    $check_abspath = substr(ABSPATH, 0, -1);
                    $start = time();
                    foreach ($opcache_status['scripts'] as $script) {
                        if (str_starts_with($script['full_path'], $check_abspath)) {
                            opcache_invalidate($script['full_path'], true);
                        }

                        if (time() - $start > 0) {
                            wp_safe_redirect(Utils::the_action_link('toolkit-opcache', 'flush-opcache'));
                            exit;
                        }
                    }
                    Admin::instance()->add_notice('success', __('OPcache flushed successfully.', 'ry-toolkit'));
                }
            } catch (\Throwable $th) {
                Admin::instance()->add_notice('success', __('OPcache flush failed.', 'ry-toolkit'));
            }
        } else {
            Admin::instance()->add_notice('success', __('OPcache flush failed.', 'ry-toolkit'));
        }
    }

    private function restart_opcache(): void
    {
        check_ajax_referer('restart-opcache', '_ajax_nonce');

        if (function_exists('opcache_reset') && opcache_reset()) {
            Admin::instance()->add_notice('success', __('OPcache restarted successfully.', 'ry-toolkit'));
        } else {
            Admin::instance()->add_notice('success', __('OPcache restart failed.', 'ry-toolkit'));
        }
    }
}
