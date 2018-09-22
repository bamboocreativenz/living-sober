<?php
/**
 * The template used for displaying page content
 *
 * @package kleo-ls
 * @since September 2018
 */
 
?>

<?
$registerPage = is_page(array(66));
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		// Page thumbnail and title.
		//livingsober_post_thumbnail();
		the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header><!-- .entry-header -->' );
	?>

	<?php if (is_page( array( 99, 'activity-2') ) ) : ?>
			<?php
			  $members_page_post_id = 5;
			  $members_page_post = get_post( $members_page_post_id, ARRAY_A );
			  $content_members = $members_page_post['post_content'];
			  echo $content_members;
			?>
	<?php endif; ?>

	
	<?php if ( !is_front_page() && !is_home() && comments_open() ) : ?>
	
	<span class="details">
		<?php //the_date('l j M, Y, g:ia'); ?>
		<?php the_time( get_option( 'date_format' ) ); ?>
		
		<span class="commentCount">
		<?php comments_number( '<a href="#respond">Share your ideas</a>', '<a href="#comments">1 comment</a>', '<a href="#comments">% comments</a>' ); ?>
		</span>
	</span><!-- /details -->
	
	<?php endif; ?>
	

	<div class="entry-content">
		<?php
			the_content();
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'livingsober' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );

		?>
		
		
	</div><!-- .entry-content -->
<?php if ($registerPage) : ?>
<hr />
<h3>Code of conduct</h3>
<p class="ethos"><?php $key="Page Short Description"; echo get_post_meta(66, $key, true); ?></p>
<?php endif; ?>
	
	
	
</article><!-- #post-## -->
