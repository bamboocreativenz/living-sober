<?php

/**
 * BuddyPress - Users Header
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

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
	<!-- DL Not sure if we need below -->
	<!-- <?php do_action('bp_member_online_status', bp_displayed_user_id()); ?> -->
</div><!-- #item-header-avatar -->

<div id="item-header-content">

	<?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
		<h4 class="user-nicename hover-tip click-tip" data-toggle="tooltip" data-container="body" data-title="<?php bp_last_activity( bp_displayed_user_id() ); ?>" data-placement="bottom">@<?php bp_displayed_user_mentionname(); ?></h4>
	<?php endif; ?>

	<?php if ( function_exists( 'bp_core_iso8601_date' ) ) : ?>
		<!--<span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_user_last_activity( bp_displayed_user_id() ) ); ?>"><?php bp_last_activity( bp_displayed_user_id() ); ?></span> -->
	<?php else : ?>
	<!--<span class="activity"><?php bp_last_activity( bp_displayed_user_id() ); ?></span> -->
	<?php endif; ?>

	<?php

	/**
	 * Fires before the display of the member's header meta.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_member_header_meta' ); ?>

	<div id="item-meta">

		<?php if ( bp_is_active( 'activity' ) ) : ?>

			<div id="latest-update">

				<?php bp_activity_latest_update( bp_displayed_user_id() ); ?>

				<!-- DL: Show the users profile in the header -->
				<h5>
					<?php
						$args = array(
							'field'     => 2,
								'user_id'   => bp_displayed_user_id()
						);
				  	    bp_profile_field_data( $args );
				    ?>	
				</h5>

			</div>

		<?php endif; ?>

		<div id="item-buttons"><?php

			/**
			 * Fires in the member header actions section.
			 *
			 * @since 1.2.6
			 */
			do_action( 'bp_member_header_actions' ); ?></div><!-- #item-buttons -->

		<?php

		/**
		 * Fires after the group header actions section.
		 *
		 * If you'd like to show specific profile fields here use:
		 * bp_member_profile_data( 'field=About Me' ); -- Pass the name of the field
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_profile_header_meta' );

		?>

	</div><!-- #item-meta -->

</div><!-- #item-header-content -->

<?php

/**
 * Fires after the display of a member's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_header' ); ?>

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

<div id="template-notices" role="alert" aria-atomic="true">
	<?php

	/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
	do_action( 'template_notices' ); ?>

</div>
