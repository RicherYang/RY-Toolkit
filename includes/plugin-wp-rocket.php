<?php

class RY_Toolkit_Plugin_WpRocket
{
    protected static $_instance = null;

    public static function instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        $wp_rocket_htaccess = RY_Toolkit::get_option('wp_rocket_htaccess', []);
        if (is_array($wp_rocket_htaccess)) {
            foreach($wp_rocket_htaccess as $type => $is_disable) {
                if($is_disable) {
                    add_filter('rocket_htaccess_' . $type, '__return_empty_string');
                }
            }
        }
    }
}
