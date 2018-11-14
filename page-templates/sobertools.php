<?php
/**
 * Template Name: Sober Tools
 *
 * @package kleo-ls
 * @since September 2018
 */

get_header(); ?>

<?php get_template_part('page-parts/general-title-section'); ?>

<?php get_template_part('page-parts/general-before-wrap'); ?>

<?php if ( have_posts() ) : ?>
	<?php
		// Reorder comments
		add_filter( 'comments_array', 'array_reverse' );
		// Start the Loop.
		while ( have_posts() ) : the_post();

		get_template_part( 'content', 'page' );
  ?>

			<?php get_template_part( 'page-parts/posts-social-share' ); ?>
			<!-- Begin Comments -->
			<?php
				if ( comments_open() || get_comments_number() ) {
					kleo_comment_form();
			} ?>
      <!-- End Comments -->
	<?php endwhile; ?>

<?php endif; ?>

<?php get_template_part('page-parts/general-after-wrap'); ?>

<?php get_footer(); ?>
