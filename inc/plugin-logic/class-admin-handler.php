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
