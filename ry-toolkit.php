<?php

/*
 * Plugin Name: RY Toolkit
 * Plugin URI: https://ry-plugin.com/ry-toolkit
 * Description: Useful tools for more control over your website
 * Version: 2026.7.31.2
 * Requires at least: 6.0
 * Requires PHP: 8.2
 * Author: Richer Yang
 * Author URI: https://richer.tw/
 * License: GPLv3
 */

defined('ABSPATH') or exit;

use RY\Toolkit\Main;

define('RY_TOOLKIT_VERSION', '2026.7.31.2');
define('RY_TOOLKIT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RY_TOOLKIT_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once RY_TOOLKIT_PLUGIN_DIR . 'includes/vendor/autoload.php';

register_activation_hook(__FILE__, [Main::class, 'plugin_activation']);
register_deactivation_hook(__FILE__, [Main::class, 'plugin_deactivation']);

Main::instance();
