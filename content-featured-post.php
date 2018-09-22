<?php
/**
 * The template for displaying featured posts on the front page
 *
 * @package kleo-ls
 * @since September 2018
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


		<?php the_title( '<h4 class="entry-title"><span>Featured post</span> <a href="' . esc_url( get_permalink() ) . '" rel="bookmark">','</a></h4>' ); ?>
		<span class="details">
			<?php the_date('D j M, Y, g:ia'); ?>
			by
			<?php the_author(); ?>
			<span class="commentCount">
			<?php comments_number( '0', '1', '%' ); ?>
			</span>
		</span><!-- /details -->
		<?php the_excerpt(); ?> 
</article><!-- #post-## -->
