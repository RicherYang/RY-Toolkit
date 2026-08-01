<?php

namespace RY\Toolkit;

defined('ABSPATH') or exit;

use RY\General\V20260801\AbstractBasic;
use RY\Toolkit\Admin\Admin;
use RY\Toolkit\Third\WpRocket;

final class Main extends AbstractBasic
{
    public const PREFIX = 'RY_Toolkit_';

    private static ?self $_instance = null;

    public static function instance(): Main
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        if (is_admin()) {
            Update::update();
        }

        add_action('init', [$this, 'do_wp_init'], 9);
    }

    public function do_wp_init(): void
    {
        global $is_apache;

        if (has_action('init', 'wp_sitemaps_get_server')) {
            Sitemaps::instance();
        }

        Cron::instance();
        Upload::instance();
        Xmlrpc::instance();
        Frontend::instance();

        if ($is_apache && defined('WP_ROCKET_VERSION')) {
            WpRocket::instance();
        }

        if (is_admin()) {
            Admin::instance();
        }
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
            'show_thumbnails' => [],

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
