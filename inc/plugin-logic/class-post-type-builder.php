<?php
/**
 * The Post Type Builder
 *
 * @package ChoctawNation
 * @subpackage News
 */

namespace ChoctawNation\News;

/**
 * Builds the Post Type w/ default ACF fields
 */
class Post_Type_Builder {
	/**
	 * Die if no ACF, else build the plugin.
	 */
	public function __construct() {
		if ( ! class_exists( 'ACF' ) ) {
			$plugin_error = new \WP_Error( 'Choctaw News Error', 'ACF not installed!' );
			echo $plugin_error->get_error_messages( 'Choctaw News Error' );
			die;
		}

		add_action( 'init', array( $this, 'init_cpt' ) );
		$this->init_acf();
		// include_once dirname( __DIR__ ) . '/acf/objects/class-choctaw-event.php';
	}

	/** Inits the CPT */
	public function init_cpt() {
		require_once __DIR__ . '/cno-news-cpt.php';
		require_once __DIR__ . '/cno-boilerplates-cpt.php';
	}

	/** Inits the ACF Fields */
	private function init_acf() {
		require_once __DIR__ . '/cno-choctaw-news-custom-fields.php';
	}
}
