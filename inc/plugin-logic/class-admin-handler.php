<?php
/**
 * The Admin Handler
 *
 * @package ChoctawNation
 * @subpackage News
 */

namespace ChoctawNation\News;

/** Handles the WP Hooks & Filters logic */
class Admin_Handler {
	/**
	 * Inits the CPT
	 * 
	 * @return void
	 */
	public function init_cpt():void {
		require_once dirname( __DIR__, 1 ) . '/cpts/cno-news-cpt.php';
		require_once dirname( __DIR__, 1 ) . '/cpts/cno-boilerplates-cpt.php';
	}

	/**
	 * Inits the ACF Fields
	 * 
	 * @return void
	 */
	protected function init_acf():void {
		if ( ! class_exists( 'ACF' ) ) {
			$plugin_error = new \WP_Error( 'Choctaw News Error', 'ACF not installed!' );
			echo $plugin_error->get_error_messages( 'Choctaw News Error' );
			die;
		}
		require_once dirname( __DIR__, 1 ) . '/acf/choctaw-news-custom-fields.php';
		require_once dirname( __DIR__, 1 ) . '/acf/classes/class-boilerplate.php';
		require_once dirname( __DIR__, 1 ) . '/acf/classes/class-news.php';
		if ( ! class_exists( 'ChoctawNation\ACF_Image' ) ) {
			require_once dirname( __DIR__, 1 ) . '/acf/classes/class-acf-image.php';
		}
	}
	
	/**
	 * Callback Function: Adds Custom Post Type to WP Query
	 *
	 * @param \WP_Query $query the current query
	 * @return void
	 */
	public function include_choctaw_news_post_type_in_search( \WP_Query $query ):void {
		if ( $query->is_search && ! is_admin() ) {
			$query->set( 'post_type', array( 'choctaw-news' ) );
		}
	}

	/**
	 * Filter the WordPress Template Lookup to view the Plugin folder first
	 *
	 * @param string $template the template path
	 * @return string the template path
	 */
	public function update_template_loader( string $template ): string {
		$is_single  = is_singular( 'choctaw-news' );
		$is_archive = is_post_type_archive( 'choctaw-news' );
		if ( $is_single ) {
			$template = $this->get_the_template( 'single' );
		}
		if ( $is_archive ) {
			$template = $this->get_the_template( 'archive' );
		}
		return $template;
	}

	/** Gets the appropriate template
	 *
	 * @param string $type "single" or "archive"
	 * @return string|WP_Error the template path
	 */
	private function get_the_template( string $type ): string|\WP_Error {
		$template_override = get_stylesheet_directory() . "/templates/{$type}-choctaw-news.php";
		$template          = file_exists( $template_override ) ? $template_override : dirname( __DIR__, 1 ) . "/templates/{$type}-choctaw-news.php";
		if ( file_exists( $template ) ) {
			return $template;
		} else {
			return new \WP_Error( 'Choctaw News Error', "{$type} template not found!" );
		}
	}

	/**
	 * Registers "lite-vimeo" script with id of 'cno-news'
	 * 
	 * @return void
	 */
	public function register_scripts(): void {
		$asset_file = require_once dirname( __DIR__, 2 ) . '/dist/cno-news.asset.php';
		wp_register_script(
			'cno-news',
			plugin_dir_url( dirname( __DIR__, 1 ) ) . 'dist/cno-news.js',
			array(),
			$asset_file['version'],
			array( 'strategy' => 'async' )
		);
	}

	/** Registers the standard image sizes */
	public function register_image_sizes() {
		add_image_size( 'choctaw-news-preview', 1392, 784 );
		add_image_size( 'choctaw-news-single', 2592, 1458 );
	}
}
