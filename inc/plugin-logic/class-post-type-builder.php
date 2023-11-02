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
		if ( ! class_exists( 'ACF_Image' ) ) {
			require_once dirname( __DIR__ ) . '/acf/objects/class-acf-image.php';
		}
		require_once dirname( __DIR__ ) . '/acf/objects/class-boilerplate.php';
		require_once dirname( __DIR__ ) . '/acf/objects/class-news.php';
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

	public function load_scripts() {
		$asset_file = require_once dirname( __DIR__, 2 ) . '/dist/cno-news.asset.php';
		wp_register_script(
			'cno-news',
			plugin_dir_url( dirname( __DIR__, 1 ) ) . 'dist/cno-news.js',
			array(),
			$asset_file['version'],
			array( 'strategy' => 'async' )
		);
	}
}
