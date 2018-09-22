<?php

/**
 * The template for displaying featured content
 *
 * @package kleo-ls
 * @since September 2018
 */
?>


<?php

/**
 * Fires before the Living Sober featured content.
 *
 * @since September 2018
 */
do_action('livingsober_featured_posts_before');

$featured_posts = livingsober_get_featured_posts();

/**
 * Limit to only show one featured post.
 * @author <james@96black.co.nz> James McFall
 */
if (is_array($featured_posts)) {
    # Trim array to only have one entry.
    $featured_posts = array_slice($featured_posts, 0, 1);
}

foreach ((array) $featured_posts as $order => $post) :
    setup_postdata($post);

    // Include the featured content template.
    get_template_part('content', 'featured-post');
endforeach;

/**
 * Fires after the Living Sober featured content.
 *
 * @since September 2018
 */
do_action('livingsober_featured_posts_after');

wp_reset_postdata();
?>

