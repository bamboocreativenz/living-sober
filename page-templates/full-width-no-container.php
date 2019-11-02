<?php
/**
 * Template Name: Full-width No Container
 *
 * Description: Template for other builders than VC
 *
 * @package WordPress
 * @subpackage Kleo
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 4.2.6
 */

get_header(); ?>

<?php
//create full width template
kleo_switch_layout('no');
add_filter( 'kleo_main_container_class', 'kleo_ret_full_container' );
?>

<?php get_template_part('page-parts/general-title-section'); ?>

<?php get_template_part('page-parts/general-before-wrap'); ?>

<?php
if ( have_posts() ) :
	// Start the Loop.
	while ( have_posts() ) : the_post();

		/*
		 * Include the post format-specific template for the content. If you want to
		 * use this in a child theme, then include a file called called content-___.php
		 * (where ___ is the post format) and that will be used instead.
		 */
		get_template_part( 'content', 'page' );
        ?>

		<?php if ( sq_option( 'page_comments', 0 ) == 1 ): ?>

			<!-- Begin Comments -->
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template( '', true );
			} ?>
			<!-- End Comments -->

		<?php endif; ?>

		<?php get_template_part( 'page-parts/posts-social-share' ); ?>

	<?php endwhile;

endif;
?>
        
<?php get_template_part('page-parts/general-after-wrap'); ?>

<?php get_footer(); ?>


<script src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>

// Plugins
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
  crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>


<script>  
  var tag = document.createElement('script');
  tag.src = "https://www.youtube.com/iframe_api";
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

  var player;
  onYouTubeIframeAPIReady = function () {
    player = new window.YT.Player('player', {
      playerVars: {
        'origin': 'https://livingsober.org.nz',
        'autoplay': 0,
        'loop': 1,
        'controls': 0,
        'showinfo': 0,
        'modestbranding': 1
     },
      events: {
        'onStateChange': onPlayerStateChange
      }
    });
  }
  
  var p = document.getElementById ("player");
  $(p).hide();
  
  onPlayerStateChange = function (event) {
    if(event.data === 0) {          
      $("#overlay-container").fadeIn(800);
      $("#player").fadeOut(400);
      player.pauseVideo();
    }
    if (event.data == YT.PlayerState.ENDED) {
      $('.start-video').fadeIn('normal');
    }
  }
  
  $(document).on('click', '.start-video', function () {
    player.playVideo();
    $("#player").fadeIn();
    $("#overlay-container").fadeOut(1200);
  });
  </script>
