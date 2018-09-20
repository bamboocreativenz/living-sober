<?php
/**
 * Kleo-ls Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package kleo-ls
 */

add_action( 'wp_enqueue_scripts', 'kleo_parent_theme_enqueue_styles' );

/**
 * Enqueue scripts and styles.
 */
function kleo_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'kleo-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'kleo-ls-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'kleo-style' )
	);

}

add_action( 'wp_enqueue_scripts', 'bb_custom_enqueue_scripts', 99 );
function bb_custom_enqueue_scripts(){  
  if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {
    wp_deregister_script( 'app' );
  }
}

//
// Show admin bar only for admins and editors
// https://digwp.com/2011/04/admin-bar-tricks/
if (!current_user_can('edit_posts')) {
	add_filter('show_admin_bar', '__return_false');
}

add_action('admin_print_scripts-profile.php', 'hideAdminBar');
function hideAdminBar() { ?>
  <style type="text/css">.show-admin-bar { display: none; }</style>
  <?php }


/**
 * Comments
 * This is all from the original wordpress theme of living sober
 * We want newest comments to appear at the top, with next button
 * In WP Admin > Settings > Discussion - the FIRST page displayed by default
 * In WP Admin > Settings > Discussion - Comments should be displayed with the OLDER comments at the top of each page
 */

/**
 * Reverse comment order because we want first page to have the latest comments
 */
function sort_by_reverse($comments) {
  return array_reverse($comments);
}
// add_filter ('comments_array', 'sort_by_reverse');

/**
* custom sorting method by karma
*/
function comment_comparator($a, $b) {
 $compared = 0;
 if($a->comment_karma != $b->comment_karma)
 {
   $compared = $a->comment_karma < $b->comment_karma ? 1:-1;
 }
 return $compared;
}

/**
* Hook up custom sorting method
*/
function sort_by_karma($comments) {
 usort($comments, 'comment_comparator');
 return $comments;
}

if($_GET['comments_sort'] == 'recent') {
 add_filter ('comments_array', 'sort_by_reverse');
} else {
 add_filter ('comments_array', 'sort_by_karma');	
}

//disable default sorting provided by the Comment Popularity plugin
add_filter( 'hmn_cp_sort_comments_by_weight', '__return_false' );

//enable comment voting by anonymous
add_filter( 'hmn_cp_allow_guest_voting', '__return_true' );
