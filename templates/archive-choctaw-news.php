<?php
/**
 * The Archive Display for the Events
 *
 * @since 1.0
 * @package ChoctawNation
 */

get_header();
?>
<div class="container my-5 py-5">
	<h1>News</h1>
	<?php if ( have_posts() ) : ?>
	<section class="results">
		<ol class="list-unstyled">
			<?php
			while ( have_posts() ) {
				the_post();
				require dirname( __DIR__ ) . '/template-parts/content-news-preview.php';
			}
			?>
		</ol>
		<?php else : ?>
		<p>No articles found.</p>
		<?php endif; ?>
	</section>
</div>
<?php
wp_footer();