<?php

/**
 * Get related questions            [description]
 */
function dwqa_related_question( $question_id = false, $number = 5, $echo = true , $hide_user = false, $hide_date = false) {
	if ( ! $question_id ) {
		$question_id = get_the_ID();
	}
	$tag_in = $cat_in = array();
	$tags = wp_get_post_terms( $question_id, 'dwqa-question_tag' );
	if ( ! empty($tags) ) {
		foreach ( $tags as $tag ) {
			$tag_in[] = $tag->term_id;
		}
	}

	$category = wp_get_post_terms( $question_id, 'dwqa-question_category' );
	if ( ! empty($category) ) {
		foreach ( $category as $cat ) {
			$cat_in[] = $cat->term_id;
		}
	}
	$args = array(
		'orderby'       => 'rand',
		'post__not_in'  => array($question_id),
		'showposts'     => $number,
		'ignore_sticky_posts' => 1,
		'post_type'     => 'dwqa-question',
	);

	$args['tax_query']['relation'] = 'OR';
	if ( ! empty( $cat_in ) ) {
		$args['tax_query'][] = array(
			'taxonomy'  => 'dwqa-question_category',
			'field'     => 'id',
			'terms'     => $cat_in,
			'operator'  => 'IN',
		);
	}
	if ( ! empty( $tag_in ) ) {
		$args['tax_query'][] = array(
			'taxonomy'  => 'dwqa-question_tag',
			'field'     => 'id',
			'terms'     => $tag_in,
			'operator'  => 'IN',
		);
	}

	$related_questions = new WP_Query( $args );

	if ( $related_questions->have_posts() ) {
		if ( $echo ) {
			echo '<ul>';
			while ( $related_questions->have_posts() ) { $related_questions->the_post();
				echo '<li><a href="'.get_permalink().'" class="question-title">'.get_the_title().'</a>';
				if(!$hide_user){
					echo __( ' asked by', 'dwqa' ).' '.get_the_author_posts_link();
				}
				if(!$hide_date){
					echo ', ' .  sprintf( esc_html__( '%s ago', 'dwqa' ), human_time_diff( get_post_time('U', true) ) );
				}
				echo '</li>';
			}
			echo '</ul>';
		}
	}
	$posts = $related_questions->posts;
	wp_reset_postdata();
	return $posts;
}

