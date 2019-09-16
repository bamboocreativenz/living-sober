<?php
$link_register = wp_registration_url();
$dwqa_register_page = dwqa_register_page();
if(is_numeric($dwqa_register_page) && $dwqa_register_page > 0){
	$link_register = get_permalink($dwqa_register_page);
}


?>
<div class="dwqa-answers-login">
	<div class="dwqa-answers-login-title">
		<?php
			if ( is_singular( 'dwqa-question' )):?>
				<p>
					<?php printf( __( 'Please login or %1$sRegister%2$s to submit your answer', 'dwqa' ), '<a href="'.$link_register.'">', '</a>' ) ?>
				</p>
			<?php else: ?>
				<p>
					<?php printf( __( 'Please login or %1$sRegister%2$s to submit your question', 'dwqa' ), '<a href="'.$link_register.'">', '</a>' ) ?>
				</p>
			<?php endif;
		?>
		
	</div>
	<div class="dwqa-answers-login-content">
		<?php wp_login_form(); ?>
		<?php do_action( 'wordpress_social_login' ); ?>
	</div>
</div>
