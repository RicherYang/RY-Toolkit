<?php

abstract class RY_Toolkit_Admin_Page
{
    protected static $page_type;
    protected static $_instance = [];

    abstract public function show_page(): void;

    abstract protected function do_init(): void;

    public static function instance()
    {
        if (!isset(static::$_instance[static::class])) {
            static::$_instance[static::class] = new static();
            static::$_instance[static::class]->do_init();
        }

        return static::$_instance[static::class];
    }

    public static function pre_show_page(): void
    {
        static::instance()->show_page();
    }

    public static function admin_action(string $redirect): string
    {
        if (!empty($redirect)) {
            return $redirect;
        }

        if (static::$page_type === wp_unslash($_GET['ry-toolkit-page'] ?? '')) {
            $action = wp_unslash($_REQUEST['ry-toolkit-action'] ?? '');
            if (wp_verify_nonce($_REQUEST['_ry_toolkit_action_nonce'] ?? '', $action)) {
                $action_method = filter_var($action, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK);
                $action_method = str_replace('-', '_', $action_method);
                if (method_exists(static::class, $action_method)) {
                    $redirect = static::instance()->$action_method();
                    if (empty($redirect)) {
                        $redirect = sanitize_url(wp_unslash($_REQUEST['_wp_http_referer'] ?? ''));
                    }
                }
            }
        }

        return $redirect;
    }
}
