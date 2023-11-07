<?php

class RY_Toolkit_Admin_Options
{
    protected static $_instance = null;

    public static function instance(): RY_Toolkit_Admin_Options
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_filter('allowed_options', [$this, 'add_allowed_options']);

        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('big_image_size'), [$this, 'return_absint']);
        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('disable_subsize'), [$this, 'return_array_nicestring']);
        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('friendly_filename'), [$this, 'return_absint']);

        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('hide_wp_version'), [$this, 'return_absint']);
        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('disable_emoji'), [$this, 'return_absint']);
        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('disable_shortlink'), [$this, 'return_absint']);
        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('disable_oembed'), [$this, 'return_absint']);
        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('disable_feed_link'), [$this, 'return_array_absint']);
        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('disable_rest_link'), [$this, 'return_absint']);
        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('disable_wlw'), [$this, 'return_absint']);

        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('disable_xmlrpc'), [$this, 'return_absint']);
        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('disable_comment'), [$this, 'return_absint']);
        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('disable_ping'), [$this, 'return_absint']);

        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('sitemap_urls_pre_file'), [$this, 'return_absint']);
        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('sitemap_disable_provider'), [$this, 'return_array_nicestring']);
        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('sitemap_add_tag'), [$this, 'return_array_absint']);
        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('sitemap_disable_post_type'), [$this, 'return_array_nicestring']);
        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('sitemap_disable_taxonomy'), [$this, 'return_array_nicestring']);

        add_filter('sanitize_option_' . RY_Toolkit::get_option_name('wp_rocket_htaccess'), [$this, 'return_array_absint']);

        add_settings_field('medium_large_size', __('Medium large size', 'ry-toolkit'), [$this, 'show_medium_large_size'], 'media', 'default');
        add_settings_field('big_image_size', __('Max size', 'ry-toolkit'), [$this, 'show_big_size'], 'media', 'default');
        add_settings_field('disable_subsize_image', __('Disable generated size', 'ry-toolkit'), [$this, 'show_disable_subsize'], 'media', 'default');

        add_settings_field('friendly_filename', __('Friendly filename', 'ry-toolkit'), [$this, 'show_friendly_filename'], 'media', 'uploads');
    }

    public function add_allowed_options($allowed_options)
    {
        $allowed_options['media'][] = 'medium_large_size_w';
        $allowed_options['media'][] = 'medium_large_size_h';
        $allowed_options['media'][] = RY_Toolkit::get_option_name('big_image_size');
        $allowed_options['media'][] = RY_Toolkit::get_option_name('disable_subsize');
        $allowed_options['media'][] = RY_Toolkit::get_option_name('friendly_filename');

        $allowed_options['ry-toolkit-options-frontend'] = [
            RY_Toolkit::get_option_name('hide_wp_version'),
            RY_Toolkit::get_option_name('disable_emoji'),
            RY_Toolkit::get_option_name('disable_shortlink'),
            RY_Toolkit::get_option_name('disable_oembed'),
            RY_Toolkit::get_option_name('disable_feed_link'),
            RY_Toolkit::get_option_name('disable_rest_link'),
            RY_Toolkit::get_option_name('disable_wlw')
        ];

        $allowed_options['ry-toolkit-options-core'] = [
            RY_Toolkit::get_option_name('disable_xmlrpc'),
            RY_Toolkit::get_option_name('disable_comment'),
            RY_Toolkit::get_option_name('disable_ping')
        ];

        $allowed_options['ry-toolkit-options-sitemap'] = [
            RY_Toolkit::get_option_name('sitemap_urls_pre_file'),
            RY_Toolkit::get_option_name('sitemap_disable_provider'),
            RY_Toolkit::get_option_name('sitemap_add_tag'),
            RY_Toolkit::get_option_name('sitemap_disable_post_type'),
            RY_Toolkit::get_option_name('sitemap_disable_taxonomy')
        ];

        $allowed_options['ry-toolkit-options-wp-rocket'] = [
            RY_Toolkit::get_option_name('wp_rocket_htaccess')
        ];

        return $allowed_options;
    }

    public function return_absint($value): int
    {
        return absint($value);
    }

    public function return_array_absint($array)
    {
        $array = (array) $array;
        $values = [];
        foreach ($array as $key => $value) {
            if ((string) $key === sanitize_key($key)) {
                $values[$key] = (int) $value;
            }
        }

        return $values;
    }

    public function return_array_nicestring($array)
    {
        $array = (array) $array;
        $values = [];
        foreach ($array as $key => $value) {
            if ((string) $key === sanitize_key($key)) {
                $values[$key] = sanitize_key($value);
            }
        }

        return $values;
    }

    public function show_medium_large_size(): void
    {
        include RY_TOOLKIT_PLUGIN_DIR . 'admin/html/media/medium-large-size.php';
    }

    public function show_big_size(): void
    {
        include RY_TOOLKIT_PLUGIN_DIR . 'admin/html/media/big-size.php';
    }

    public function show_disable_subsize(): void
    {
        $disable_subsize = RY_Toolkit::get_option('disable_subsize', []);
        if (!is_array($disable_subsize)) {
            $disable_subsize = [];
        }

        $all_sizes = wp_get_registered_image_subsizes();
        unset($all_sizes['thumbnail']);

        /** This filter is documented in wp-admin/includes/media.php */
        $size_names = apply_filters('image_size_names_choose', [
            'thumbnail' => __('Thumbnail', 'ry-toolkit'),
            'medium' => __('Medium', 'ry-toolkit'),
            'large' => __('Large', 'ry-toolkit'),
            'full' => __('Full Size', 'ry-toolkit')
        ]);

        include RY_TOOLKIT_PLUGIN_DIR . 'admin/html/media/disable-subsize.php';
    }

    public function show_friendly_filename(): void
    {
        $friendly_filename = (int) RY_Toolkit::get_option('friendly_filename', 0);

        include RY_TOOLKIT_PLUGIN_DIR . 'admin/html/media/friendly-filename.php';
    }
}
