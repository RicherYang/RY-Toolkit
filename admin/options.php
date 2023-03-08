<?php

class RY_Admin_Options
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
        add_filter('allowed_options', [$this, 'add_allowed_options']);

        add_filter('sanitize_option_' . RY::get_option_name('big_image_size'), [$this, 'return_absint']);
        add_filter('sanitize_option_' . RY::get_option_name('disable_xmlrpc'), [$this, 'return_absint']);
        add_filter('sanitize_option_' . RY::get_option_name('hide_wp_version'), [$this, 'return_absint']);
        add_filter('sanitize_option_' . RY::get_option_name('disable_emoji'), [$this, 'return_absint']);
        add_filter('sanitize_option_' . RY::get_option_name('disable_shortlink'), [$this, 'return_absint']);
        add_filter('sanitize_option_' . RY::get_option_name('disable_oembed'), [$this, 'return_absint']);
        add_filter('sanitize_option_' . RY::get_option_name('disable_feed_link'), [$this, 'return_array_absint']);
        add_filter('sanitize_option_' . RY::get_option_name('disable_rest_link'), [$this, 'return_absint']);
        add_filter('sanitize_option_' . RY::get_option_name('disable_wlw'), [$this, 'return_absint']);

        add_settings_field('medium_large_size', __('Medium large size', 'ry-toolkit'), [$this, 'show_medium_large_size'], 'media', 'default');
        add_settings_field('big_image_size', __('Max size', 'ry-toolkit'), [$this, 'show_big_size'], 'media', 'default');
        add_settings_field('disable_subsize_image', __('Disable generated size', 'ry-toolkit'), [$this, 'show_disable_subsize'], 'media', 'default');
    }

    public function add_allowed_options($allowed_options)
    {
        $allowed_options['media'][] = 'medium_large_size_w';
        $allowed_options['media'][] = 'medium_large_size_h';
        $allowed_options['media'][] = RY::get_option_name('big_image_size');
        $allowed_options['media'][] = RY::get_option_name('disable_subsize');

        $allowed_options['ry-options'] = [
            RY::get_option_name('disable_xmlrpc'),
            RY::get_option_name('hide_wp_version'),
            RY::get_option_name('disable_emoji'),
            RY::get_option_name('disable_shortlink'),
            RY::get_option_name('disable_oembed'),
            RY::get_option_name('disable_feed_link'),
            RY::get_option_name('disable_rest_link'),
            RY::get_option_name('disable_wlw')
        ];

        return $allowed_options;
    }

    public function return_absint($value)
    {
        return absint($value);
    }

    public function return_array_absint($value)
    {
        return array_map('intval', (array) $value);
    }

    public function show_medium_large_size(): void
    {
        include RY_PLUGIN_DIR . 'admin/html/media/medium-large-size.php';
    }

    public function show_big_size(): void
    {
        include RY_PLUGIN_DIR . 'admin/html/media/big-size.php';
    }

    public function show_disable_subsize(): void
    {
        $disable_subsize = RY::get_option('disable_subsize', []);
        if (!is_array($disable_subsize)) {
            $disable_subsize = [];
        }

        $all_sizes = wp_get_registered_image_subsizes();
        unset($all_sizes['thumbnail']);

        /** This filter is documented in wp-admin/includes/media.php */
        $size_names = apply_filters('image_size_names_choose', [
            'thumbnail' => __('Thumbnail'),
            'medium' => __('Medium'),
            'large' => __('Large'),
            'full' => __('Full Size')
        ]);

        include RY_PLUGIN_DIR . 'admin/html/media/disable-subsize.php';
    }
}
