<?php
/**
 * Template Name: Sober Tools Single
 *
 * @package kleo-ls
 * @since September 2018
 */

get_header(); ?>


<div id="main-content" class="main-content">

	<div id="primary">
		<div class="container" role="main">
			<div class="row">
			<div class="col-sm-3 col-md-2  blogMenu">
			<?php dynamic_sidebar( 'sidebar-4' ); ?>
			</div>
			
			
			<section class="col-sm-9 col-md-8 col-lg-10 contentPage detail">
		
      <?php
        // Reorder comments
        add_filter( 'comments_array', 'array_reverse' );
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
			
			</div><!-- /row -->
		</div><!-- /container -->
	</div><!-- #primary -->
</div><!-- #main-content -->	

<?php
get_footer();


