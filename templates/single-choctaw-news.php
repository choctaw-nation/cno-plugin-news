<?php
/**
 * The Single Display for the Events
 *
 * @since 1.0
 * @package ChoctawNation
 */

get_header();

?>
<div class="container my-5 py-5">
	<nav arial-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="/events">All Events</a></li>
			<li class="breadcrumb-item active" aria-current="page"><?php echo $event->get_the_name(); ?></li>
		</ol>
	</nav>
	<article <?php post_class(); ?> id="<?php echo 'post-' . get_the_ID(); ?>">
		<header>
			<h1></h1>
			<?php the_post_thumbnail( 'large' ); ?>
		</header>
		<section class="row">
			<?php the_content(); ?>
		</section>
		<aside class="boilerplates"></aside>
	</article>
</div>

<?php
wp_footer();