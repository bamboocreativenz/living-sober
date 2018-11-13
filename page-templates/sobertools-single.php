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
			<section class="col-sm-12 col-md-12 col-lg-12 contentPage detail">
		
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


