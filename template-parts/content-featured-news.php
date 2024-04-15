<?php
/**
 * Featured News
 * This snippet is meant to be copied and placed inside a theme's `template-parts` directory. It must have an associated template-part to display the post previews themselves.
 *
 * @since 1.1
 * @package ChoctawNation
 * @subpackage News
 */

/**
 * An array of Post IDs to exclude from the query (because they are ) Passed via `get_template_parts` array (3rd arg).
 *
 * @var int[] $video_ids
 */
$video_ids = empty( $args['video_ids'] ) || ! is_array( $args['video_ids'] ) ? null : $args['video_ids'];

/**
 * The number of posts to show
 *
 * @var int $posts_per_page
 */
$posts_per_page = 8;

/**
 * The date constraint to limit the SQL query to (makes the query more performant)
 *
 * @var int $date_constraint
 */
$date_constraint = strtotime( '-1 month' );

$date_query = array(
	'after'     => array(
		'year'  => gmdate( 'Y', $date_constraint ),
		'month' => gmdate( 'n', $date_constraint ),
	),
	'inclusive' => true,
);

$base_query = array(
	'post_type'      => array( 'news' ),
	'posts_per_page' => $posts_per_page,
	'order'          => 'DESC',
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'no_found_rows'  => true,
);

if ( $video_ids ) {
	$base_query['post__not_in'] = $video_ids;
}

$featured_posts_args['meta_query'] = array(
	array(
		'key'     => 'featured_post',
		'value'   => 1,
		'compare' => '=',
	),
);
?>

<section id="news" class='container mb-5'>
	<header class="row mb-5">
		<div class="col">
			<h2>News</h2>
		</div>
	</header>
	<ul class="row py-3 list-unstyled m-0">
		<?php
		$featured_posts_query = new WP_Query( $featured_posts_args );
		$featured_posts_count = 0;
		if ( $featured_posts_query->have_posts() ) {
			$featured_posts_count = count( $featured_posts_query->posts );
			while ( $featured_posts_query->have_posts() ) {
				$featured_posts_query->the_post();
				echo '<li class="col-12 mb-4">';
				$template_override = get_template_part( 'template-parts/content', 'featured-news' );
				if ( false === $template_override ) {
					require dirname( __DIR__ ) . '/template-parts/content-featured-news.php';
				}
				echo '</li>';
			}
		}
		wp_reset_postdata();

		$remaining_posts              = $posts_per_page - $featured_posts_count;
		$can_get_more_posts           = $remaining_posts > 0;
		$more_posts                   = $base_query;
		$more_posts['posts_per_page'] = $remaining_posts;
		$more_posts['meta_query']     = array(
			'relation' => 'OR',
			array(
				'key'     => 'featured_post',
				'value'   => 0,
				'compare' => '=',
			),
			array(
				'key'     => 'featured_post',
				'compare' => 'NOT EXISTS',
			),
		);

		if ( $can_get_more_posts ) {
			$query = new WP_Query( $more_posts );
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					echo '<li class="col-12 mb-4">';
					$template_override = get_template_part( 'template-parts/content', 'news-preview' );
					if ( false === $template_override ) {
						require dirname( __DIR__ ) . '/template-parts/content-news-preview.php';
					}
					echo '</li>';
				}
			}
			wp_reset_postdata();
		}
		?>
	</ul>

	<div class="row py-4 position-relative">
		<a href="<?php echo get_post_type_archive_link( 'choctaw-news' ); ?>" class="btn btn-primary">More News <i class="fa fa-angle-right" aria-hidden="true"></i>
		</a>
	</div>
</section>