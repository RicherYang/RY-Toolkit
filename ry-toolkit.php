<?php
/*
 * Plugin Name: RY Toolkit
 * Plugin URI: https://ry-plugin.com/ry-toolkit
 * Description: Useful tools for more control over you website
 * Version: 1.0.0
 * Requires at least: 5.6
 * Requires PHP: 7.4
 * Author: Richer Yang
 * Author URI: https://richer.tw/
 * License: GPLv3 or later
 */

function_exists('plugin_dir_url') or exit('No direct script access allowed');

define('RY_VERSION', '1.0.0');
define('RY_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RY_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once RY_PLUGIN_DIR . 'class.main.php';

register_activation_hook(__FILE__, ['RY', 'plugin_activation']);
register_deactivation_hook(__FILE__, ['RY', 'plugin_deactivation']);

function RY()
{
    return RY::instance();
}

RY();
