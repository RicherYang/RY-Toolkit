<?php
/*
 * Plugin Name: RY Toolkit
 * Plugin URI: https://ry-plugin.com/ry-toolkit
 * Description: Useful tools for more control over you website
 * Version: 1.0.2
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Richer Yang
 * Author URI: https://richer.tw/
 * License: GPLv3 or later
 */

function_exists('plugin_dir_url') or exit('No direct script access allowed');

define('RY_TOOLKIT_VERSION', '1.0.2');
define('RY_TOOLKIT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RY_TOOLKIT_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once RY_TOOLKIT_PLUGIN_DIR . 'class.main.php';

register_activation_hook(__FILE__, ['RY_Toolkit', 'plugin_activation']);
register_deactivation_hook(__FILE__, ['RY_Toolkit', 'plugin_deactivation']);

function RY_Toolkit()
{
    return RY_Toolkit::instance();
}

RY_Toolkit();
