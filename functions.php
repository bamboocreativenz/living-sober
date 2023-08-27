<?php
/**
 * @package WordPress
 * @subpackage Kleo
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Kleo 1.0
 */

/**
 * Kleo Child Theme Functions
 * Add custom code below
*/

/**
 * Proper way to enqueue scripts and styles
 * Based on:
 * https://wordpress.stackexchange.com/posts/274667/revisions
 * https://developer.wordpress.org/reference/functions/wp_enqueue_style/#comment-340
 */

// Register and enqueue scripts.
function ls_kleo_scripts() {

	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('jquery-validate', get_stylesheet_directory_uri() . "/js/jquery-validate.min.js", array(), '1.0.0', true);

	// Add other scripts below
	wp_enqueue_script( 'livingsober', get_stylesheet_directory_uri() . '/js/livingsober.js', array(), '1.0.0', true );
}

// Register and enqueue styles.
function ls_kleo_styles() {
    // Access the wp_scripts global to get the jquery-ui-core version used.
    global $wp_scripts;
    // Create a handle for the jquery-ui-core css.
    $handle = 'jquery-ui';
    // Path to stylesheet, based on the jquery-ui-core version used in core.
    $src = "https://ajax.googleapis.com/ajax/libs/jqueryui/{$wp_scripts->registered['jquery-ui-core']->ver}/themes/smoothness/{$handle}.css";
    // Required dependencies
    $deps = array();
    // Add stylesheet version.
    $ver = $wp_scripts->registered['jquery-ui-core']->ver;
    // Register the stylesheet handle.
    wp_register_style( $handle, $src, $deps, $ver );
    // Enqueue the style.
    wp_enqueue_style( 'jquery-ui' );
    wp_enqueue_style( 'sample-theme-style', get_stylesheet_uri(), array( 'jquery-ui' ), '1.0.0' );
}

// Enqueue required scripts and styles.
function ls_kleo_enqueue() {
    ls_kleo_scripts();
    ls_kleo_styles();
}

// DL: fix for Kleo theme https://docs.wpbeaverbuilder.com/beaver-builder/troubleshooting/debugging/known-beaver-builder-incompatibilities/#kleo-theme
function bb_custom_enqueue_scripts(){

	if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {
	  wp_deregister_script( 'app' );
	}
  }

add_action( 'wp_enqueue_scripts', 'ls_kleo_enqueue', 'bb_custom_enqueue_scripts', 99 );

// DL: fix to redirect signed in users to members home page
function redirect_signedin_user()
{
    if(is_user_logged_in() && is_page('8250'))
    {
        wp_redirect('https://livingsober.org.nz/members-home');
    }
}

add_action('template_redirect', 'redirect_signedin_user');

/**
 * Other overides
 */

// Remove DNS Prefetch will solve this issue https://stackoverflow.com/a/51918472
remove_action( 'wp_head', 'wp_resource_hints', 2 );

// DL disable wp-embeds as people posting youtube links lag the site.
function my_deregister_scripts(){
	wp_deregister_script( 'wp-embed' );
  }
add_action( 'wp_footer', 'my_deregister_scripts' );


/**
 * Buddypress stuff
*/
add_filter( 'bp_activity_maybe_load_mentions_scripts', 'buddydev_enable_mention_autosuggestions', 10, 2 );

function buddydev_enable_mention_autosuggestions( $load, $mentions_enabled ) {

    if( ! $mentions_enabled ) {
        return $load;//activity mention is  not enabled, so no need to bother
    }
    //modify this condition to suit yours
    if( is_user_logged_in() && is_page_template( 'page-templates/sobertools.php' ) ) {
        $load = true;
    }

    return $load;
}

/**
 * DL: Tell buddy press to use WP mail smtp
 * This will mean that mail wont work in dev/staging unless wp mail is set up as well
 */
add_filter( 'bp_email_use_wp_mail', '__return_true' );

// Disable the "Activity > Following" subnav item on a user's profile page
// note we are running this modified version of the plugin. this code is actually redundant but its here as a reference for not doing what we wanted.
// see https://github.com/agentlewis/buddypress-followers
add_filter( 'bp_follow_show_activity_subnav', '__return_false' );

// DL make posts longer in activity feed
function bpfr_custom_length( $excerpt_length) {
	$excerpt_length = '2000'; // change value to your need
	return $excerpt_length;

}
add_filter( 'bp_activity_excerpt_length', 'bpfr_custom_length', 10, 1);


// DL remove Archive Title Prefix
// https://developer.wordpress.org/reference/hooks/get_the_archive_title_prefix/
add_filter( 'get_the_archive_title_prefix', '__return_false' );
