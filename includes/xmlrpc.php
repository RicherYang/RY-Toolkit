<?php

class RY_Toolkit_Xmlrpc
{
    protected static $_instance = null;

    public static function instance(): RY_Toolkit_Xmlrpc
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        if (RY_Toolkit::get_option('disable_xmlrpc')) {
            if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) {
                add_filter('xmlrpc_enabled', '__return_false');
            }

            add_filter('wp_headers', [$this, 'remove_x_pingback']);
            remove_action('wp_head', 'rsd_link');
        }
    }

    public function remove_x_pingback($headers)
    {
        unset($headers['X-Pingback']);

        return $headers;
    }
}
