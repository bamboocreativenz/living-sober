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
			
			
			<section class="col-sm-6 col-md-7 col-lg-8 contentPage">
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
			
			
			<aside class="col-xs-5 col-sm-3 col-lg-2 rightSide">
				
                            <?php include(dirname(__FILE__) . "/_days-sober.php"); ?>
                            
                <?php
                 // Loading on random the 4 blog categories
                 $RandomList = array(); 
                
                 //Drink of the week
                 $RandomList[] = '<div class="toolBox drink">
                 <img src="/wp-content/themes/livingsober/images/drink-of-the-week.png" alt="Drink of the week" />
                 <h3>Drink Of The Week</h3>
                 <p>' . get_post_meta( 8 , "drinkoftheweek", true) . '</p>
                 <a class="btn btn-primary btn-orange" href="/category/drink-of-the-week/">Get ideas and recipes</a>
                 </div>';
                 
                 //Mrs Ds Blog
                 $RandomList[] = '<div class="toolBox quote">
                 <img src="/wp-content/themes/livingsober/images/mrs-ds-blog.png" alt="Mrs Ds Blog" />
                 <h3>Mrs D&#39;s Blog</h3>
                 <p>' . get_post_meta( 8 , "mrsdsblog", true) . '</p>
                 <a class="btn btn-primary btn-orange" href="/category/mrs-ds-blog/">See Mrs D&#39;s blogs</a>
                 </div>';
                 
                 //Ask an expert
                 $RandomList[] = '<div class="toolBox expert">
                 <img src="/wp-content/themes/livingsober/images/ask-an-expert.png" alt="Ask an Expert" />
                 <h3>Ask An Expert</h3>
                 <p>' . get_post_meta( 8 , "askanexpert", true) . '</p>
                 <a class="btn btn-primary btn-orange" href="/category/k-ask-an-expert/">See blog posts</a>
                 </div>';
                 
                 //Sober Stories
                 $RandomList[] = '<div class="toolBox stories">
                 <img src="/wp-content/themes/livingsober/images/sober-stories.png" alt="Sober Stories" />
                 <h3>Sober Stories</h3>
                 <p>' . get_post_meta( 8 , "soberstories", true) . '</p>
                 <a class="btn btn-primary btn-orange" href="/category/l-sober-stories/">Read Sober Stories</a>
                 </div>';
                 
                 echo $RandomList[rand(0,count($RandomList)-1)];
                 ?>            
			
			</aside>
			

			</div><!-- /row -->
		</div><!-- /container -->
	</div><!-- #primary -->
</div><!-- #main-content -->	

<?php
get_footer();


