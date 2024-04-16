<?php
/**
 * The News Article Preview
 *
 * @package ChoctawNation
 * @subpackage News
 * @since 1.0
 */

use ChoctawNation\News\News;

$news = new News( $post->ID );
?>
<li class="row my-4">
	<div class="col-lg-5">
		<a href="<?php the_permalink(); ?>" class="ratio ratio-16x9">
			<?php
			$news->the_photo(
				'choctaw-news-preview',
				array(
					'class' => 'object-fit-cover',
				)
			);
			?>
		</a>
	</div>
	<div class="col d-flex flex-column">
		<a href="<?php the_permalink(); ?>">
			<?php the_title( '<h2>', '</h2>' ); ?>
		</a>
		<?php $news->the_excerpt(); ?>
		<a href="<?php the_permalink(); ?>" class="btn btn-primary mt-auto align-self-start fs-5">Read More</a>
	</div>
</li>
