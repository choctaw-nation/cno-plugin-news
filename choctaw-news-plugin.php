<?php
/**
 * Plugin Name: Choctaw News Plugin
 * Description: Choctaw News Plugin creates the News Post Type, Boilerplate Post Type and links up the ACF Fields.
 * Plugin URI: https://github.com/choctaw-nation/news-plugin
 * Version: 1.1.8
 * Author: Choctaw Nation of Oklahoma
 * Author URI: https://www.choctawnation.com
 * Text Domain: cno
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Requires PHP: 8.1
 * Requires at least: 6.0
 * Requires Plugins: advanced-custom-fields-pro
 *
 * @package ChoctawNation
 * @subpackage News
 */

use ChoctawNation\News\Plugin_Loader;

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

require_once __DIR__ . '/inc/class-plugin-loader.php';
$plugin_loader = new Plugin_Loader();

register_activation_hook( __FILE__, array( $plugin_loader, 'activate' ) );
register_deactivation_hook( __FILE__, array( $plugin_loader, 'deactivate' ) );
