<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package kleo-ls
 * @since September 2018
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

?>
<?php if ( sq_option( 'replace_wp_comments_with_fb_comments' ) == 1 ) { ?>

	<section class="container-wrap">
		<div class="container">
			<div id="comments" class="comments-area">
				<div class="fb-comments" data-href="<?php echo get_permalink(); ?>" data-numposts="5" width="100%"></div>
			</div><!-- #comments -->
		</div>
	</section>

	<?php if ( sq_option( 'facebook_login' ) == 0 || is_user_logged_in() ) : ?>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/<?php echo apply_filters('kleo_facebook_js_locale', 'en_US'); ?>/sdk.js#xfbml=1&version=v2.8<?php if ( sq_option( 'fb_app_id' ) ) { echo '&appId=' . sq_option( 'fb_app_id' ); } ?>";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>
	<?php endif; ?>


<?php } else { ?>

	<section class="container-wrap">
		<div class="container">

      <?php if ( strpos(get_page_template_slug(), 'sobertools-single') ) : ?>
        <?php kleo_comment_form(); ?>
      <?php endif; ?>

      <h1>hello dan</h1>

			<div id="comments" class="comments-area">

				<div class="hr-title hr-long"><abbr><?php comments_number( esc_html__( '0 Comments', 'kleo_framework' ), esc_html__( '1 Comment', 'kleo_framework' ), __( '% Comments', 'kleo_framework' ) ); ?></abbr></div>

				<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
					<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'kleo_framework' ); ?></p>
				<?php endif; ?>

				<?php if ( have_comments() ) : ?>
					<div id="comments-list">

            <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
            
              <?php if ( is_plugin_active( 'comment-popularity/comment-popularity.php' ) ) { ?>
                <ul class="sobertools-sort">
                  <li <?php echo !isset($_GET['comments_sort']) ? 'class="selected"' : ''; ?>><a href="<?php the_permalink(); ?>#comments">Sort by most liked</a></li>
                  <li <?php echo $_GET['comments_sort'] == 'recent' ? 'class="selected"' : ''; ?>><a href="<?php the_permalink(); ?>?comments_sort=recent#comments">Sort by most recent</a></li>
                </ul>
              <?php } ?>


							<div id="comments-nav-above" class="comments-navigation" role="navigation">
								<div class="paginated-comments-links clearfix">
									<?php
									paginate_comments_links( array(
										'type' => 'list',
										'prev_text' => esc_html__( 'Previous', 'kleo_framework' ),
										'next_text' => esc_html__( 'Next', 'kleo_framework' ),
									));
									?>
								</div>
							</div><!-- #comments-nav-above -->
						<?php endif; // Check for comment navigation. ?>
						<ol>
							<?php wp_list_comments( 'type=all&callback=kleo_custom_comments' ); ?>
						</ol>

						<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
							<div id="comments-nav-below" class="comments-navigation" role="navigation">
								<div class="paginated-comments-links comments-links-after clearfix">
									<?php
									paginate_comments_links( array(
										'type' => 'list',
										'prev_text' => esc_html__( 'Previous', 'kleo_framework' ),
										'next_text' => esc_html__( 'Next', 'kleo_framework' ),
									) );
									?>
								</div>
							</div><!-- #comments-nav-below -->
						<?php endif; // Check for comment navigation. ?>

						<div class="activity-timeline"></div>
					</div>

				<?php endif; // have_comments() ?>

				<?php if ( strpos(get_page_template_slug(), 'sobertools-single' ) === false) : ?>
          <?php kleo_comment_form(); ?>
        <?php endif; ?>

			</div><!-- #comments -->
		</div>
	</section>

<?php }
