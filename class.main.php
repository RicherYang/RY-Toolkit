<?php

class RY_Toolkit
{
    public static $option_prefix = 'RY_Toolkit_';

    protected static $_instance = null;

    private $instance = [];

    public static function instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    public function __get(string $name)
    {
        if (isset($this->instance[$name])) {
            return $this->instance[$name];
        }
    }

    protected function do_init(): void
    {
        load_plugin_textdomain('ry-toolkit', false, plugin_basename(RY_TOOLKIT_PLUGIN_DIR) . '/languages');

        add_action('init', [$this, 'ry_pre_init'], 9);
        add_action('init', [$this, 'ry_init']);

        if (is_admin()) {
            include_once RY_TOOLKIT_PLUGIN_DIR . 'includes/update.php';
            RY_Toolkit_Update::update();

            include_once RY_TOOLKIT_PLUGIN_DIR . 'admin/admin.php';
            $this->instance['admin'] = RY_Toolkit_Admin::instance();
        }

        if(wp_doing_cron()) {
            include_once RY_TOOLKIT_PLUGIN_DIR . 'includes/cron.php';
            $this->instance['cron'] = RY_Toolkit_Cron::instance();
        }
    }

    public function ry_pre_init()
    {
        if(has_action('init', 'wp_sitemaps_get_server')) {
            include_once RY_TOOLKIT_PLUGIN_DIR . 'includes/sitemaps.php';
            $this->instance['sitemaps'] = RY_Toolkit_Sitemaps::instance();
        }
    }

    public function ry_init(): void
    {
        global $is_apache;

        include_once RY_TOOLKIT_PLUGIN_DIR . 'includes/upload.php';
        $this->instance['upload'] = RY_Toolkit_Upload::instance();

        include_once RY_TOOLKIT_PLUGIN_DIR . 'includes/xmlrpc.php';
        $this->instance['xmlrpc'] = RY_Toolkit_Xmlrpc::instance();

        include_once RY_TOOLKIT_PLUGIN_DIR . 'includes/frontend.php';
        $this->instance['frontend'] = RY_Toolkit_Frontend::instance();

        if($is_apache && defined('WP_ROCKET_VERSION')) {
            include_once RY_TOOLKIT_PLUGIN_DIR . 'includes/plugin-wp-rocket.php';
            $this->instance['plugin_wprocket'] = RY_Toolkit_Plugin_WpRocket::instance();
        }
    }

    public static function get_option_name(string $option): string
    {
        return self::$option_prefix . $option;
    }

    public static function get_option(string $option, $default = false): mixed
    {
        return get_option(self::get_option_name($option), $default);
    }

    public static function add_option(string $option, $value = '', $deprecated = '', $autoload = 'yes'): bool
    {
        return add_option(self::get_option_name($option), $value, $deprecated, $autoload);
    }

    public static function update_option(string $option, $value, $autoload = null): bool
    {
        return update_option(self::get_option_name($option), $value, $autoload);
    }

    public static function plugin_activation(): void {}

    public static function plugin_deactivation(): void {}

    public static function plugin_uninstall(): void {}
}
