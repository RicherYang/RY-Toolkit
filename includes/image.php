<?php

class RY_Image
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
        add_filter('image_size_names_choose', [$this, 'add_size_name']);
        add_filter('big_image_size_threshold', [$this, 'change_big_image_size'], 0);
        add_filter('intermediate_image_sizes_advanced', [$this, 'disable_subsize']);
    }

    public function add_size_name($size_names)
    {
        $size_names['medium_large'] = __('Medium large', 'ry-toolkit');
        $size_names['post-thumbnail'] = __('Post thumbnail', 'ry-toolkit');

        return $size_names;
    }

    public function change_big_image_size($threshold): int
    {
        return (int) RY::get_option('big_image_size', $threshold);
    }

    public function disable_subsize($new_sizes)
    {
        $disable_subsize = RY::get_option('disable_subsize', []);
        if (!is_array($disable_subsize)) {
            $disable_subsize = [];
        }

        $new_sizes = array_diff_key($new_sizes, array_fill_keys($disable_subsize, true));

        return $new_sizes;
    }
}
