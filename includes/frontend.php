<?php

class RY_Toolkit_Frontend
{
    protected static $_instance = null;

    public static function instance(): RY_Toolkit_Frontend
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        if (RY_Toolkit::get_option('hide_wp_version')) {
            add_filter('get_the_generator_html', [$this, 'hide_version']);
            add_filter('get_the_generator_xhtml', [$this, 'hide_version']);
            add_filter('get_the_generator_comment', [$this, 'hide_version']);
            add_filter('get_the_generator_atom', [$this, 'hide_version_rss']);
            add_filter('get_the_generator_rss2', [$this, 'hide_version_rss']);
            add_filter('get_the_generator_rdf', [$this, 'hide_version_rss']);
            add_filter('get_the_generator_export', [$this, 'hide_version_rss']);
        }

        if (RY_Toolkit::get_option('disable_emoji')) {
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('embed_head', 'print_emoji_detection_script');
            remove_action('wp_print_styles', 'print_emoji_styles');

            remove_filter('the_content_feed', 'wp_staticize_emoji');
            remove_filter('comment_text_rss', 'wp_staticize_emoji');
            remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        }

        if (RY_Toolkit::get_option('disable_shortlink')) {
            remove_action('wp_head', 'wp_shortlink_wp_head', 10);
            remove_action('template_redirect', 'wp_shortlink_header', 11);
        }

        if (RY_Toolkit::get_option('disable_oembed')) {
            remove_action('wp_head', 'wp_oembed_add_discovery_links');
        }

        $disable_feed_link = (array) RY_Toolkit::get_option('disable_feed_link', []);
        if ($disable_feed_link['all'] ?? 0) {
            remove_action('wp_head', 'feed_links', 2);
            remove_action('wp_head', 'feed_links_extra', 3);
        } else {
            if ($disable_feed_link['posts'] ?? 0) {
                add_filter('feed_links_show_posts_feed', '__return_false');
            }
            if ($disable_feed_link['comments'] ?? 0) {
                add_filter('feed_links_show_comments_feed', '__return_false');
            }
            if ($disable_feed_link['postcomments'] ?? 0) {
                add_filter('feed_links_extra_show_post_comments_feed', '__return_false');
            }
            if ($disable_feed_link['archive'] ?? 0) {
                add_filter('feed_links_extra_show_post_type_archive_feed', '__return_false');
            }
            if ($disable_feed_link['category'] ?? 0) {
                add_filter('feed_links_extra_show_category_feed', '__return_false');
            }
            if ($disable_feed_link['tag'] ?? 0) {
                add_filter('feed_links_extra_show_tag_feed', '__return_false');
            }
            if ($disable_feed_link['tax'] ?? 0) {
                add_filter('feed_links_extra_show_tax_feed', '__return_false');
            }
            if ($disable_feed_link['author'] ?? 0) {
                add_filter('feed_links_extra_show_author_feed', '__return_false');
            }
        }

        if (RY_Toolkit::get_option('disable_rest_link')) {
            remove_action('wp_head', 'rest_output_link_wp_head', 10);
            remove_action('template_redirect', 'rest_output_link_header', 11);
            remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
        }

        if (RY_Toolkit::get_option('disable_wlw')) {
            remove_action('wp_head', 'wlwmanifest_link');
        }

        if (RY_Toolkit::get_option('disable_comment')) {
            add_filter('comments_open', '__return_false');
        }

        if (RY_Toolkit::get_option('disable_ping')) {
            add_filter('pings_open', '__return_false');
        }
    }

    public function hide_version($meta): string
    {
        return str_replace(get_bloginfo('version'), '', $meta);
    }

    public function hide_version_rss($meta): string
    {
        return str_replace(get_bloginfo_rss('version'), '', $meta);
    }
}
