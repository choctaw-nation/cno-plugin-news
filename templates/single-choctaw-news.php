<?php
/**
 * The Single Display for the Events
 *
 * @since 1.0
 * @package ChoctawNation
 */

use ChoctawNation\News\News;

get_header();
$news = new News( get_the_ID() );
wp_enqueue_script( 'cno-news' );
?>
<div class="container my-5 py-5">
	<article <?php post_class( 'article' ); ?> id="<?php echo 'post-' . get_the_ID(); ?>">
		<header class="article__header">
			<div class="row">
				<div class="col">
					<?php the_title( '<h1 class="mb-5">', '</h1>' ); ?>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<?php
					if ( $news->has_photo ) {
						$news->the_photo();
						echo "<div class='photo-meta mt-3'>";
						$news->the_photo_credit();
						$news->the_photo_caption();
						echo '</div>';
					}
					?>
				</div>
			</div>
		</header>
		<nav arial-label="breadcrumb" class="my-4">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/news">All News</a></li>
				<li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
			</ol>
		</nav>
		<aside class="article__published-date mb-5"><?php $news->the_published_date(); ?></aside>
		<section class="article__body row">
			<?php
			$news->the_article();
			if ( ( $news->has_video ) ) {
				$news->the_video();
			}
			?>

		</section>
		<section class="boilerplates">
			<?php $news->the_boilerplates(); ?>
		</section>
	</article>
</div>

<?php
wp_footer();