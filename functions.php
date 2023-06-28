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

// Remove DNS Prefetch will solve this issue https://stackoverflow.com/a/51918472
remove_action( 'wp_head', 'wp_resource_hints', 2 );

/* DL: Tell buddy press to use WP mail smtp
 * This will mean that mail wont work in dev/staging unless wp mail is set up as well
 */
add_filter( 'bp_email_use_wp_mail', '__return_true' );

// Disable the "Activity > Following" subnav item on a user's profile page
// note we are running this modified version of the plugin. this code is actually redundant but its here as a reference for not doing what we wanted.
// see https://github.com/agentlewis/buddypress-followers
add_filter( 'bp_follow_show_activity_subnav', '__return_false' );

// DL disable wp-embeds as people posting youtube links lag the site.
function my_deregister_scripts(){
  wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );

// DL make posts longer in activity feed
function bpfr_custom_length( $excerpt_length) {
	$excerpt_length = '2000'; // change value to your need
	return $excerpt_length;

}
add_filter( 'bp_activity_excerpt_length', 'bpfr_custom_length', 10, 1);
