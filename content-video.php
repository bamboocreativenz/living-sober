<?php
/**
 * The template for displaying posts in the Video post format
 *
 * @package kleo-ls
 * @since September 2018
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php livingsober_post_thumbnail(); ?>

	<header class="entry-header">

		<?php
			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
			endif;
		?>

	</header><!-- .entry-header -->
	
				<?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
				<span class="details">
					<?php the_date('l j M, Y, g:ia'); ?>
					by
					<?php the_author(); ?>
					<span class="commentCount">
					<?php comments_number( '<a href="#respond">Share your ideas</a>', '<a href="#comments">1 comment</a>', '<a href="#comments">% comments</a>' ); ?>
					</span>
				</span><!-- /details -->
				
				<?php endif; ?>

	<div class="entry-content">
		<?php
			the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'livingsober' ) );
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'livingsober' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
