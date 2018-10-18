<?php
/**
 * Template Name: Sober Tools
 *
 * @package kleo-ls
 * @since September 2018
 */

get_header(); ?>


<div id="main-content" class="main-content">

	<div id="primary">
		<div class="container" role="main">
			<div class="row">
			<div class="col-sm-3 col-md-2 blogMenu">
			<?php dynamic_sidebar( 'sidebar-4' ); ?>
			</div>
			
			
			<section class="col-sm-9 col-md-10 col-lg-10 contentPage">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php // Include the page content template.
			get_template_part( 'content', 'page' );?>
			
	
			<?php
			$args = array(
			'post_parent' => $post->ID,
			'post_type' => 'page',
			'post_status' => 'publish',
			'numberposts' => -1,
			'orderby' => 'date',
			'order' => 'ASC'
			);
			
			$postslist = get_posts($args);
			?>
			
			<?php foreach ( $postslist as $post ) : ?>
			
			<?php setup_postdata( $post ); ?>
			
			<article class="soberTools">
			
			<a href="<?php the_permalink(); ?>"><?php if( has_post_thumbnail() ) the_post_thumbnail('Project'); ?></a>
			<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<span class="details">
				<?php //the_date('l j M, Y, g:ia'); ?>
				<?php the_time( get_option( 'date_format' ) ); ?>
				
				<span class="commentCount">
					<a href="<?php comments_link(); ?>">
				<?php comments_number( '0 comments', '1 comment', '% comments' ); ?></a>
				</span>
			</span><!-- /details -->
			<p class="summary">
			<?php $key="Page Short Description"; echo get_post_meta($post->ID, $key, true); ?>
			</p><!-- summary -->
			<a class="btn btn-primary btn-orange" href="<?php the_permalink(); ?>">Share your ideas and read others'</a>
			</article><!-- soberTools -->
			
			<?php endforeach; ?>
			<?php endwhile; endif; ?>	
			</section>
			</div><!-- /row -->
		</div><!-- /container -->
	</div><!-- #primary -->
</div><!-- #main-content -->	

<?php
get_footer();


