<?php

abstract class RY_Toolkit_Admin_Page
{
    protected static $page_type;

    protected static $_instance = [];

    abstract public function show_page(): void;

    abstract protected function do_init(): void;

    public static function instance(): RY_Toolkit_Admin_Page
    {
        if (!isset(static::$_instance[static::class])) {
            static::$_instance[static::class] = new static();
            static::$_instance[static::class]->do_init();
        }

        return static::$_instance[static::class];
    }

    public static function set_page_load($hook_suffix): void
    {
        add_action('load-' . $hook_suffix, [static::class, 'process_admin_ui']);
    }

    public static function process_admin_ui(): void
    {
        static::instance();
    }

    public static function pre_show_page(): void
    {
        static::instance()->show_page();
    }

    public static function admin_post_action(): void
    {
        if (static::$page_type === wp_unslash($_GET['ry-toolkit-page'] ?? '')) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            if (wp_verify_nonce(wp_unslash($_REQUEST['_wpnonce'] ?? ''), 'ry-toolkit-action')) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                $action = (string) wp_unslash($_REQUEST['ry-toolkit-action'] ?? ''); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                if ($action === sanitize_key($action)) {
                    $callback = [static::instance(), str_replace('-', '_', $action)];
                    if (is_callable($callback)) {
                        $redirect = call_user_func($callback);
                        if (empty($redirect)) {
                            $redirect = sanitize_url(wp_unslash($_REQUEST['_wp_http_referer'] ?? '')); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                        }
                        wp_safe_redirect($redirect);
                    }
                }
            }
        }
    }
}
