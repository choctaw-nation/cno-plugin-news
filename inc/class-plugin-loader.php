<?php
/**
 * Plugin Loader
 *
 * @since 1.0
 * @package ChoctawNation
 * @subpackage News
 */

namespace ChoctawNation\News;

/** Load the Parent Class */
require_once __DIR__ . '/plugin-logic/class-admin-handler.php';

/** Inits the Plugin */
final class Plugin_Loader extends Admin_Handler {
	/**
	 * Die if no ACF, else build the plugin.
	 */
	public function __construct() {
		parent::__construct();
		parent::init();
		add_filter( 'template_include', array( $this, 'update_template_loader' ) );
	}


	/**
	 * Filter the WordPress Template Lookup to view the Plugin folder first
	 *
	 * @param string $template the template path
	 */
	public function update_template_loader( string $template ): string {
		$is_single  = is_singular( 'choctaw-news' );
		$is_archive = is_archive( 'choctaw-news' );
		$is_search  = is_search();
		if ( $is_single ) {
			$template = $this->get_the_template( 'single' );
		}
		if ( $is_archive ) {
			$template = $this->get_the_template( 'archive' );
		}
		if ( is_search() ) {
			$template = $this->get_the_search_page( $template );

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
	 * Returns the Plugin Archive.php Path (if exists)
	 */
	private function get_the_search_page(): string|\WP_Error {
		$search_page = dirname( __DIR__, 1 ) . '/templates/search.php';
		global $wp_query;
		// $post_type = $wp_query->
		if ( file_exists( $search_page ) ) {

			return $search_page;
		} else {
			return new \WP_Error( 'Choctaw Events Error', 'Search page not found!' );
		}
	}
}
