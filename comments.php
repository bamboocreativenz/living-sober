<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Living Sober
 * @since July 2014
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( strpos(get_page_template_slug(), 'sobertools-single' )) : ?>
	<?php comment_form(); ?>
	<?php endif; ?>

  <?php comment_form(); ?>

	<?php if ( have_comments() ) : ?>

	<h2 class="comments-title">
		<?php
			printf( _n( 'One comment', '%1$s comments', get_comments_number(), 'livingsober' ),
				number_format_i18n( get_comments_number() ) );
		?>
	</h2>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>

  	<?php if ( is_plugin_active( 'comment-popularity/comment-popularity.php' ) ) { ?>
  	<ul class="sobertools-sort">
  		<li <?php echo !isset($_GET['comments_sort']) ? 'class="selected"' : ''; ?>><a href="<?php the_permalink(); ?>#comments">Sort by most liked</a></li>
  		<li <?php echo $_GET['comments_sort'] == 'recent' ? 'class="selected"' : ''; ?>><a href="<?php the_permalink(); ?>?comments_sort=recent#comments">Sort by most recent</a></li>
  	</ul>
  	<?php } ?>

  	<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
  		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'livingsober' ); ?></h1>
  		<div class="nav-previous"><?php echo add_sorting_querystring(get_previous_comments_link( __( '&larr; Previous Comments', 'livingsober' ) ) ); ?></div>
  		<div class="nav-next"><?php echo add_sorting_querystring(get_next_comments_link( __( 'Next Comments &rarr;', 'livingsober' ) ) ); ?></div>
  	</nav><!-- #comment-nav-above -->
	<?php endif; // Check for comment navigation. ?>

  <?php
    // Healthy Recipes
    if ($post->ID === 1519) :
  ?>
  	<ul class="sobertools-sort">
  		<li <?php echo !isset($_GET['comments_sort']) ? 'class="selected"' : ''; ?>><a href="<?php the_permalink(); ?>#comments">Sort by most liked</a></li>
  		<li <?php echo $_GET['comments_sort'] == 'recent' ? 'class="selected"' : ''; ?>><a href="<?php the_permalink(); ?>?comments_sort=recent#comments">Sort by most recent</a></li>
  	</ul>
  <?php endif; ?>

	<ol class="comment-list">
		<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
				'avatar_size'=> 42,
				'callback' => 'custom_comment_callback'
			) );
		?>
	</ol><!-- .comment-list -->

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'livingsober' ); ?></h1>
		<div class="nav-previous"><?php echo add_sorting_querystring(get_previous_comments_link( __( '&larr; Previous Comments', 'livingsober' ) ) ); ?></div>
		<div class="nav-next"><?php echo add_sorting_querystring(get_next_comments_link( __( 'Next Comments &rarr;', 'livingsober' ) ) ); ?></div>
	</nav><!-- #comment-nav-below -->
	<?php endif; // Check for comment navigation. ?>

	<?php if ( ! comments_open() ) : ?>
	<p class="no-comments"><?php _e( 'Comments are closed.', 'livingsober' ); ?></p>
	<?php endif; ?>

	<?php endif; // have_comments() ?>
	<?php if ( strpos(get_page_template_slug(), 'sobertools-single' ) === false) : ?>
	<?php comment_form(); ?>
	<?php endif; ?>
</div><!-- #comments -->
