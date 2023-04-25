<?php

class RY_Toolkit_Update
{
    public static function update(): void
    {
        $now_version = RY_Toolkit::get_option('version');

        if (RY_TOOLKIT_VERSION == $now_version) {
            return;
        }

        if (version_compare($now_version, '1.1.0', '<')) {
            RY_Toolkit::update_option('version', '1.1.0');
        }
    }
}
