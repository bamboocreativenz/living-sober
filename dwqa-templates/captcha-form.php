<?php
/**
 * The template for displaying captcha form
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.0.1
 */
?>
<?php global $dwqa_general_settings; ?>
<?php if ( ( 'dwqa-question' == get_post_type() && dwqa_is_captcha_enable_in_single_question() ) || ( dwqa_is_ask_form() && dwqa_is_captcha_enable_in_submit_question() ) ) : ?>
<div class="dwqa-captcha">
	<?php if ( 'default' == dwqa_current_captcha_selected() ) : ?>
		<?php 
		$number_1 = mt_rand( 0, 20 );
		$number_2 = mt_rand( 0, 20 );
		?>
		<span class="dwqa-number-one"><?php echo esc_attr( $number_1 ) ?></span>
		<span class="dwqa-plus">&#43;</span>
		<span class="dwqa-number-one"><?php echo esc_attr( $number_2 ) ?></span>
		<span class="dwqa-plus">&#61;</span>
		<input type="text" name="dwqa-captcha-result" id="dwqa-captcha-result" value="" placeholder="<?php _e( 'Enter the result', 'dwqa' ) ?>">
		<input type="hidden" name="dwqa-captcha-number-1" id="dwqa-captcha-number-1" value="<?php echo esc_attr( $number_1 ) ?>">
		<input type="hidden" name="dwqa-captcha-number-2" id="dwqa-captcha-number-2" value="<?php echo esc_attr( $number_2 ) ?>">
	<?php elseif ( 'google-captcha-v2' == dwqa_current_captcha_selected() ) : ?>
		<?php
		$public_key = isset( $dwqa_general_settings['captcha-google-public-key'] ) ?  $dwqa_general_settings['captcha-google-public-key'] : '';
		echo '<div class="google-recaptcha">';
		//wp_enqueue_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js', array() );
		//echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
		echo '<div class="g-recaptcha" data-sitekey="'. $public_key .'"></div>';
		echo '<br></div>';
		?>
	<?php elseif ( 'funcaptcha' == dwqa_current_captcha_selected() ) : ?>
		<?php
		$public_key = isset( $dwqa_general_settings['funcaptcha-public-key'] ) ?  $dwqa_general_settings['funcaptcha-public-key'] : '';
		echo '<div class="google-recaptcha">';
		wp_enqueue_script( 'funcaptcha', 'https://funcaptcha.com/fc/api/', array() );
		echo '<script src="https://funcaptcha.com/fc/api/" async defer></script>';
		echo '<div id="funcaptcha" data-pkey="'.$public_key.'"></div>';
		echo '<br></div>';
		?>
	<?php endif; ?>
</div>
<?php endif; ?>