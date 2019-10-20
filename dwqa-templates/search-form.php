<form id="dwqa-search" class="dwqa-search">
	<input data-nonce="<?php echo wp_create_nonce( '_dwqa_filter_nonce' ) ?>" type="text" placeholder="<?php _e( 'What do you want to know?', 'dwqa' ); ?>" name="qs" value="<?php echo isset( $_GET['qs'] ) ? esc_attr(sanitize_text_field(str_replace('\\', '', $_GET['qs']))) : '' ?>">
</form>