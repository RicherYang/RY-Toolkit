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

        add_settings_field('medium_large_size', __('Medium large size', 'ry-tools'), [$this, 'show_medium_large_size'], 'media', 'default');
        add_settings_field('big_image_size', __('Max size', 'ry-tools'), [$this, 'show_big_size'], 'media', 'default');
        add_settings_field('disable_subsize_image', __('Disable generated size', 'ry-tools'), [$this, 'show_disable_subsize'], 'media', 'default');
    }

    public function add_allowed_options($allowed_options)
    {
        $allowed_options['media'][] = 'medium_large_size_w';
        $allowed_options['media'][] = 'medium_large_size_h';
        $allowed_options['media'][] = RY::get_option_name('big_image_size');
        $allowed_options['media'][] = RY::get_option_name('disable_subsize');

        return $allowed_options;
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

        foreach ($all_sizes as $size_name => $size_data) {
            $show_size_name = isset($size_names[$size_name]) ? $size_names[$size_name] : $size_name;
            include RY_PLUGIN_DIR . 'admin/html/media/disable-subsize.php';
        }
    }
}
