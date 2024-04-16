<?php
/**
 * The Single Display for the Events
 *
 * @since 1.0
 * @package ChoctawNation
 * @subpackage News
 */

use ChoctawNation\News\News;

wp_enqueue_script( 'cno-news' );
get_header();
$news = new News( get_the_ID() );
?>

<article <?php post_class( array( 'article', 'container', 'my-5', 'py-5' ) ); ?> id="<?php echo 'post-' . get_the_ID(); ?>">
	<header class="article__header">
		<?php if ( $news->has_photo ) : ?>
		<div class="row my-5">
			<div class="col">
				<div class='ratio ratio-16x9'>
					<?php $news->the_photo( 'choctaw-news-single', array( 'class' => 'object-fit-cover' ) ); ?>
				</div>
				<?php if ( $news->get_the_photo_credit() || $news->get_the_photo_caption() ) : ?>
				<div class='photo-meta mt-3'>
					<?php
					$news->the_photo_credit();
					$news->the_photo_caption();
					?>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>
		<div class="row my-5">
			<div class="col">
				<?php the_title( '<h1>', '</h1>' ); ?>
				<?php if ( $news->get_the_subheadline() ) : ?>
				<span class="subheadline fw-bold text-secondary">
					<?php $news->the_subheadline(); ?>
				</span>
				<?php endif; ?>
			</div>
		</div>
	</header>
	<nav arial-label="breadcrumb" class="my-4">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?php echo get_post_type_archive_link( 'choctaw-news' ); ?>">All News</a></li>
			<li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
		</ol>
	</nav>
	<aside class="article__published-date mb-5"><?php $news->the_published_date(); ?></aside>
	<section class="article__body row">
		<?php $news->the_article(); ?>
		<?php if ( $news->has_external_link ) : ?>
		<a href="<?php $news->the_external_article_link(); ?>" target="_blank" rel="noopener noreferrer"
			class="article__external-link d-block border border-1 bg-light-subtle p-4 my-5 w-auto">
			<aside class='external-link'>
				<h2 class="external-link__headline">Read the Full Article</h2>
				<span class='external-link__title h3'><?php $news->the_external_article_title(); ?></span>
				<p class='external-link__author'>By <?php $news->the_external_article_author(); ?></p>
				<p class="external-link__publish-date">Published <?php $news->the_external_article_publish_date( 'M j, Y' ); ?></p>
			</aside>
		</a>
		<?php endif; ?>
		<?php
		if ( ( $news->has_video ) ) {
			$news->the_video();
		}
		?>
	</section>
	<?php if ( $news->has_boilerplates ) : ?>
	<section class="boilerplates">
		<?php $news->the_boilerplates(); ?>
	</section>
	<?php endif; ?>
</article>
<?php
get_footer();