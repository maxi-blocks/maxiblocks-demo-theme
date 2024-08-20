<?php
get_header();
?>

<?php
if (have_posts()) :

	/* Start the Loop */
	while (have_posts()) :
		the_post();

?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div class="entry-content">
				<?php the_content(); ?>
			</div><!-- .entry-content -->

		</article><!-- #post-<?php the_ID(); ?> -->

<?php

	endwhile;

endif;
?>

</main><!-- #main -->

<?php
get_footer();