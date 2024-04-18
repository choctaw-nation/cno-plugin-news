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
	 * Constructor
	 */
	public function __construct() {
		$this->init_acf();
		add_action( 'init', array( $this, 'init_cpt' ) );
		add_filter( 'template_include', array( $this, 'update_template_loader' ) );
		add_action( 'pre_get_posts', array( $this, 'include_choctaw_news_post_type_in_search' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'after_setup_theme', array( $this, 'register_image_sizes' ) );
	}


	/**
	 * Initializes the Plugin
	 *
	 * @return void
	 */
	public function activate(): void {
		$this->init_cpt();
		flush_rewrite_rules();
	}

	/**
	 * Deactivates the Plugin
	 *
	 * @return void
	 */
	public function deactivate(): void {
		$image_sizes = array( 'choctaw-news-preview', 'choctaw-news-single' );
		foreach ( $image_sizes as $size ) {
			remove_image_size( $size );
		}

		$post_types = array( 'choctaw-news', 'choctaw-boilerplates' );
		foreach ( $post_types as $type ) {
			unregister_post_type( $type );
		}

		$scripts = array( 'cno-news' );
		foreach ( $scripts as $script ) {
			wp_deregister_script( $script );
		}
		flush_rewrite_rules();
	}
}
