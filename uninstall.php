<?php

defined('ABSPATH') or exit;

use RY\Toolkit\Main;

if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

require_once __DIR__ . '/includes/vendor/autoload.php';

delete_option(Main::get_prefix_name('big_image_size'));
delete_option(Main::get_prefix_name('disable_subsize'));
delete_option(Main::get_prefix_name('friendly_filename'));

delete_option(Main::get_prefix_name('hide_wp_version'));
delete_option(Main::get_prefix_name('disable_emoji'));
delete_option(Main::get_prefix_name('disable_shortlink'));
delete_option(Main::get_prefix_name('disable_oembed'));
delete_option(Main::get_prefix_name('disable_feed_link'));
delete_option(Main::get_prefix_name('disable_rest_link'));
delete_option(Main::get_prefix_name('disable_wlw'));

delete_option(Main::get_prefix_name('disable_xmlrpc'));
delete_option(Main::get_prefix_name('disable_comment'));
delete_option(Main::get_prefix_name('disable_ping'));
delete_option(Main::get_prefix_name('show_thumbnails'));

delete_option(Main::get_prefix_name('sitemap_urls_pre_file'));
delete_option(Main::get_prefix_name('sitemap_disable_provider'));
delete_option(Main::get_prefix_name('sitemap_disable_post_type'));
delete_option(Main::get_prefix_name('sitemap_skip_page'));
delete_option(Main::get_prefix_name('sitemap_disable_taxonomy'));

delete_option(Main::get_prefix_name('wp_rocket_htaccess'));

delete_option(Main::get_prefix_name('version'));
