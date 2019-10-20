<?php
/**
 * The template for displaying answers
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.0.1
 */

global $dwqa_general_settings;
$sort = isset( $_GET['sort'] ) ? $_GET['sort'] : '';
$filter = isset( $_GET['filter'] ) ? $_GET['filter'] : 'all';
?>
<?php
if( current_user_can('editor') || current_user_can('administrator') ) {
     echo "<div class='dwqa-question-filter' style='display:block !important'>";
} else {
     echo "<div class='dwqa-question-filter' style='display:none'>";
}
?>

<span><?php _e( 'Filter:', 'dwqa' ); ?></span>
	<?php if ( !isset( $_GET['user'] ) ) : ?>
		<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'all' ) ) ) ?>" class="all <?php echo 'all' == $filter ? 'active' : '' ?>"><?php _e( 'All', 'dwqa' ); ?></a>
		<?php if ( dwqa_is_enable_status() ) : ?>
			<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'open' ) ) ) ?>" class="open <?php echo 'open' == $filter ? 'active' : '' ?>"><?php _e( 'Open', 'dwqa' ); ?></a>
			<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'resolved' ) ) ) ?>" class="resolved <?php echo 'resolved' == $filter ? 'active' : '' ?>"><?php _e( 'Resolved', 'dwqa' ); ?></a>
			<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'closed' ) ) ) ?>" class="closed <?php echo 'closed' == $filter ? 'active' : '' ?>"><?php _e( 'Closed', 'dwqa' ); ?></a>
		<?php endif; ?>
		<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'unanswered' ) ) ) ?>" class="unanswered <?php echo 'unanswered' == $filter ? 'active' : '' ?>"><?php _e( 'Unanswered', 'dwqa' ); ?></a>
		<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'my-questions' ) ) ) ?>" class="my-questions <?php echo 'my-questions' == $filter ? 'active' : '' ?>"><?php _e( 'My questions', 'dwqa' ); ?></a>
			<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'my-subscribes' ) ) ) ?>" class="my-subscribes <?php echo 'my-subscribes' == $filter ? 'active' : '' ?>"><?php _e( 'My subscribes', 'dwqa' ); ?></a>
		<?php endif; ?>
	<?php else : ?>
		<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'all' ) ) ) ?>" class="all <?php echo 'all' == $filter ? 'active' : '' ?>"><?php _e( 'Questions', 'dwqa' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'subscribes' ) ) ) ?>" class="subscribes <?php echo 'subscribes' == $filter ? 'active' : '' ?>"><?php _e( 'Subscribes', 'dwqa' ); ?></a>
	<?php endif; ?>
	<select id="dwqa-sort-by" class="dwqa-sort-by" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
		<option selected disabled><?php _e( 'Sort by', 'dwqa' ); ?></option>
		<option <?php selected( $sort, 'views' ) ?> value="<?php echo esc_url( add_query_arg( array( 'sort' => 'views' ) ) ) ?>"><?php _e( 'Views', 'dwqa' ) ?></option>
		<option <?php selected( $sort, 'answers' ) ?> value="<?php echo esc_url( add_query_arg( array( 'sort' => 'answers' ) ) ) ?>"><?php _e( 'Answers', 'dwqa' ); ?></option>
		<option <?php selected( $sort, 'votes' ) ?> value="<?php echo esc_url( add_query_arg( array( 'sort' => 'votes' ) ) ) ?>"><?php _e( 'Votes', 'dwqa' ) ?></option>
	</select>
</div>