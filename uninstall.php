<?php

if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

require_once __DIR__ . '/includes/main.php';

delete_option(RY_Toolkit::get_option_name('big_image_size'));
delete_option(RY_Toolkit::get_option_name('disable_subsize'));
delete_option(RY_Toolkit::get_option_name('friendly_filename'));

delete_option(RY_Toolkit::get_option_name('hide_wp_version'));
delete_option(RY_Toolkit::get_option_name('disable_emoji'));
delete_option(RY_Toolkit::get_option_name('disable_shortlink'));
delete_option(RY_Toolkit::get_option_name('disable_oembed'));
delete_option(RY_Toolkit::get_option_name('disable_feed_link'));
delete_option(RY_Toolkit::get_option_name('disable_rest_link'));
delete_option(RY_Toolkit::get_option_name('disable_wlw'));

delete_option(RY_Toolkit::get_option_name('disable_xmlrpc'));
delete_option(RY_Toolkit::get_option_name('disable_comment'));
delete_option(RY_Toolkit::get_option_name('disable_ping'));
delete_option(RY_Toolkit::get_option_name('show_thumbnails'));

delete_option(RY_Toolkit::get_option_name('sitemap_urls_pre_file'));
delete_option(RY_Toolkit::get_option_name('sitemap_disable_provider'));
delete_option(RY_Toolkit::get_option_name('sitemap_disable_post_type'));
delete_option(RY_Toolkit::get_option_name('sitemap_skip_page'));
delete_option(RY_Toolkit::get_option_name('sitemap_disable_taxonomy'));

delete_option(RY_Toolkit::get_option_name('wp_rocket_htaccess'));

delete_option(RY_Toolkit::get_option_name('version'));
