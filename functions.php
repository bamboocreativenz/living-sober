<?php
/**
 * @package WordPress
 * @subpackage Kleo
 * @author Daniel Lewis & Bamboo Creative NZ
 * @since Kleo 1.0
 */

/**
 * Kleo Child Theme Functions
 * Add custom code below
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
//add_filter ('comments_array', 'sort_by_reverse');

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

// Overide the Archive for category line in Kleo

if ( ! function_exists( 'kleo_title' ) ):
	/**
	 *  Return the Page title string
	 */

	function kleo_title() {
		$output = "";
		if ( is_tag() ) {
			$output = __( 'Tag Archive for:', 'kleo_framework' ) . " " . single_tag_title( '', false );
		} elseif ( is_tax() ) {
			$term   = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			$output = $term->name;
		} elseif ( is_category() ) {
			$output = single_cat_title( '', false );
		} elseif ( is_day() ) {
			$output = __( 'Archive for date:', 'kleo_framework' ) . " " . get_the_time( 'F jS, Y' );
		} elseif ( is_month() ) {
			$output = __( 'Archive for month:', 'kleo_framework' ) . " " . get_the_time( 'F, Y' );
		} elseif ( is_year() ) {
			$output = __( 'Archive for year:', 'kleo_framework' ) . " " . get_the_time( 'Y' );
		} elseif ( is_author() ) {
			$curauth = ( get_query_var( 'author_name' ) ) ? get_user_by( 'slug', get_query_var( 'author_name' ) ) : get_userdata( get_query_var( 'author' ) );
			$output  = __( 'Author Archive', 'kleo_framework' ) . " ";

			if ( isset( $curauth->nickname ) ) {
				$output .= __( 'for:', 'kleo_framework' ) . " " . $curauth->nickname;
			}
		} elseif ( is_archive() ) {
			$output = post_type_archive_title( '', false );
		} elseif ( is_search() ) {
			global $wp_query;
			if ( ! empty( $wp_query->found_posts ) ) {
				if ( $wp_query->found_posts > 1 ) {
					$output = $wp_query->found_posts . " " . __( 'search results for:', 'kleo_framework' ) . " " . esc_attr( get_search_query() );
				} else {
					$output = $wp_query->found_posts . " " . __( 'search result for:', 'kleo_framework' ) . " " . esc_attr( get_search_query() );
				}
			} else {
				if ( ! empty( $_GET['s'] ) ) {
					$output = __( 'Search results for:', 'kleo_framework' ) . " " . esc_attr( get_search_query() );
				} else {
					$output = __( 'To search the site please enter a valid term', 'kleo_framework' );
				}
			}

		} elseif ( is_front_page() && ! is_home() ) {
			$output = get_the_title( get_option( 'page_on_front' ) );

		} elseif ( is_home() ) {
			if ( get_option( 'page_for_posts' ) ) {
				$output = get_the_title( get_option( 'page_for_posts' ) );
			} else {
				$output = __( 'Blog', 'kleo_framework' );
			}

		} elseif ( is_404() ) {
			$output = __( 'Error 404 - Page not found', 'kleo_framework' );
		} else {
			$output = get_the_title();
		}

		if ( isset( $_GET['paged'] ) && ! empty( $_GET['paged'] ) ) {
			$output .= " (" . __( 'Page', 'kleo_framework' ) . " " . $_GET['paged'] . ")";
		}

		return $output;
	}
endif;