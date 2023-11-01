<?php
/**
 * News CPT
 *
 * @since 1.0
 * @package ChoctawNation
 */

$news_labels = array(
	'name'               => 'Boilerplates',
	'singular_name'      => 'Boilerplate',
	'add_new'            => 'Add New',
	'add_new_item'       => 'Add New Boilerplate',
	'edit_item'          => 'Edit Boilerplate',
	'new_item'           => 'New Boilerplate',
	'all_items'          => 'All Boilerplates',
	'view_item'          => 'View Boilerplates',
	'search_items'       => 'Search Boilerplates',
	'not_found'          => 'No Boilerplates found',
	'not_found_in_trash' => 'No Boilerplates found in Trash',
	'menu_name'          => 'Boilerplates',
);

$args = array(
	'labels'        => $news_labels,
	'public'        => true,
	'has_archive'   => true,
	'show_in_rest'  => true,
	'rest_base'     => 'choctaw-boilerplates',
	'supports'      => array(
		'title',
		'thumbnail',
		'revisions',
		'author',
	),
	'menu_icon'     => 'dashicons-format-aside',
	'menu_position' => 7,
);
register_post_type( 'choctaw-boilerplates', $args );
