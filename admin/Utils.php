<?php

namespace RY\Toolkit\Admin;

defined('ABSPATH') or exit;

use RY\Toolkit\Main;

final class Utils
{
    public static function the_bool_option_checkbox(string $option, string $label, string $sub_name = ''): void
    {
        $id = Main::get_prefix_name($option);
        $name = $id;
        if (empty($sub_name)) {
            $value = Main::get_option($option) ?? 0;
        } else {
            $id .= '-' . $sub_name;
            $name .= '[' . $sub_name . ']';
            $value = Main::get_option($option)[$sub_name] ?? 0;
        }
        printf(
            '<label for="%1$s"><input type="checkbox" id="%1$s" name="%2$s" value="1" %3$s /> %4$s</label>',
            esc_attr($id),
            esc_attr($name),
            checked('1', $value, false),
            esc_html($label)
        );
    }
}
