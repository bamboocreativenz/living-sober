<?php
/**
 * Template Name: Blog Posts Landing
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
			
				
				<section class="col-sm-6 col-md-7 col-lg-8 blogCatsList">
				<?php
					// Start the Loop.
					while ( have_posts() ) : the_post();
						// Include the page content template.
						get_template_part( 'content', 'page' );
						// If comments are open or we have at least one comment, load up the comment template.
					endwhile;
				?>

				<?php
				$categories = get_categories(array('orderby' => 'slug','order' => 'desc'));
				
				foreach ($categories as $category) {
					echo '<article class="blogCat"><div class="row">';
					
					if ($category->category_nicename == 'k-ask-an-expert') {
						echo '<div class="col-sm-3 text-center"><img src="/wp-content/themes/livingsober/images/ask-an-expert.png" alt="Stethoscope illustration"/></div>';
					}
					
					if ($category->category_nicename == 'mrs-ds-blog') {
						echo '<div class="col-sm-3 text-center"><img src="/wp-content/themes/livingsober/images/mrs-ds-blog.png" alt="Speech bubbles illustration"/></div>';
					}
					
					if ($category->category_nicename == 'drink-of-the-week') {
						echo '<div class="col-sm-3 text-center"><img src="/wp-content/themes/livingsober/images/drink-of-the-week-blog.png" alt="Cocktail glass illustration"/></div>';
					}
					
					if ($category->category_nicename == 'l-sober-stories') {
						echo '<div class="col-sm-3 text-center"><img src="/wp-content/themes/livingsober/images/sober-stories.png" alt="Notebook and quill illustration"/></div>';
					}
					
					echo '<div class="col-sm-9"><h3><a href="'.get_option('home').get_option('category_base').'/category/'.$category->category_nicename.'/">'.$category->cat_name.'</a></h3>';
					
					if ($category->category_description != '') {
						echo '<p>' . $category->category_description . '</p>';
					}
					
					echo '<a class="btn btn-primary btn-orange" href="'.get_option('home').get_option('category_base').'/category/'.$category->category_nicename.'/">See blog posts</a>';
					
					
					echo '</div></div></article>';
				}
				?>
				
				</section>
			
				<aside class="col-xs-5 col-sm-3 col-lg-2 rightSide">
	                <?php include(dirname(__FILE__) . "/_days-sober.php"); ?>
	                <div class="toolBox">
	                <img src="/wp-content/themes/livingsober/images/sober-toolbox.png" alt="Sober toolbox" />
	                <p>Share tools and ideas that help you stay sober.</p>
	                <a class="btn btn-primary btn-orange" href="/sober-toolbox/">Sober toolbox</a>
	                </div>            
				</aside>
				
				
				
			

			</div><!-- /row -->
		</div><!-- /container -->
	</div><!-- #primary -->
</div><!-- #main-content -->	

<?php
get_footer();


