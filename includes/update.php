<?php

class RY_update
{
    public static function update(): void
    {
        $now_version = RY::get_option('version');

        if (RY_VERSION == $now_version) {
            return;
        }

        if (version_compare($now_version, '0.0.1', '<')) {
            RY::update_option('version', '0.0.1');
        }
    }
}
