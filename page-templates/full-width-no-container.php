<?php
/**
 * Template Name: Full-width No Container
 *
 * Description: Template for other builders than VC
 *
 * @package WordPress
 * @subpackage Kleo
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 4.2.6
 */

get_header(); ?>

<?php
//create full width template
kleo_switch_layout('no');
add_filter( 'kleo_main_container_class', 'kleo_ret_full_container' );
?>

<?php get_template_part('page-parts/general-title-section'); ?>

<?php get_template_part('page-parts/general-before-wrap'); ?>

<?php
if ( have_posts() ) :
	// Start the Loop.
	while ( have_posts() ) : the_post();

		/*
		 * Include the post format-specific template for the content. If you want to
		 * use this in a child theme, then include a file called called content-___.php
		 * (where ___ is the post format) and that will be used instead.
		 */
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