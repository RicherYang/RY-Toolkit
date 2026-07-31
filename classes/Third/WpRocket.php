<?php

namespace RY\Toolkit\Third;

defined('ABSPATH') or exit;

use RY\Toolkit\Main;

final class WpRocket
{
    private static ?self $_instance = null;

    public static function instance(): WpRocket
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        $wp_rocket_htaccess = Main::get_option('wp_rocket_htaccess', []);
        if (is_array($wp_rocket_htaccess)) {
            foreach ($wp_rocket_htaccess as $type => $is_disable) {
                if ($is_disable) {
                    add_filter('rocket_htaccess_' . $type, '__return_empty_string');
                }
            }
        }
    }
}
