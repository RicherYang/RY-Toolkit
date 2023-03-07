<?php

class RY
{
    public static $option_prefix = 'RY_';

    protected static $_instance = null;

    private $instance = [];

    public static function instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    public function __get(string $name)
    {
        if (isset($this->instance[$name])) {
            return $this->instance[$name];
        }
    }

    protected function do_init(): void
    {
        load_plugin_textdomain('ry-tools', false, plugin_basename(RY_PLUGIN_DIR) . '/languages');

        add_action('init', [$this, 'ry_init']);

        if (is_admin()) {
            include_once RY_PLUGIN_DIR . 'includes/update.php';
            RY_update::update();

            include_once RY_PLUGIN_DIR . 'admin/admin.php';
            $this->instance['admin'] = RY_Admin::instance();
        }
    }

    public function ry_init(): void
    {
        include_once RY_PLUGIN_DIR . 'includes/image.php';
        $this->instance['image'] = RY_Image::instance();
    }

    public static function get_option_name(string $option): string
    {
        return self::$option_prefix . $option;
    }

    public static function get_option(string $option, $default = false): mixed
    {
        return get_option(self::get_option_name($option), $default);
    }

    public static function add_option(string $option, $value = '', $deprecated = '', $autoload = 'yes'): bool
    {
        return add_option(self::get_option_name($option), $value, $deprecated, $autoload);
    }

    public static function update_option(string $option, $value, $autoload = null): bool
    {
        return update_option(self::get_option_name($option), $value, $autoload);
    }

    public static function plugin_activation(): void
    {
        RY::add_option('big_image_size', 2560, '', 'no');
        RY::add_option('disable_subsize', [], '', 'no');
    }

    public static function plugin_deactivation(): void
    {
    }

    public static function plugin_uninstall(): void
    {
    }
}
