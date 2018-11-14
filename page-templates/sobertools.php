<?php
/**
 * Template Name: Sober Tools
 *
 * @package kleo-ls
 * @since September 2018
 */

get_header(); ?>

<?php
//create sobertools template
kleo_switch_layout('sobertools');
?>

<?php get_template_part('page-parts/general-title-section'); ?>

<?php get_template_part('page-parts/general-before-wrap'); ?>

<?php
	// Reorder comments
	add_filter( 'comments_array', 'array_reverse' );
	// Start the Loop.
	while ( have_posts() ) : the_post();
		// Include the page content template.
		get_template_part( 'content', 'page' );
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) {
			kleo_comment_form();
		}
	endwhile;
?>
</section>

<?php
get_footer();


