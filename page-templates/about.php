<?php
/**
 * Template Name: About pages
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
			<?php dynamic_sidebar( 'sidebar-7' ); ?>
			</div>
			
			
			<section class="col-sm-6 col-md-7 col-lg-8 contentPage detail">
		
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();
					// Include the page content template.
					get_template_part( 'content', 'page' );
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
			?>
						
			</section>
			
			
			<aside class="col-xs-5 col-sm-3 col-lg-2 rightSide">
                            
				<?php include(dirname(__FILE__) . "/_days-sober.php"); ?>
				<div class="toolBox">
				<a href="http://www.mightyape.co.nz/product/Mrs-D-is-Going-without-A-Memoir/22258318" target="_blank"><img src="/wp-content/themes/livingsober/images/book.png" alt="Mrs D is going without: the book" /></a>
				<p>Mrs D's best-selling memoir describes her drinking problem, charts her route to sobriety and reveals how crucial the online recovery community became.</p>
				<a class="btn btn-primary btn-orange" href="http://www.mightyape.co.nz/product/Mrs-D-is-Going-without-A-Memoir/22258318" target="_blank">Buy the book</a>
				</div>
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
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_footer();


