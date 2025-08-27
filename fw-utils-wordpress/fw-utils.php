<?php
/**
 * Plugin Name:       FW Utils
 * Plugin URI:        https://fwest.dev
 * Description:       Utility functions and tools for WordPress development.
 * Version:           1.0.0
 * Author:            fw.
 * Author URI:        https://fwest.dev
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       fw-utils
 * Domain Path:       /languages
 */
if(!defined('ABSPATH')) {exit;}

// Plugin constants
define('FW_UTILS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FW_UTILS_PLUGIN_URL', plugin_dir_url(__FILE__));

/** Counter Shortcode */
require_once FW_UTILS_PLUGIN_DIR . 'counter.php';

/** 
 * Feel free to fork 🍴 and add more 😎
 * */