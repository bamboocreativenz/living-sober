<?php
/**
 * Template Name: Sober Tools
 *
 * @package kleo-ls
 * @since September 2018
 */

get_header(); ?>

<?php
//create full width base template from Kleo - ie do you want widget areas?
kleo_switch_layout('no');
?>

<?php get_template_part('page-parts/general-title-section'); ?>

<?php get_template_part('page-parts/general-before-wrap'); ?>

<?php
if ( have_posts() ) :
	// Reorder comments
	add_filter( 'comments_array', 'array_reverse' );
	// Start the Loop.
	while ( have_posts() ) : the_post();
	
		get_template_part( 'content', 'page' );
        ?>

        <?php get_template_part( 'page-parts/posts-social-share' ); ?>

		<?php if ( sq_option( 'page_comments', 0 ) == 1 ): ?>

			<!-- Begin Comments -->
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template( '', true );
			} ?>
			<!-- End Comments -->

		<?php endif; ?>

	<?php endwhile;

endif;
?>

<?php get_template_part('page-parts/general-after-wrap'); ?>

<?php get_footer(); ?>
