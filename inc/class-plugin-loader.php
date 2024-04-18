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
	 * Initializes the Plugin
	 * 
	 * @return void
	 */
	public function activate(): void {
		$this->load_acf_classes();
		add_filter( 'template_include', array( $this, 'update_template_loader' ) );
		add_action( 'pre_get_posts', array( $this, 'include_choctaw_news_post_type_in_search' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'after_setup_theme', array( $this, 'register_image_sizes' ) );
	}

	/** 
	 * Deactivates the Plugin
	 * 
	 * @return void
	 */
	public function deactivate(): void {
		$image_sizes = array('choctaw-news-preview', 'choctaw-news-single');
		foreach ( $image_sizes as $size ) {
			remove_image_size( $size );
		}

		$post_types = array('choctaw-news', 'choctaw-boilerplates');
		foreach ( $post_types as $type ) {
			unregister_post_type( $type );
		}
		
		$scripts = array('cno-news');
		foreach ( $scripts as $script ) {
			wp_deregister_script( $script );
		}
	}

	/** Loads the ACF APIs */
	private function load_acf_classes() {
		if ( ! class_exists( 'ChoctawNation\ACF_Image' ) ) {
			require_once __DIR__ . '/acf/classes/class-acf-image.php';
		}
		require_once __DIR__ . '/acf/classes/class-boilerplate.php';
		require_once __DIR__ . '/acf/classes/class-news.php';
	}

	/**
	 * Filter the WordPress Template Lookup to view the Plugin folder first
	 *
	 * @param string $template the template path
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

	/** Registers "lite-vimeo" script with id of 'cno-news' */
	public function register_scripts() {
		$asset_file = require_once dirname( __DIR__, 1 ) . '/dist/cno-news.asset.php';
		wp_register_script(
			'cno-news',
			plugin_dir_url( __DIR__ ) . 'dist/cno-news.js',
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
