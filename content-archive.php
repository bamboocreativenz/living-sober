<?php
/**
 * The default template for displaying content
 *
 * Used for achive
 *
 * @package WordPress
 * @subpackage Living Sober
 * @since July 2014
 */
?>

<article class="blogListing">
	<?php //livingsober_post_thumbnail(); ?>

		<?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && livingsober_categorized_blog() ) : ?>
		<?php
			endif;

			if ( is_single() ) :
				the_title( '<h3>', '</h3>' );
			else :
				the_title( '<h3><a href="' . esc_url( get_permalink() ) . '">', '</a></h3>' );
			endif;
		?>

		<span class="details">
			<?php the_date('l j M, Y, g:ia'); ?>
			by
			<?php the_author(); ?>
			<?php
				if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
			?>
			<span class="commentCount"><?php comments_popup_link( __( 'Share your ideas', 'livingsober' ), __( '1 comment', 'livingsober' ), __( '% comments', 'livingsober' ) ); ?></span>
			<?php
				endif;
			?>
		</span><!-- .details -->
		

	<?php if ( is_search() ) : ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	   <?php the_excerpt(__('(more…)')); ?>
	<?php endif; ?>

	<?php //the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
</article><!-- #post-## -->
