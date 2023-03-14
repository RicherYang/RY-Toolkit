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
            $action = (string) wp_unslash($_REQUEST['ry-toolkit-action'] ?? '');
            $action_nonce = (string) wp_unslash($_REQUEST['_ry_toolkit_action_nonce'] ?? '');
            if (wp_verify_nonce($action_nonce, $action)) {
                $action_method = str_replace('-', '_', $action);
                if (sanitize_key($action_method) === $action_method) {
                    $callback = [static::instance(), $action_method];
                    if (is_callable($callback)) {
                        $redirect = call_user_func($callback);
                        if (empty($redirect)) {
                            $redirect = sanitize_url(wp_unslash($_REQUEST['_wp_http_referer'] ?? ''));
                        }
                    }
                }
            }
        }

        return $redirect;
    }
}
