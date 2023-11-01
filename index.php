<?php
/**
 * Plugin Name: Choctaw News Plugin
 * Description: Choctaw News Plugin creates the News Post Type, Boilerplate Post Type and links up the ACF Fields.
 * Version: 1.0
 * Author: Choctaw Nation of Oklahoma
 * Author URI: https://www.choctawnation.com
 * Text Domain: cno
 * License: GPLv3 or later
 *
 * @package ChoctawNation
 */

use ChoctawNation\News\Plugin_Loader;

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

require_once __DIR__ . '/inc/class-plugin-loader.php';
$plugin_loader = new Plugin_Loader();
