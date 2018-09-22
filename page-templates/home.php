<?php
/**
 * Template Name: Home Page
 *
 * @package kleo-ls
 * @since September 2018
 */
get_header();
?>


<div id="main-content" class="main-content">

    <div id="primary">
        <div class="container" role="main">
            <div class="row">
                <div class="col-sm-6 col-lg-7">
                    <div class="welcome"></div>
                    <?php
                    // Start the Loop.
                    while (have_posts()) : the_post();
                        // Include the page content template.
                        get_template_part('content', 'page');
                    endwhile;
                    ?>
                    
                    <?php $the_query = new WP_Query( 'cat=1&showposts=1&tag=home' ); ?>
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
                    
                </div><!-- col 7 -->
                <div class="col-xs-7 col-sm-3 communityFeatured">
                    <h3>Join the community</h3>
                    <?php if (is_user_logged_in()) : ?>
                    	<p><strong>You are not alone.</strong> Join the community to share your own stories and connect with others who are looking at the role alcohol plays in their lives.</p>
                        <a href="/activity-2/" class="btn btn-primary">Community Area</a>
                    <?php else : ?>
                    	<p>Once you're a member you can share your own stories and connect with others who are looking at the role alcohol plays in their lives.</p>
                        <a href="/join-the-community/" class="btn btn-primary">Join the community</a>

                    <?php endif; ?>

                    <!-- featured post -->
                    <?php get_template_part('featured-content'); ?>

                    <div class="featuredComment">
                        <h4><span>Featured comment</span></h4>
                        <!-- featured comment -->
                        <?php dynamic_sidebar('sidebar-2'); ?>
                    </div>
                </div>
                <aside class="col-xs-5 col-sm-3 col-lg-2 rightSide">
                    <?php include(dirname(__FILE__) . "/_days-sober.php"); ?>
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
            </div><!-- row -->
        </div><!-- container -->
    </div><!-- #primary -->
</div><!-- #main-content -->

<?php
//get_sidebar();
get_footer();
?>