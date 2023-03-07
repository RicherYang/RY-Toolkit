<?php

class RY_Frontend
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
        if (RY::get_option('hide_wp_version')) {
            add_filter('get_the_generator_html', [$this, 'hide_version']);
            add_filter('get_the_generator_xhtml', [$this, 'hide_version']);
            add_filter('get_the_generator_comment', [$this, 'hide_version']);
            add_filter('get_the_generator_atom', [$this, 'hide_version_rss']);
            add_filter('get_the_generator_rss2', [$this, 'hide_version_rss']);
            add_filter('get_the_generator_rdf', [$this, 'hide_version_rss']);
            add_filter('get_the_generator_export', [$this, 'hide_version_rss']);
        }

        if (RY::get_option('disable_emoji')) {
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('embed_head', 'print_emoji_detection_script');
            remove_action('wp_print_styles', 'print_emoji_styles');

            remove_filter('the_content_feed', 'wp_staticize_emoji');
            remove_filter('comment_text_rss', 'wp_staticize_emoji');
            remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        }

        if (RY::get_option('disable_shortlink')) {
            remove_action('wp_head', 'wp_shortlink_wp_head', 10);
            remove_action('template_redirect', 'wp_shortlink_header', 11);
        }

        if (RY::get_option('disable_oembed')) {
            remove_action('wp_head', 'wp_oembed_add_discovery_links');
        }

        if (RY::get_option('disable_feed_link')) {
            remove_action('wp_head', 'feed_links', 2);
            remove_action('wp_head', 'feed_links_extra', 3);
        }

        if (RY::get_option('disable_rest_link')) {
            remove_action('wp_head', 'rest_output_link_wp_head', 10);
            remove_action('template_redirect', 'rest_output_link_header', 11);
            remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
        }

        if (RY::get_option('disable_wlw')) {
            remove_action('wp_head', 'wlwmanifest_link');
        }
    }

    public function hide_version($meta)
    {
        return str_replace(get_bloginfo('version'), '', $meta);
    }

    public function hide_version_rss($meta)
    {
        return str_replace(get_bloginfo_rss('version'), '', $meta);
    }
}
