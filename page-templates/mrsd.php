<?php
/**
 * Template Name: Mrs D's blog
 *
 * @package kleo-ls
 * @since September 2018
 */
//Potentially  no longer used at all
get_header(); ?>


<div id="main-content" class="main-content">

	<div id="primary">
		<div class="container" role="main">
			<div class="row">
			<div class="col-sm-3 col-md-2 blogMenu">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
			</div>
			
			
			<section class="col-sm-6 col-md-7 col-lg-8 blogListSection">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					// Include the page content template.
					get_template_part( 'content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
				endwhile;
			?>
			
			
				<?php $the_query = new WP_Query( 'cat=1&showposts=4' ); ?>
			    <?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
			    <article class="blogListing">
			    <h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
			    <span class="details">
			    	<?php the_date('l j M, Y, g:ia'); ?>
			    	by
			    	<?php the_author(); ?>
			    	<?php
			    	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
			    	?>
			    	<span class="commentCount">
			    	<a href="<?php comments_link(); ?>">
			    	<?php comments_number( '0 comments', '1 comment', '% comments' ); ?></a>
			    	</span>
			    	<?php endif; ?>
			    </span><!-- /details -->
			    
			    <?php the_excerpt(__('(more…)')); ?>
			    </article>
			    <?php endwhile;?>
			    <a href="/<?=date('Y')?>" class="btn btn-primary btn-red pull-right">Older posts</a>

			</section>
			<aside class="col-xs-5 col-sm-3 col-lg-2 rightSide">
                            
				<?php include(dirname(__FILE__) . "/_days-sober.php"); ?>
                <div class="toolBox">
                <img src="/wp-content/themes/livingsober/images/sober-toolbox.png" alt="Sober toolbox" />
                <p>Share tools and ideas that help you stay sober.</p>
                <a class="btn btn-primary btn-orange" href="/sober-toolbox/">Sober toolbox</a>
                </div>
                <div class="toolBox drink">
                <img src="/wp-content/themes/livingsober/images/drink-of-the-week.png" alt="Drink of the week" />
                <h3>Drink of the week</h3>
                <p><?php echo get_post_meta( 8 , "drinkoftheweek", true); ?></p>
                <a class="btn btn-primary btn-orange" href="/category/drink-of-the-week/">Get ideas and recipes</a>
                </div>            
			</aside>
			

		</div><!-- /row -->
		</div><!-- /container -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_footer();


