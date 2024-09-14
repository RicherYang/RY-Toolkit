<?php

class RY_Toolkit
{
    public const OPTION_PREFIX = 'RY_Toolkit_';

    protected static $_instance = null;

    private $instance = [];

    public function __get(string $name)
    {
        if (isset($this->instance[$name])) {
            return $this->instance[$name];
        }
    }

    public static function instance(): RY_Toolkit
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
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

        if (wp_doing_cron()) {
            include_once RY_TOOLKIT_PLUGIN_DIR . 'includes/cron.php';
            $this->instance['cron'] = RY_Toolkit_Cron::instance();
        }
    }

    public function ry_pre_init()
    {
        if (has_action('init', 'wp_sitemaps_get_server')) {
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

        if ($is_apache && defined('WP_ROCKET_VERSION')) {
            include_once RY_TOOLKIT_PLUGIN_DIR . 'includes/plugin-wp-rocket.php';
            $this->instance['plugin_wp_rocket'] = RY_Toolkit_Plugin_Wp_Rocket::instance();
        }
    }

    public static function get_option_name(string $option): string
    {
        return self::OPTION_PREFIX . $option;
    }

    public static function get_option(string $option, $default = false): mixed
    {
        return get_option(self::get_option_name($option), $default);
    }

    public static function update_option(string $option, $value, $autoload = null): bool
    {
        return update_option(self::get_option_name($option), $value, $autoload);
    }

    public static function delete_option($option): bool
    {
        return delete_option(self::get_option_name($option));
    }

    public static function plugin_activation(): void
    {
        $defauts = [
            'big_image_size' => 2560,
            'disable_subsize' => [],
            'friendly_filename' => 0,

            'hide_wp_version' => 0,
            'disable_emoji' => 0,
            'disable_shortlink' => 0,
            'disable_oembed' => 0,
            'disable_feed_link' => [],
            'disable_rest_link' => 0,
            'disable_wlw' => 0,

            'disable_xmlrpc' => 1,
            'disable_comment' => 0,
            'disable_ping' => 0,
        ];
        foreach ($defauts as $name => $value) {
            self::update_option($name, self::get_option($name, $value), true);
        }

        $defauts = [
            'sitemap_urls_pre_file' => 2000,
            'sitemap_disable_provider' => [],
            'sitemap_disable_post_type' => [],
            'sitemap_skip_page' => [],
            'sitemap_disable_taxonomy' => [],
        ];
        foreach ($defauts as $name => $value) {
            self::update_option($name, self::get_option($name, $value), false);
        }
    }

    public static function plugin_deactivation(): void {}
}
