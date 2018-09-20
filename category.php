<?php
/**
 * The template for displaying Category pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Living Sober
 * @since July 2014
 */

get_header(); ?>
<div id="main-content" class="main-content">
	<section id="primary">
		<div class="container" role="main">
			<div class="row">
			<div class="col-sm-3 col-md-2 blogMenu">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
			</div>
				
			<section class="col-sm-6 col-md-7 col-lg-8 blogListSection">
				
			<?php if ( have_posts() ) : ?>
			
			<h1><?php printf( __( '%s', 'livingsober' ), single_cat_title( '', false ) ); ?></h1>
				
			<div class="entry-content">
			<?php if( !$paged ) :?>
				<?php if (is_category('1') ) : ?>
					<?php
					  $mrsd_page_post_id = 13;
					  $mrsd_page_post = get_post( $mrsd_page_post_id );
					  $content_mrsd_intro = $mrsd_page_post->post_content;
					  $content_mrsd_intro = apply_filters('the_content', $content_mrsd_intro);
					  $content_mrsd_intro = str_replace(']]>', ']]&gt;', $content_mrsd_intro);
					  echo $content_mrsd_intro;
					?>
				<?php else : ?>
					<?php echo '<h2>' . category_description( $category_id ). '</h2>'; ?> 
				<?php endif; ?>
			<?php else : ?>
				<h2><?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					echo 'Page ' . $paged; 
				?></h2>
			<?php endif;?>
			</div>

			<?php
					// Start the Loop.
					while ( have_posts() ) : the_post();

					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part( 'content', 'category', get_post_format() );

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
	</section><!-- #primary -->
</div><!-- #main-content -->	

<?php
get_footer();
