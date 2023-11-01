<?php
/**
 * The Admin Handler
 *
 * @package ChoctawNation
 * @subpackage News
 */

namespace ChoctawNation\News;

/** Load the Post Type Builder */
require_once __DIR__ . '/class-post-type-builder.php';

/** Handles the WP Hooks & Filters logic */
class Admin_Handler extends Post_Type_Builder {
	/** Handles the WordPress Admin Columns Hooks & Filters */
	protected function init() {
		$this->add_cpt_to_search_loop();
	}


	/** Add Custom Post Type to WP Search */
	private function add_cpt_to_search_loop() {
		add_action( 'pre_get_posts', array( $this, 'include_choctaw_news_post_type_in_search' ) );
	}

	/**
	 * Callback Function: Adds Custom Post Type to WP Query
	 *
	 * @param \WP_Query $query the curent query
	 */
	public function include_choctaw_news_post_type_in_search( \WP_Query $query ) {
		if ( $query->is_search && ! is_admin() ) {
			$query->set( 'post_type', array( 'choctaw-news' ) );
		}
	}
}
