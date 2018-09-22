<?php
/**
 * The template for displaying Tag pages
 *
 * Used to display archive-type pages for posts in a tag.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
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
					<?php dynamic_sidebar( 'sidebar-1' ); ?>
				</div>

				<section class="col-sm-6 col-md-7 col-lg-8 blogListSection">
				
				<?php if ( have_posts() ) : ?>
				<article>
				<header class="entry-header">
				<h1 class="entry-title"><?php printf( __( '‘%s’ tag', 'livingsober' ), single_tag_title( '', false ) ); ?></h1>
					<?php
						// Show an optional term description.
						$term_description = term_description();
						if ( ! empty( $term_description ) ) :
							printf( '<div class="taxonomy-description">%s</div>', $term_description );
						endif;
					?>
				</header><!-- .entry-header -->
				</article>
				<?php
				// Start the Loop.
				while ( have_posts() ) : the_post(); ?>
				
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
			    
			    <?php

				/*
				 * Include the post format-specific template for the content. If you want to
				 * use this in a child theme, then include a file called called content-___.php
				 * (where ___ is the post format) and that will be used instead.
				 */
				//get_template_part( 'content', get_post_format() );

				endwhile;
				// Previous/next page navigation.
				livingsober_paging_nav();
	
				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;
				?>
				</section>
				

				<aside class="col-xs-5 col-sm-3 col-lg-2 rightSide">
				<?php include(dirname(__FILE__) . "/page-templates/_days-sober.php"); ?>
                <div class="toolBox">
                <img src="/wp-content/themes/livingsober/images/sober-toolbox.png" alt="Sober toolbox" />
                <p>Share tools and ideas that help you stay sober.</p>
                <a class="btn btn-primary btn-orange" href="/sober-toolbox/">Sober toolbox</a>
                </div>
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


				