function dwqa_get_latest_activity_info( $question_id ) {
	if ( 'dwqa-question' !== get_post_type( $question_id ) ) {
		return false;;
	}

	$latest_activity = get_post_meta( $question_id, '_latest_activity', true );
	$latest_answer = dwqa_get_latest_answer( $question_id );

	if($latest_activity && isset( $latest_activity['act_id'] ) && isset( $latest_activity['user_id'] )){
		$post_status = get_post_status ( $latest_activity['act_id'] );
	}
	if ( isset( $post_status ) && $post_status && $post_status !='trash') {
		$user_id = absint( $latest_activity['user_id'] );
		$act_id = absint( $latest_activity['act_id'] );
		$text = esc_html( dwqa_get_latest_activity_text( $latest_activity['text'] ) );

		// is answer
		if ( $latest_activity['text'] == 'answered' ) {
			$is_anonymous = dwqa_is_anonymous( $act_id );
			$time = human_time_diff( get_post_time( 'U', true, $act_id ));
			if ( $is_anonymous ) {
				$username = get_post_meta( $act_id, '_dwqa_anonymous_name', true );
				$username = $username ? esc_html( $username ) : __( 'Anonymous', 'dwqa' );
				$user_email = get_post_meta( $act_id, '_dwqa_anonymous_email', true );
				$user_email = $user_email ? sanitize_email( $user_email ) : false;
				$avatar = $user_email ? get_avatar( $user_email, 48 ) : get_avatar( 0, 48 );
				$link = '#';
			} else {
				$username = get_the_author_meta( 'display_name', $user_id );
				$avatar = get_avatar( $user_id, 48 );
				$link = bp_core_get_user_domain( $user_id );
			}
		}

		// is comment
		if ( $latest_activity['text'] == 'commented' ) {
			$comment = get_comment( $act_id );
			$username = get_comment_author( $act_id );
			$user_email = get_comment_author_email( $act_id );
			if ( $comment->user_id ) {
				$user_email = get_the_author_meta( 'user_email', $comment->user_id );
			} else {
				$user_email = $comment->comment_author_email;
			}
			$avatar = get_avatar( $user_email, 48 );
			$link = dwqa_get_author_link( $comment->user_id );
			$time = human_time_diff( strtotime($comment->comment_date_gmt) );
		}
	} else if ( $latest_answer ) {
		$is_anonymous = dwqa_is_anonymous( $latest_answer->ID );
		$text = esc_html( dwqa_get_latest_activity_text( 'answered' ) );
		$time = human_time_diff( get_post_time( 'U', true, $latest_answer->ID ));
		if ( $is_anonymous ) {
			$username = get_post_meta( $latest_answer->ID, '_dwqa_anonymous_name', true );
			$username = $username ? esc_html( $username ) : __( 'Anonymous', 'dwqa' );
			$user_email = get_post_meta( $latest_answer->ID, '_dwqa_anonymous_email', true );
			$user_email = $user_email ? sanitize_email( $user_email ) : false;
			$avatar = $user_email ? get_avatar( $user_email, 48 ) : get_avatar( 0, 48 );
			$link = '#';
		} else {
			$username = get_the_author_meta( 'display_name', $latest_answer->post_author );
			$avatar = get_avatar( $latest_answer->post_author, 48 );
			$link = dwqa_get_author_link( $latest_answer->post_author );
		}
	} else {
		$is_anonymous = dwqa_is_anonymous( $question_id );
		$user_id = get_post_field( 'post_author', $question_id );
		$text = __( 'asked', 'dwqa' );
		$time = human_time_diff( get_post_time( 'U', true, $question_id ));
		if ( $is_anonymous ) {
			$username = get_post_meta( $question_id, '_dwqa_anonymous_name', true );
			$username = $username ? esc_html( $username ) : __( 'Anonymous', 'dwqa' );
			$user_email = get_post_meta( $question_id, '_dwqa_anonymous_email', true );
			$user_email = $user_email ? sanitize_email( $user_email ) : false;
			$avatar = $user_email ? get_avatar( $user_email, 48 ) : get_avatar( 0, 48 );
			$link = '#';
		} else {
			$username = get_the_author_meta( 'display_name', $user_id );
			$avatar = get_avatar( $user_id, 48 );
			$link = bp_core_get_user_domain( $user_id );
		}
	}

	return array(
		'username' => $username,
		'userlink' => $link,
		'useravatar' => $avatar,
		'time' => $time,
		'text' => $text
	);
}

/**
 * Count number of views for a questions
 * @param  int $question_id Question Post ID
 * @return int Number of views
 */
function dwqa_question_views_count( $question_id = null ) {
	if ( ! $question_id ) {
		global $post;
		$question_id = $post->ID;
		if ( isset( $post->view_count ) ) {
			return $post->view_count;
		}
	}
	$views = get_post_meta( $question_id, '_dwqa_views', true );

	if ( ! $views ) {
		return 0;
	} else {
		return ( int ) $views;
	}
}

class DWQA_Posts_Question extends DWQA_Posts_Base {

