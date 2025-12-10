<?php

class RY_Toolkit_Upload
{
    protected static $_instance = null;

    public static function instance(): RY_Toolkit_Upload
    {
        if (self::$_instance === null) {
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

        if (RY_Toolkit::get_option('friendly_filename')) {
            add_filter('sanitize_file_name', [$this, 'sanitize_file_name']);
        }
    }

    public function add_size_name($size_names)
    {
        $size_names['medium_large'] = __('Medium large', 'ry-toolkit');
        $size_names['post-thumbnail'] = __('Post thumbnail', 'ry-toolkit');

        $size_names['woocommerce_thumbnail'] = __('WooCommerce thumbnail', 'ry-toolkit');
        $size_names['woocommerce_single'] = __('WooCommerce image', 'ry-toolkit');
        $size_names['woocommerce_gallery_thumbnail'] = __('WooCommerce gallery thumbnail', 'ry-toolkit');

        return $size_names;
    }

    public function change_big_image_size(int $threshold): int
    {
        return (int) RY_Toolkit::get_option('big_image_size', $threshold);
    }

    public function disable_subsize($new_sizes)
    {
        $disable_subsize = RY_Toolkit::get_option('disable_subsize', []);
        if (!is_array($disable_subsize)) {
            $disable_subsize = [];
        }

        return array_diff_key($new_sizes, array_fill_keys($disable_subsize, true));
    }

    public function sanitize_file_name(string $file_name): string
    {
        $parts = explode('.', $file_name);
        if (count($parts) === 1) {
            $extension = '';
        } else {
            $extension = strtolower(array_pop($parts));
            $file_name = implode('.', $parts);
        }

        if ($file_name !== preg_replace('/[^a-z0-9_\-\.]/i', '', $file_name)) {
            $file_name = substr(md5($file_name), 0, 12);
        }

        if ('' !== $extension) {
            $file_name .= '.' . $extension;
        }

        return $file_name;
    }
}
