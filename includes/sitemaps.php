<?php

class RY_Toolkit_Sitemaps
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
        if (is_admin()) {
            return;
        }

        add_filter('wp_sitemaps_add_provider', [$this, 'limit_the_provider'], 10, 2);
        add_action('wp_sitemaps_init', [$this, 'init_change']);
    }

    public function limit_the_provider($provider, $name)
    {
        $sitemap_disable_providers = RY_Toolkit::get_option('sitemap_disable_provider', []);
        if (is_array($sitemap_disable_providers)) {
            if (isset($sitemap_disable_providers[$name])) {
                return new stdClass();
            }
        }
        return $provider;
    }

    public function init_change()
    {
        add_filter('wp_sitemaps_max_urls', [$this, 'change_max_urls']);

        add_filter('wp_sitemaps_post_types', [$this, 'disable_post_type']);
        add_filter('wp_sitemaps_taxonomies', [$this, 'disable_taxonomy']);

        add_filter('wp_sitemaps_posts_entry', [$this, 'add_last_mod'], 10, 2);
    }

    public function change_max_urls($max_urls)
    {
        $urls = (int) RY_Toolkit::get_option('sitemap_urls_pre_file', $max_urls);
        if ($urls > 0) {
            return $urls;
        }

        return $max_urls;
    }

    public function disable_post_type($post_types)
    {
        $disable_post_types = RY_Toolkit::get_option('sitemap_disable_post_type', []);
        if (is_array($disable_post_types)) {
            $post_types = array_diff_key($post_types, $disable_post_types);
        }

        return $post_types;
    }

    public function disable_taxonomy($taxonomies)
    {
        $disable_taxonomies = RY_Toolkit::get_option('sitemap_disable_taxonomy', []);
        if (is_array($disable_taxonomies)) {
            $taxonomies = array_diff_key($taxonomies, $disable_taxonomies);
        }

        return $taxonomies;
    }

    public function add_last_mod($sitemap_entry, $post)
    {
        $sitemap_entry['lastmod'] = get_the_modified_date(DateTimeInterface::W3C, $post);

        return $sitemap_entry;
    }
}
