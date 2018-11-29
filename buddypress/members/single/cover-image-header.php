<?php

/**
 * BuddyPress - Cover Header
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>
	<div id="header-cover-image"></div>
<?php

/**
 * Fires before the display of a member's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_header' ); ?>

	<div id="item-header-avatar" class="rounded">
		<a href="<?php bp_displayed_user_link(); ?>">

			<?php bp_displayed_user_avatar( 'type=full' ); ?>

		</a>
		<?php do_action( 'bp_member_online_status', bp_displayed_user_id() ); ?>
	</div><!-- #item-header-avatar -->

	<div id="item-header-content">

		<?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
			<h4 class="user-nicename hover-tip click-tip" data-toggle="tooltip" data-container="body" data-title="<?php bp_last_activity( bp_displayed_user_id() ); ?>" data-placement="bottom">@<?php bp_displayed_user_mentionname(); ?></h4>
		<?php endif; ?>

		<!--<span class="activity"><?php /*bp_last_activity( bp_displayed_user_id() ); */?></span>-->

		<?php do_action( 'bp_before_member_header_meta' ); ?>

		<div id="item-meta">

			<?php if ( bp_is_active( 'activity' ) ) : ?>

				<div id="latest-update">

				<?php 
					$args = array(
						'field'     => 2,
						'user_id'   => bp_displayed_user_id()
					);
			  	bp_profile_field_data( $args );
			  ?>

				</div>

			<?php endif; ?>

			<div id="item-buttons"><?php do_action( 'bp_member_header_actions' ); ?></div><!-- #item-buttons -->

			<?php
			/***
			 * If you'd like to show specific profile fields here use:
			 * bp_member_profile_data( 'field=About Me' ); -- Pass the name of the field
			 */
			do_action( 'bp_profile_header_meta' );

			?>

		</div><!-- #item-meta -->

	</div><!-- #item-header-content -->


<?php do_action( 'bp_after_member_header' ); ?>

<?php if ( sq_option( 'bp_nav_overlay', 0 ) == 1 ) : ?>
<div id="item-nav">
	<div class="item-list-tabs no-ajax" id="object-nav" aria-label="<?php esc_attr_e( 'Member primary navigation', 'buddypress' ); ?>" role="navigation">
		<ul class="responsive-tabs">
			
			<?php bp_get_displayed_user_nav(); ?>
			
			<?php
			
			/**
			 * Fires after the display of member options navigation.
			 *
			 * @since 1.2.4
			 */
			do_action( 'bp_member_options_nav' ); ?>

		</ul>
	</div>
</div>
<!-- #item-nav -->
<?php endif; ?>

<?php do_action( 'template_notices' ); ?>

<script>
	(function($) {
		$(document).ready(function () {
			$(document).ajaxComplete(function (event, xhr, settings) {
				if(settings.data){
					if(settings.data.indexOf("action=bp_cover_image_delete") != -1){
						$('body').removeClass('is-user-profile');
					}
				}
			});
			if(typeof(bp) !== 'undefined' && typeof(bp.Uploader) !== 'undefined' && typeof(bp.Uploader.filesQueue) !== 'undefined'){
				bp.Uploader.filesQueue.on( 'add', function(){
					$('body').addClass('is-user-profile');
				});
			}
		});
	})(jQuery);
</script>
