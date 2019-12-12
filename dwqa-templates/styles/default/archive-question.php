<style>
.dwqa-questions-list .dwqa-question-item {
    border-bottom: none !important;
    border-left: none !important;
    border-right: none !important;
}
	
	.dwqa-questions-list .dwqa-question-item .dwqa-question-stats span {border:none !important}
</style>

<?php
/**
 * The template for displaying question archive pages
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.0.1
 */
/* if ( isset( $_GET['test'] ) ) {
	global $wp_query;
	print_r( $wp_query->dwqa_questions );
} */
?>
<div class="dwqa-questions-archive">
	<?php do_action( 'dwqa_before_questions_archive' ) ?>
	
		<div class="dwqa-questions-list <?php echo !dwqa_is_enable_status()?'hidden-status':''?>">
		<?php do_action( 'dwqa_before_questions_list' ) ?>
		<?php if ( dwqa_has_question() ) : ?>
			<?php while ( dwqa_has_question() ) : dwqa_the_question(); ?>
				<?php if ( get_post_status() == 'publish' || ( ( get_post_status() == 'private' || get_post_status() == 'spam' ) && ( dwqa_current_user_can( 'edit_question', get_the_ID() ) || dwqa_current_user_can( 'manage_question' ) || get_current_user_id() == get_post_field( 'post_author', get_the_ID() ) ) ) ) : ?>
					<?php dwqa_load_template( 'content', 'question' ) ?>
				<?php endif; ?>
			<?php endwhile; ?>
		<?php else : ?>
			<?php dwqa_load_template( 'content', 'none' ) ?>
		<?php endif; ?>
		<?php do_action( 'dwqa_after_questions_list' ) ?>
		</div>
		<div class="dwqa-questions-footer">
			<?php dwqa_question_paginate_link() ?>
			<?php if ( dwqa_current_user_can( 'post_question' ) ) : ?>
				<div class="dwqa-ask-question"><a href="<?php echo dwqa_get_ask_link(); ?>"><?php _e( 'Request Help', 'dwqa' ); ?></a> <a href="<?php echo dwqa_get_ask_link(); ?>"><?php _e( 'Share Idea', 'dwqa' ); ?></a></div>
			<?php endif; ?>
		</div>

	<?php do_action( 'dwqa_after_questions_archive' ); ?>
</div>