	public function __construct() {
		global $dwqa_general_settings;

		if ( !$dwqa_general_settings ) {
			$dwqa_general_settings = get_option( 'dwqa_options' );
		}
		$rewrite_slug = $this->get_question_rewrite();

		parent::__construct( 'dwqa-question', array(
			'plural' => __( 'Questions', 'dwqa' ),
			'singular' => __( 'Question', 'dwqa' ),
			'menu'	 => __( 'Questions', 'dwqa' ),
			'rewrite' => array( 'slug' => $rewrite_slug, 'with_front' => false ),
		) );

		add_action( 'manage_dwqa-question_posts_custom_column', array( $this, 'columns_content' ), 10, 2 );

		// Update view count of question, if we change single question template into shortcode, this function will need to be rewrite
		add_action( 'wp_head', array( $this, 'update_view' ) );
		//Ajax Get Questions Archive link

		add_action( 'wp_ajax_dwqa-get-questions-permalink', array( $this, 'get_questions_permalink') );
		add_action( 'wp_ajax_nopriv_dwqa-get-questions-permalink', array( $this, 'get_questions_permalink') );
		//Ajax stick question
		add_action( 'wp_ajax_dwqa-stick-question', array( $this, 'stick_question' ) );
		add_action( 'restrict_manage_posts', array( $this, 'admin_posts_filter_restrict_manage_posts' ) );

		
		// Ajax Update question status
		add_filter( 'parse_query', array( $this, 'posts_filter' ) );

		add_action( 'wp', array( $this, 'schedule_events' ) );
		add_action( 'dwqa_hourly_event', array( $this, 'do_this_hourly' ) );
		add_action( 'before_delete_post', array( $this, 'hook_on_remove_question' ) );

		//Prepare question content
		add_filter( 'dwqa_prepare_question_content', array( $this, 'pre_content_kses' ), 10 );
		add_filter( 'dwqa_prepare_question_content', array( $this, 'pre_content_filter'), 20 );
		add_filter( 'dwqa_prepare_update_question', array( $this, 'pre_content_kses'), 10 );
		add_filter( 'dwqa_prepare_update_question', array( $this, 'pre_content_filter'), 20 );
	}


	public function set_supports() {
		return array( 'title', 'editor', 'comments', 'author', 'page-attributes' );
	}

	public function set_rewrite() {
		global $dwqa_general_settings;
		if( isset( $dwqa_general_settings['question-rewrite'] ) ) {
			return array(
				'slug' => $dwqa_general_settings['question-rewrite'],
				'with_front' => false,
			);
		}
		return array(
			'slug' => 'question',
			'with_front' => false,
		);
	}

	public function get_question_rewrite() {
		global $dwqa_general_settings;

		if ( !$dwqa_general_settings ) {
			$dwqa_general_settings = get_option( 'dwqa_options' );
		}

		return isset( $dwqa_general_settings['question-rewrite'] ) && !empty( $dwqa_general_settings['question-rewrite'] ) ? $dwqa_general_settings['question-rewrite'] : 'question';
	}

	public function get_category_rewrite() {
		global $dwqa_general_settings;

		if ( !$dwqa_general_settings ) {
			$dwqa_general_settings = get_option( 'dwqa_options' );
		}

		return isset( $dwqa_general_settings['question-category-rewrite'] ) && !empty( $dwqa_general_settings['question-category-rewrite'] ) ? $dwqa_general_settings['question-category-rewrite'] : 'dwqa-question_category';
	}

	public function get_array_permalink(){
		$question_base = $this->get_question_rewrite();
		$category_base = $this->get_category_rewrite();

		$array_permalink = array(
			'question_permalink_1' => array(
				'sample_permalink' => home_url($question_base.'/question-name'),
			),
			'question_permalink_2' => array(
				'sample_permalink' => home_url($question_base.'/category-name/question-name'),
			),
			'question_permalink_3' => array(
				'sample_permalink' => home_url($question_base.'/'.$category_base.'/category-name/question-name'),
			),
			'question_permalink_4' => array(
				'sample_permalink' => home_url($category_base.'/category-name/question-name'),
			)
		);
		return apply_filters('dwqa_get_array_permalink', $array_permalink);
	}

	public function get_tag_rewrite() {
		global $dwqa_general_settings;

		if ( !$dwqa_general_settings ) {
			$dwqa_general_settings = get_option( 'dwqa_options' );
		}

		return isset( $dwqa_general_settings['question-tag-rewrite'] ) && !empty( $dwqa_general_settings['question-tag-rewrite'] ) ? $dwqa_general_settings['question-tag-rewrite'] : 'dwqa-question_tag';
	}

