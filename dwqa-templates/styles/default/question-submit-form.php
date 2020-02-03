<style>
#comments {
    display: none;
}
.dwqa-btn.dwqa-btn-primary {

    width: 300px;
    padding: 10px;

}
.CodeMirror, .CodeMirror-scroll {
    min-height: 150px !important;
}
.dwqa-attachments-description {

    display: none;

}
#question-title {
    height: 60px;
}
.wp-block-spacer {

    display: none;

}
.editor-statusbar {
    display: none;
}
#dwqa-attachments-wrap-button-upload {
    display: none !important;
}
.editor-toolbar {
    display: none;
	visibility: hidden;
}
	
	#qt_question-content_toolbar {
    display: none;
}
	
</style>

<?php
/**
 * The template for displaying single answers
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.0.1
 */
	global $dwqa_shortcode_atts;

	if ( isset( $dwqa_shortcode_atts['category'] ) && $dwqa_shortcode_atts['category'] !='') {
		$dwqa_category = get_term_by('slug', $dwqa_shortcode_atts['category'], 'dwqa-question_category');
	}
?>

<?php do_action( 'dwqa_before_question_submit_form' ); ?>
<?php if ( dwqa_current_user_can( 'post_question' ) ) : ?>
<p>Select category</p>
	<form method="post" class="dwqa-content-ask-form" enctype="multipart/form-data">
    <div style="margin:0px 0px 20px 0px">
    <table width="100%">
    <tr>
    <td width="30%">
     <p><input type="radio" name="question-category" id="Site Help" value="21082"> <span style="font-size:18px;font-weight:bold">Site Help</span></p>
     <p style="font-size: 14px;font-style: italic;">For collecting errors related to the members page.</p>
    </td>
    <td width="5%">
    </td>
    <td width="30%">
     <p> <input type="radio" name="question-category" id="Feature Suggestions" value="21080"> <span style="font-size:18px;font-weight:bold">Feature Suggestions</span></p>
     <p style="font-size: 14px;font-style: italic;">For all your feature suggestions.</p>
    </td>
     <td width="5%">
    </td>
    <td width="30%">
     <p> <input type="radio" name="question-category" id="Technical Issues" value="21081"> <span style="font-size:18px;font-weight:bold">Technical Issues</span></p>
     <p style="font-size: 14px;font-style: italic;">For any problem other then those on the members feed.</p>
    </td>
    </tr>
    </table>
</div>
        
        <p class="dwqa-search">
			<label for="question_title"><?php _e( 'Description', 'dwqa' ) ?></label>
			<?php $title = isset( $_POST['question-title'] ) ? $_POST['question-title'] : ''; ?>
			<input type="text" data-nonce="<?php echo wp_create_nonce( '_dwqa_filter_nonce' ) ?>" id="question-title" name="question-title" value="<?php echo esc_attr( $title ) ?>" tabindex="1">
		</p>
<label for="question_title">Details (optional)</label>
		<?php $content = isset( $_POST['question-content'] ) ? $_POST['question-content'] : ''; ?>
		<p><?php dwqa_init_tinymce_editor( array( 'content' => $content, 'textarea_name' => 'question-content', 'id' => 'question-content' ) ) ?></p>
		<?php global $dwqa_general_settings; ?>
		<?php if ( isset( $dwqa_general_settings['enable-private-question'] ) && $dwqa_general_settings['enable-private-question'] && is_user_logged_in() ) : ?>
		<p>
			<label for="question-status"><?php _e( 'Status', 'dwqa' ) ?></label>
			<select class="dwqa-select" id="question-status" name="question-status">
				<optgroup label="<?php _e( 'Who can see this?', 'dwqa' ) ?>">
					<option value="publish"><?php _e( 'Public', 'dwqa' ) ?></option>
					<option value="private"><?php _e( 'Only Me &amp; Admin', 'dwqa' ) ?></option>
				</optgroup>
			</select>
		</p>
		<?php endif; ?>
		<?php
if( current_user_can('editor') || current_user_can('administrator') ) {
     echo "<p style='display:block !important'>";
} else {
     echo "<p style='display:none'>";
}
?>
			
		<?php
if( current_user_can('editor') || current_user_can('administrator') ) {
     echo "<p style='display:block !important'>";
} else {
     echo "<p style='display:none'>";
}
?>
			<label for="question-tag"><?php _e( 'Tag', 'dwqa' ) ?></label>
			<?php $tags = isset( $_POST['question-tag'] ) ? $_POST['question-tag'] : ''; ?>
			<input type="text" class="dwqa-question-tags" name="question-tag" value="<?php echo esc_attr( $tags ) ?>" >
		</p>
		<?php if ( dwqa_current_user_can( 'post_question' ) && !is_user_logged_in() ) : ?>
		<p>
			<label for="_dwqa_anonymous_email"><?php _e( 'Your Email', 'dwqa' ) ?></label>
			<?php $email = isset( $_POST['_dwqa_anonymous_email'] ) ? $_POST['_dwqa_anonymous_email'] : ''; ?>
			<input type="email" class="dwqa-question-anonymous-email" name="_dwqa_anonymous_email" value="<?php echo sanitize_email( $email ) ?>" >
		</p>
		<p>
			<label for="_dwqa_anonymous_name"><?php _e( 'Your Name', 'dwqa' ) ?></label>
			<?php $name = isset( $_POST['_dwqa_anonymous_name'] ) ? $_POST['_dwqa_anonymous_name'] : ''; ?>
			<input type="text" class="dwqa-question-anonymous-name" name="_dwqa_anonymous_name" value="<?php echo esc_attr( $name ) ?>" >
		</p>
		<?php endif; ?>
		<?php do_action( 'dwqa_before_question_submit_button' ) ?>
		<?php wp_nonce_field( '_dwqa_submit_question' ) ?>
		<?php dwqa_load_template( 'captcha', 'form' ); ?>
		<?php do_action('dwqa_show_captcha_question')?>
		<input type="submit" name="dwqa-question-submit" class="dwqa-btn dwqa-btn-primary" value="<?php _e( 'Post', 'dwqa' ) ?>" >
		<?php do_action( 'dwqa_after_question_submit_button' ) ?>
	</form>
<?php else : ?>
	<?php if ( is_user_logged_in() ) : ?>
		<div><?php _e( "You doesn't have permission to post a question", 'dwqa' ) ?></div>
	<?php else : ?>
		<?php dwqa_load_template( 'login', 'form' ) ?>
	<?php endif; ?>
<?php endif; ?>
<?php do_action( 'dwqa_after_question_submit_form' ); ?>
