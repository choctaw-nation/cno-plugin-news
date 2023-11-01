<?php
/**
 * News CPT
 *
 * @since 1.0
 * @package ChoctawNation
 */

$news_labels = array(
	'name'               => 'News',
	'singular_name'      => 'News',
	'add_new'            => 'Add New',
	'add_new_item'       => 'Add New News',
	'edit_item'          => 'Edit News',
	'new_item'           => 'New News',
	'all_items'          => 'All News',
	'view_item'          => 'View News',
	'search_items'       => 'Search News',
	'not_found'          => 'No News found',
	'not_found_in_trash' => 'No News found in Trash',
	'menu_name'          => 'News',
);

$args = array(
	'labels'        => $news_labels,
	'public'        => true,
	'has_archive'   => true,
	'show_in_rest'  => true,
	'rest_base'     => 'choctaw-news',
	'rewrite'       => array(
		'slug'       => 'news',
		'with-front' => false,
	),
	'supports'      => array(
		'title',
		'thumbnail',
		'revisions',
		'author',
	),
	'menu_icon'     => 'dashicons-media-text',
	'menu_position' => 6,
);
register_post_type( 'choctaw-news', $args );