	public function register_taxonomy() {
		global $dwqa_general_settings;

		if ( !$dwqa_general_settings ) {
			$dwqa_general_settings = get_option( 'dwqa_options' );
		}

		$cat_slug = $this->get_category_rewrite();
		$tag_slug = $this->get_tag_rewrite();

		$labels = array(
			'name'              => _x( 'Question Categories', 'taxonomy general name', 'dwqa' ),
			'singular_name'     => _x( 'Question Category', 'taxonomy singular name', 'dwqa' ),
			'search_items'      => __( 'Search Question Categories', 'dwqa' ),
			'all_items'         => __( 'All Question Categories', 'dwqa' ),
			'parent_item'       => __( 'Parent Question Category', 'dwqa' ),
			'parent_item_colon' => __( 'Parent Question Category:', 'dwqa' ),
			'edit_item'         => __( 'Edit Question Category', 'dwqa' ),
			'update_item'       => __( 'Update Question Category', 'dwqa' ),
			'add_new_item'      => __( 'Add New Question Category', 'dwqa' ),
			'new_item_name'     => __( 'New Question Category Name', 'dwqa' ),
			'menu_name'         => __( 'Question Category', 'dwqa' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_admin_column' => false,
			'hierarchical'      => true,
			'show_tagcloud'     => true,
			'show_ui'           => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => $cat_slug, 'with_front' => false, 'hierarchical' =>false),
			'query_var'         => true,
			'capabilities'      => array(),
		);
		register_taxonomy( 'dwqa-question_category', array( $this->get_slug() ), $args );

		$labels = array(
			'name'                       => _x( 'Question Tags', 'taxonomy general name', 'dwqa' ),
			'singular_name'              => _x( 'Question Tag', 'taxonomy singular name', 'dwqa' ),
			'search_items'               => __( 'Search Question Tags', 'dwqa' ),
			'popular_items'              => __( 'Popular Question Tags', 'dwqa' ),
			'all_items'                  => __( 'All Question Tags', 'dwqa' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Question Tag', 'dwqa' ),
			'update_item'                => __( 'Update Question Tag', 'dwqa' ),
			'add_new_item'               => __( 'Add New Question Tag', 'dwqa' ),
			'new_item_name'              => __( 'New Question Tag Name', 'dwqa' ),
			'separate_items_with_commas' => __( 'Separate question tags with commas', 'dwqa' ),
			'add_or_remove_items'        => __( 'Add or remove question tags', 'dwqa' ),
			'choose_from_most_used'      => __( 'Choose from the most used question tags', 'dwqa' ),
			'not_found'                  => __( 'No question tags found.', 'dwqa' ),
			'menu_name'                  => __( 'Question Tags', 'dwqa' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_admin_column' => false,
			'hierarchical'      => false,
			'show_tagcloud'     => true,
			'show_ui'           => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => $tag_slug, 'with_front' => false, 'hierarchical' =>false),
			'query_var'         => true,
			'capabilities'      => array(),
		);
		register_taxonomy( 'dwqa-question_tag', array( $this->get_slug() ), $args );

		// Create default category for dwqa question type when dwqa plugin is actived
		$cats = get_categories( array(
			'type'                     => $this->get_slug(),
			'hide_empty'               => 0,
			'taxonomy'                 => $this->get_slug() . '_category',
		) );

		if ( empty( $cats ) ) {
			wp_insert_term( __( 'Questions', 'dwqa' ), $this->get_slug() . '_category' );
		}

		// global $dwqa;
		// $dwqa->rewrite->update_term_rewrite_rules();
	}

	// ADD NEW COLUMN
	public function columns_head( $defaults ) {
		if ( isset( $_GET['post_type'] ) && esc_html( $_GET['post_type'] ) == $this->get_slug() ) {
			$defaults['info'] = __( 'Info', 'dwqa' );
			$defaults = dwqa_array_insert( $defaults, array( 'question-category' => 'Category', 'question-tag' => 'Tags' ), 1 );
		}
		return $defaults;
	}

	// SHOW THE FEATURED IMAGE
	public function columns_content( $column_name, $post_ID ) {
		switch ( $column_name ) {
			case 'info':
				echo ucfirst( get_post_meta( $post_ID, '_dwqa_status', true ) ) . '<br>';
				echo '<strong>'.dwqa_question_answers_count( $post_ID ) . '</strong> '.__( 'answered', 'dwqa' ) . '<br>';
				echo '<strong>'.dwqa_vote_count( $post_ID ).'</strong> '.__( 'voted', 'dwqa' ) . '<br>';
				echo '<strong>'.dwqa_question_views_count( $post_ID ).'</strong> '.__( 'views', 'dwqa' ) . '<br>';
				break;
			case 'question-category':
				$terms = wp_get_post_terms( $post_ID, 'dwqa-question_category' );
				$i = 0;
				foreach ( $terms as $term ) {
					if ( $i > 0 ) {
						echo ', ';
					}
					echo '<a href="'.get_term_link( $term, 'dwqa-question_category' ).'">'.$term->name . '</a> ';
					$i++;
				}
				break;
			case 'question-tag':
				$terms = wp_get_post_terms( $post_ID, 'dwqa-question_tag' );
				$i = 0;
				foreach ( $terms as $term ) {
					if ( $i > 0 ) {
						echo ', ';
					}
					echo '<a href="'.get_term_link( $term, 'dwqa-question_tag' ).'">' . $term->name . '</a> ';
					$i++;
				}
				break;
		}
	}

	public function get_edit_link( $args, $label, $class = '' ) {
		$url = add_query_arg( $args, 'edit.php' );

		$class_html = '';
		if ( ! empty( $class ) ) {
			 $class_html = sprintf(
				' class="%s"',
				esc_attr( $class )
			);
		}

		return sprintf(
			'<a href="%s"%s>%s</a>',
			esc_url( $url ),
			$class_html,
			$label
		);
	}
	
	/**
	 * Init or increase views count for single question
	 * @return void
	 */
	public function update_view() {
		global $post;
		if ( is_singular( 'dwqa-question' ) ) {
			$refer = wp_get_referer();
			if ( is_user_logged_in() ) {
				global $current_user;
				//save who see this post
				$viewed = get_post_meta( $post->ID, '_dwqa_who_viewed', true );
				$viewed = ! is_array( $viewed ) ? array() : $viewed;
				$viewed[$current_user->ID] = current_time( 'timestamp' );
			}

			if ( ( $refer && $refer != get_permalink( $post->ID ) ) || ! $refer ) {
				if ( is_single() && 'dwqa-question' == get_post_type() ) {
					$views = get_post_meta( $post->ID, '_dwqa_views', true );

					if ( ! $views ) {
						$views = 1;
					} else {
						$views = ( ( int ) $views ) + 1;
					}
					update_post_meta( $post->ID, '_dwqa_views', $views );
				}
			}
		}
	}

	public function get_questions_permalink() {
		if ( isset( $_GET['params'] ) ) {
			global $dwqa_options;
			$params = explode( '&', sanitize_text_field( $_GET['params'] ) );
			$args = array();
			if ( ! empty( $params ) ) {
				foreach ( $params as $p ) {
					if ( $p ) {
						$arr = explode( '=', $p );
						$args[$arr[0]] = $arr[1];
					}
				}
			}

			if ( ! empty( $args ) ) {
				$url = get_permalink( $dwqa_options['pages']['archive-question'] );
				$url = $url ? $url : get_post_type_archive_link( 'dwqa-question' );

				$question_tag_rewrite = $dwqa_options['question-tag-rewrite'];
				$question_tag_rewrite = $question_tag_rewrite ? $question_tag_rewrite : 'question-tag';
				if ( isset( $args[$question_tag_rewrite] ) ) {
					if ( isset( $args['dwqa-question_tag'] ) ) {
						unset( $args['dwqa-question_tag'] );
					}
				}

				$question_category_rewrite = $dwqa_options['question-category-rewrite'];
				$question_category_rewrite = $question_category_rewrite ? $question_category_rewrite : 'question-category';

				if ( isset( $args[$question_category_rewrite] ) ) {
					if ( isset( $args['dwqa-question_category'] ) ) {
						unset( $args['dwqa-question_category'] );
					}
					$term = get_term_by( 'slug', $args[$question_category_rewrite], 'dwqa-question_category' );
					unset( $args[$question_category_rewrite] );
					$url = get_term_link( $term, 'dwqa-question_category' );
				} else {
					if ( isset( $args[$question_tag_rewrite] ) ) {
						$term = get_term_by( 'slug', $args[$question_tag_rewrite], 'dwqa-question_tag' );
						unset( $args[$question_tag_rewrite] );
						$url = get_term_link( $term, 'dwqa-question_tag' );
					}
				}


				if ( $url && ! is_wp_error( $url ) ) {
					$url = esc_url( add_query_arg( $args, $url ) );
					wp_send_json_success( array( 'url' => $url ) );
				} else {
					wp_send_json_error( array( 'error' => 'missing_questions_archive_page' ) );
				}
			} else {
				$url = get_permalink( $dwqa_options['pages']['archive-question'] );
				$url = $url ? $url : get_post_type_archive_link( 'dwqa-question' );
				wp_send_json_success( array( 'url' => $url ) );
			}
		}
		wp_send_json_error();
	}

	public function stick_question() {
		check_ajax_referer( '_dwqa_stick_question', 'nonce' );
		if ( ! isset( $_POST['post'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid Post', 'dwqa' ) ) );
		}

		$question = get_post( intval( $_POST['post'] ) );
		if ( is_user_logged_in() ) {
			global $current_user;
			$sticky_questions = get_option( 'dwqa_sticky_questions', array() );

			if ( ! dwqa_is_sticky( $question->ID )  ) {
				$sticky_questions[] = $question->ID;
				update_option( 'dwqa_sticky_questions', $sticky_questions );
				wp_send_json_success( array( 'code' => 'stickied' ) );
			} else {
				foreach ( $sticky_questions as $key => $q ) {
					if ( $q == $question->ID ) {
						unset( $sticky_questions[$key] );
					}
				}
				update_option( 'dwqa_sticky_questions', $sticky_questions );
				wp_send_json_success( array( 'code' => 'Unstick' ) );
			}
		} else {
			wp_send_json_error( array( 'code' => 'not-logged-in' ) );
		}
	}

	public function admin_posts_filter_restrict_manage_posts() {
		$type = 'post';
		if ( isset( $_GET['post_type'] ) ) {
			$type = sanitize_text_field( $_GET['post_type'] );
		}

		//only add filter to post type you want
		if ( 'dwqa-question' == $type ) {
			?>
			<label for="dwqa-filter-sticky-questions" style="line-height: 32px"><input type="checkbox" name="dwqa-filter-sticky-questions" id="dwqa-filter-sticky-questions" value="1" <?php checked( true, ( isset( $_GET['dwqa-filter-sticky-questions'] ) && sanitize_text_field( $_GET['post_type'] ) ) ? true : false, true ); ?>> <span class="description"><?php _e( 'Sticky Questions','dwqa' ) ?></span></label>
			<?php
		}
	}

	public function posts_filter( $query ) {
		global $pagenow;
		$type = 'post';
		if ( isset( $_GET['post_type'] ) ) {
			$type = sanitize_text_field( $_GET['post_type'] );
		}
		if ( 'dwqa-question' == $type && is_admin() && $pagenow == 'edit.php' && isset( $_GET['dwqa-filter-sticky-questions'] ) && $_GET['dwqa-filter-sticky-questions'] ) {

			$sticky_questions = get_option( 'dwqa_sticky_questions' );

			if ( $sticky_questions ) {
				$query->query_vars['post__in'] = $sticky_questions;
			}
		}
		return $query;
	}


	public function delete_question() {
		$valid_ajax = check_ajax_referer( '_dwqa_delete_question', 'nonce', false );
		$nonce = isset($_POST['nonce']) ? esc_html( $_POST['nonce'] ) : false;
		if ( ! $valid_ajax || ! wp_verify_nonce( $nonce, '_dwqa_delete_question' ) || ! is_user_logged_in() ) {
			wp_send_json_error( array(
				'message' => __( 'Hello, Are you cheating huh?', 'dwqa' )
			) );
		}

		if ( ! isset( $_POST['question'] ) ) {
			wp_send_json_error( array(
				'message'   => __( 'Question is not valid','dwqa' )
			) );
		}

		$question = get_post( sanitize_text_field( $_POST['question'] ) );
		global $current_user;
		if ( dwqa_current_user_can( 'delete_question', $question->ID ) || dwqa_current_user_can( 'manage_question' ) ) {
			//Get all answers that is tired with this question
			do_action( 'before_delete_post', $question->ID );

			$delete = wp_delete_post( $question->ID );

			if ( $delete ) {
				global $dwqa_options;
				do_action( 'dwqa_delete_question', $question->ID );
				wp_send_json_success( array(
					'question_archive_url' => get_permalink( $dwqa_options['pages']['archive-question'] )
				) );
			} else {
				wp_send_json_error( array(
					'question'  => $question->ID,
					'message'   => __( 'Delete Action was failed','dwqa' )
				) );
			}
		} else {
			wp_send_json_error( array(
				'message'   => __( 'You do not have permission to delete this question','dwqa' )
			) );
		}
	}

	public function hook_on_remove_question( $post_id ) {
		if ( 'dwqa-question' == get_post_type( $post_id ) ) {
			$answers = wp_cache_get( 'dwqa-answers-for-' . $post_id, 'dwqa' );

			if ( false == $answers ) {

				$args = array(
					'post_type' => 'dwqa-answer',
					'post_parent' => $post_id,
					'post_per_page' => '-1',
					'post_status' => array('publish', 'private', 'pending')
				);

				$answers = get_posts($args);

				wp_cache_set( 'dwqa-answers-for'.$post_id, $answers, 'dwqa', 21600 );
			}

			if ( ! empty( $answers ) ) {
				foreach ( $answers as $answer ) {
					wp_trash_post( $answer->ID );
				}
			}
		}
	}

	//Auto close question when question was resolved longtime
	public function schedule_events() {
		if ( ! wp_next_scheduled( 'dwqa_hourly_event' ) ) {
			wp_schedule_event( time(), 'hourly', 'dwqa_hourly_event' );
		}
	}

	public function do_this_hourly() {
		$closed_questions = wp_cache_get( 'dwqa-closed-question' );
		if ( false == $closed_questions ) {
			global $wpdb;
			$query = "SELECT `{$wpdb->posts}`.ID FROM `{$wpdb->posts}` JOIN `{$wpdb->postmeta}` ON `{$wpdb->posts}`.ID = `{$wpdb->postmeta}`.post_id WHERE 1=1 AND `{$wpdb->postmeta}`.meta_key = '_dwqa_status' AND `{$wpdb->postmeta}`.meta_value = 'closed' AND `{$wpdb->posts}`.post_status = 'publish' AND `{$wpdb->posts}`.post_type = 'dwqa-question'";
			$closed_questions = $wpdb->get_results( $query );

			wp_cache_set( 'dwqa-closed-question', $closed_questions );
		}

		if ( ! empty( $closed_questions ) ) {
			foreach ( $closed_questions as $q ) {
				$resolved_time = get_post_meta( $q->ID, '_dwqa_resolved_time', true );
				if ( dwqa_is_resolved( $q->ID ) && ( time() - $resolved_time > (3 * 24 * 60 * 60 ) ) ) {
					update_post_meta( $q->ID, '_dwqa_status', 'resolved' );
				}
			}
		}
	}
}

?>
