<?php
/**
 * The template for displaying question content
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.0.1
 */

?>
<div class="<?php echo dwqa_post_class(get_the_ID()); ?>">
	<header class="dwqa-question-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></header>
	<div class="dwqa-question-meta">
		<?php dwqa_question_print_status() ?>
		<?php $latest_activity = dwqa_get_latest_activity_info( get_the_ID() ); ?>
		<?php printf( __( '<span><a href="%1$s">%2$s%3$s</a> %4$s %5$s ago</span>', 'dwqa' ), esc_url( $latest_activity['userlink'] ), $latest_activity['useravatar'], $latest_activity['username'], $latest_activity['text'], $latest_activity['time'] ) ?>
		<?php echo get_the_term_list( get_the_ID(), 'dwqa-question_category', '<span class="dwqa-question-category"><span class="dwqa-sep">' . __( '&nbsp;&bull;&nbsp;', 'dwqa' ) . '</span>', ', ', '</span>' ); ?>
	</div>
	<div class="dwqa-question-stats">
		<span class="dwqa-views-count">
			<?php $views_count = dwqa_question_views_count() ?>
			<?php printf( __( '<strong>%1$s</strong> views', 'dwqa' ), $views_count ); ?>
		</span>
		<span class="dwqa-answers-count">
			<?php $answers_count = dwqa_question_answers_count(get_the_ID()); ?>
			<?php printf( __( '<strong>%1$s</strong> answers', 'dwqa' ), $answers_count ); ?>
		</span>
		<span class="dwqa-votes-count">
			<?php $vote_count = dwqa_vote_count() ?>
			<?php printf( __( '<strong>%1$s</strong> votes', 'dwqa' ), $vote_count ); ?>
		</span>
	</div>
</div>
