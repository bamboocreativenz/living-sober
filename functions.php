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
	wp_enqueue_style( 'kleo-style', get_stylesheet_directory_uri() . '/css/jquery-ui.structure.min.css');
	wp_enqueue_style( 'kleo-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'kleo-ls-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'kleo-style' )
	);
}

add_action( 'wp_enqueue_scripts', 'jquery_ui_enqueue_styles' );
function jquery_ui_enqueue_styles() {
	wp_enqueue_style( 'jquery-style', get_stylesheet_directory_uri() . '/css/jquery-ui.theme.min.css');
}

add_action( 'wp_enqueue_scripts', 'bb_custom_enqueue_scripts', 99 );;
function bb_custom_enqueue_scripts(){  
  if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {
    wp_deregister_script( 'app' );
  }
}

// Load Custom JavaScript via Child Theme
function my_scripts_method() {
	wp_enqueue_script(
			'custom-script',
			get_stylesheet_directory_uri() . '/js/livingsober.js',
			array( 'app' )
	);
	wp_register_script( 'validate-js', 'https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js', array('jquery'), NULL, true );
	wp_enqueue_script( 'validate-js' );
	wp_register_script( 'jquery-ui-js', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js?integrity=sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=&crossorigin=anonymous', array('jquery'), NULL, true );
	wp_enqueue_script( 'jquery-ui-js' );
}
add_action( 'wp_enqueue_scripts', 'my_scripts_method' );

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

// Protect pages

/**
 * Make the members/community pages protected if you aren't logged in.
 * 
 * @global <BuddyPress> $bp
 * @return <void>
 */
function protectMembersPage() {
	global $bp;
	
	# Allow access to the register/activation page
	if( bp_is_register_page() || bp_is_activation_page() || bp_is_page( BP_FORUMS_SLUG ) || bp_is_page( BP_GROUPS_SLUG ) ) {
			return;
	}
	
	# Restrict any community pages
	if( ! bp_is_blog_page() && ! is_user_logged_in() ) {
			bp_core_redirect( $bp->root_domain .'/'. BP_REGISTER_SLUG );
	}
}
add_action( 'get_header', 'protectMembersPage' );

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

// Overide url redirect when comment on blog is posted to show the first page not the last

function custom_comment_redirect( $location ) {
	if ( strpos(get_page_template_slug(), 'sobertools' ) === true){
			$location = '#';
	}
	return $location;
}

add_filter( 'comment_post_redirect', 'custom_comment_redirect' );
