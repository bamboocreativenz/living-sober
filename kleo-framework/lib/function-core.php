<?php
//Theme options
if ( ! function_exists( 'sq_option' ) ) {

	//array with theme options
	global $kleo_options;
	$kleo_options = get_option( 'kleo_' . KLEO_DOMAIN );

	/**
	 * Function to get options in front-end
	 *
	 * @param int $option The option we need from the DB
	 * @param string $default If $option doesn't exist in DB return $default value
	 *
	 * @return string|array
	 */
	function sq_option( $option = false, $default = false ) {
		$output_data = false;

		if ( $option === false ) {
			return $output_data;
		}

		global $kleo_options;

		if ( isset( $kleo_options[ $option ] ) && $kleo_options[ $option ] !== '' ) {
			$output_data = $kleo_options[ $option ];
		} else {
			$output_data = $default;
		}

		return apply_filters( 'sq_option', $output_data, $option );
	}
}

add_action( 'init', 'kleo_options_filter_manage' );

function kleo_options_filter_manage() {
	global $kleo_options;
	$kleo_options = apply_filters( 'kleo_options', $kleo_options );
}

if ( ! function_exists( 'sq_option_url' ) ) {
	/**
	 * Function to get url options in front-end
	 *
	 * @param int $option The option we need from the DB
	 * @param string $default If $option doesn't exist in DB return $default value
	 *
	 * @return string
	 */
	function sq_option_url( $option = false, $default = false ) {
		if ( $option === false ) {
			return false;
		}
		global $kleo_options;

		if ( isset( $kleo_options[ $option ]['url'] ) && $kleo_options[ $option ]['url'] !== '' ) {
			return $kleo_options[ $option ]['url'];
		} else {
			return $default;
		}
	}
}


if ( ! function_exists( 'kleo_style_options' ) ) {
	/**
	 * Get styling options structured on sections
	 * @return array
	 * @global array $kleo_options
	 */
	function kleo_style_options() {
		global $kleo_options;
		//$kleo_options = apply_filters( 'kleo_options', $kleo_options );

		$sections = array();
		if ( isset( $kleo_options ) && ! empty( $kleo_options ) ) {
			foreach ( $kleo_options as $key => $option ) {
				if ( substr( $key, 0, 4 ) === "st__" ) {
					$data                             = explode( '__', $key );
					$sections[ $data[1] ][ $data[2] ] = $option;
				}
			}
		}

		return apply_filters( 'kleo_style_options', $sections );
	}
}


/*
 * Retrieve custom field
 */
if ( ! function_exists( 'get_cfield' ) ) {

	function get_cfield( $meta = null, $id = null ) {
		if ( $meta === null ) {
			return false;
		}

		if ( ! $id && ! in_the_loop() && is_home() && get_option( 'page_for_posts' ) ) {
			$id = get_option( 'page_for_posts' );
		}

		if ( $id === null ) {
			$id = get_the_ID();
		}

		if ( ! $id ) {
			return false;
		}

		return get_post_meta( $id, '_kleo_' . $meta, true );
	}
}

/*
 * Echo the custom field
 */
if ( ! function_exists( 'the_cfield' ) ) {
	function the_cfield( $meta = null, $id = null ) {
		echo get_cfield( $meta, $id );
	}
}

/*
 * Get POST value
 */
function get_postval( $val ) {
	global $_POST;
	if ( isset( $_POST[ $val ] ) && ! empty( $_POST[ $val ] ) ) {
		return $_POST[ $val ];
	} else {
		return false;
	}
}


/**
 * Set selected attribute in select form
 *
 * @param string $request
 * @param string $val
 */
function set_selected( $request, $val ) {
	global $_REQUEST;
	if ( isset( $_REQUEST[ $request ] ) && $_REQUEST[ $request ] == $val ) {
		echo 'selected="selected"';
	} else {
		echo '';
	}
}

/**
 * Returns selected attribute in select form
 *
 * @param string $request $_REQUEST value
 * @param string $val value to check uppon
 * @param string $default default value if no $_REQUEST is set
 */
function get_selected( $request, $val, $default = false ) {
	global $_REQUEST;
	if ( isset( $_REQUEST[ $request ] ) && $_REQUEST[ $request ] == $val ) {
		return 'selected="selected"';
	} elseif ( isset( $default ) && $default == $val ) {
		return 'selected="selected"';
	} else {
		return '';
	}
}


//TRIM WORD
function word_trim( $string, $count, $ellipsis = false ) {
	$words = explode( ' ', $string );
	if ( count( $words ) > $count ) {
		array_splice( $words, $count );
		$string = implode( ' ', $words );
		if ( is_string( $ellipsis ) ) {
			$string .= $ellipsis;
		} elseif ( $ellipsis ) {
			$string .= '&hellip;';
		}
	}

	return $string;
}

//TRIM by characters
function char_trim( $string, $count = 50, $ellipsis = false ) {
	$trimstring = substr( $string, 0, $count );
	if ( strlen( $string ) > $count ) {
		if ( is_string( $ellipsis ) ) {
			$trimstring .= $ellipsis;
		} elseif ( $ellipsis ) {
			$trimstring .= '&hellip;';
		}
	}

	return $trimstring;
}

//SANITIZE
function kleo_clean_input( $input ) {

	$search = array(
		'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
		'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
		'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
		'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
	);

	$output = preg_replace( $search, '', $input );

	return $output;
}

function kleo_sanitize( $input ) {
	if ( is_array( $input ) ) {
		foreach ( $input as $var => $val ) {
			$output[ $var ] = kleo_sanitize( $val );
		}
	} else {
		if ( get_magic_quotes_gpc() ) {
			$input = stripslashes( $input );
		}
		$input  = kleo_clean_input( $input );
		$output = addslashes( $input );
	}

	return $output;
}


//GET THE LINK FOR AN ARCHIVE
if ( ! function_exists( 'get_archive_link' ) ) {
	function get_archive_link( $post_type ) {
		global $wp_post_types;
		$archive_link = false;
		if ( isset( $wp_post_types[ $post_type ] ) ) {
			$wp_post_type = $wp_post_types[ $post_type ];
			if ( $wp_post_type->publicly_queryable ) {
				if ( $wp_post_type->has_archive && $wp_post_type->has_archive !== true ) {
					$slug = $wp_post_type->has_archive;
				} else if ( isset( $wp_post_type->rewrite['slug'] ) ) {
					$slug = $wp_post_type->rewrite['slug'];
				} else {
					$slug = $post_type;
				}
			}
			$archive_link = get_option( 'siteurl' ) . "/{$slug}/";
		}

		return apply_filters( 'archive_link', $archive_link, $post_type );
	}
}


if ( ! function_exists( 'kleo_pagination' ) ) :
	/**
	 * Displays pagination where if is required
	 *
	 * @param integer $pages - Number of pages for the current section(this is set automatically if it is omitted)
	 * @param integer $range - How many pagination links to show
	 *
	 * @since Squeen Framework 1.0
	 */
	function kleo_pagination( $pages = '', $echo = true, $current = false ) {

		$output = '';
		if ( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if ( ! $pages ) {
				$pages = 1;
			}
		}

		// Don't print empty markup if there's only one page.
		if ( $pages < 2 ) {
			return;
		}

		if ( $current ) {
			$paged = $current;
		} else {
			$paged = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		}
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

		$format = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

		$query_args = http_build_query( $query_args );

		// Set up paginated links.
		$links = paginate_links( array(
			'base'      => $pagenum_link,
			'format'    => $format,
			'total'     => $pages,
			'current'   => $paged,
			'mid_size'  => 2,
			'add_args'  => urlencode_deep( $query_args ),
			'prev_text' => esc_html__( '&laquo;', 'kleo' ),
			'next_text' => esc_html__( '&raquo;', 'kleo' ),
			'type'      => 'array',
		) );

		if ( $links ) {
			$output .= '<nav class="pagination-nav clear" role="navigation">'
			           . '<ul class="pagination">';

			foreach ( $links as $link ) {
				$output .= '<li>' . $link . '</li>';
			}

			$output .= '</ul>'
			           . '</nav><!-- .navigation -->';
		}

		if ( $echo ) {
			echo $output; // PHPCS: XSS ok.
		} else {
			return $output;
		}
	}
endif;

// Return attachment id from url
if ( ! function_exists( 'kleo_get_attachment_id_from_url' ) ) {
	function kleo_get_attachment_id_from_url( $url ) {
		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$url'";

		return $wpdb->get_var( $query );
	}
}

if ( ! function_exists( 'kleo_title' ) ):
	/**
	 *  Return the Page title string
	 */

	function kleo_title() {
		$output = "";
		if ( is_tag() ) {
			$output = esc_html__( 'Tag Archive for:', 'kleo' ) . " " . single_tag_title( '', false );
		} elseif ( is_tax() ) {
			$term   = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			$output = $term->name;
		} elseif ( is_category() ) {
			$output = esc_html__( 'kleo' ) . " " . single_cat_title( '', false );
		} elseif ( is_day() ) {
			$output = esc_html__( 'Archive for date:', 'kleo' ) . " " . get_the_time( 'F jS, Y' );
		} elseif ( is_month() ) {
			$output = esc_html__( 'Archive for month:', 'kleo' ) . " " . get_the_time( 'F, Y' );
		} elseif ( is_year() ) {
			$output = esc_html__( 'Archive for year:', 'kleo' ) . " " . get_the_time( 'Y' );
		} elseif ( is_author() ) {
			$curauth = ( get_query_var( 'author_name' ) ) ? get_user_by( 'slug', get_query_var( 'author_name' ) ) : get_userdata( get_query_var( 'author' ) );
			$output  = esc_html__( 'Author Archive', 'kleo' ) . " ";

			if ( isset( $curauth->nickname ) ) {
				$output .= esc_html__( 'for:', 'kleo' ) . " " . $curauth->nickname;
			}
		} elseif ( is_archive() ) {
			$output = post_type_archive_title( '', false );
		} elseif ( is_search() ) {
			global $wp_query;
			if ( ! empty( $wp_query->found_posts ) ) {
				if ( $wp_query->found_posts > 1 ) {
					$output = $wp_query->found_posts . " " . esc_html__( 'search results for:', 'kleo' ) . " " . esc_attr( get_search_query() );
				} else {
					$output = $wp_query->found_posts . " " . esc_html__( 'search result for:', 'kleo' ) . " " . esc_attr( get_search_query() );
				}
			} else {
				if ( ! empty( $_GET['s'] ) ) {
					$output = esc_html__( 'Search results for:', 'kleo' ) . " " . esc_attr( get_search_query() );
				} else {
					$output = esc_html__( 'To search the site please enter a valid term', 'kleo' );
				}
			}

		} elseif ( is_front_page() && ! is_home() ) {
			$output = get_the_title( get_option( 'page_on_front' ) );

		} elseif ( is_home() ) {
			if ( get_option( 'page_for_posts' ) ) {
				$output = get_the_title( get_option( 'page_for_posts' ) );
			} else {
				$output = esc_html__( 'Blog', 'kleo' );
			}

		} elseif ( is_404() ) {
			$output = esc_html__( 'Error 404 - Page not found', 'kleo' );
		} else {
			$output = get_the_title();
		}

		if ( isset( $_GET['paged'] ) && ! empty( $_GET['paged'] ) ) {
			$output .= " (" . esc_html__( 'Page', 'kleo' ) . " " . $_GET['paged'] . ")";
		}

		return $output;
	}
endif;


if ( ! function_exists( 'kleo_calc_perceived_brightness' ) ) {
	/**
	 *  calculates if a color is dark or light,
	 *  if a second parameter is passed it will return true or false based on the comparison of the calculated and passed value
	 *
	 * @param string $color hex color code
	 *
	 * @return array $color
	 */
	function kleo_calc_perceived_brightness( $color, $compare = false ) {
		$rgba = kleo_hex_to_rgb( $color );

		$brightness = sqrt(
			$rgba['r'] * $rgba['r'] * 0.241 +
			$rgba['g'] * $rgba['g'] * 0.691 +
			$rgba['b'] * $rgba['b'] * 0.068 );

		if ( $compare ) {
			$brightness = $brightness < $compare ? true : false;
		}

		return $brightness;
	}
}

function kleo_hex_to_rgb( $hex ) {
	$hex   = str_replace( "#", "", $hex );
	$color = array();

	if ( strlen( $hex ) == 3 ) {
		$color['r'] = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
		$color['g'] = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 0, 1 ) );
		$color['b'] = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 0, 1 ) );
	} else if ( strlen( $hex ) == 6 ) {
		$color['r'] = hexdec( substr( $hex, 0, 2 ) );
		$color['g'] = hexdec( substr( $hex, 2, 2 ) );
		$color['b'] = hexdec( substr( $hex, 4, 2 ) );
	} else {
		$color = array( 'r' => '255', 'g' => '255', 'b' => '255' );
	}

	return $color;
}

function kleo_rgb_to_hex( $r, $g, $b ) {
	$hex = "#";
	$hex .= str_pad( dechex( $r ), 2, "0", STR_PAD_LEFT );
	$hex .= str_pad( dechex( $g ), 2, "0", STR_PAD_LEFT );
	$hex .= str_pad( dechex( $b ), 2, "0", STR_PAD_LEFT );

	return $hex;
}

if ( ! function_exists( 'kleo_calc_similar_color' ) ) {
	/**
	 *  Calculates a darker or lighter color variation of a color
	 *
	 * @param string $color hex color code
	 * @param string $shade darker or lighter
	 * @param int $amount how much darker or lighter
	 *
	 * @return string returns the converted string
	 */
	function kleo_calc_similar_color( $color, $shade, $amount ) {

		//remove # from the begiining if available and make sure that it gets appended again at the end if it was found
		$newcolor = "";
		$prepend  = "";
		if ( strpos( $color, '#' ) !== false ) {
			$prepend = "#";
			$color   = substr( $color, 1, strlen( $color ) );
		}

		//iterate over each character and increment or decrement it based on the passed settings
		$nr = 0;
		while ( isset( $color[ $nr ] ) ) {
			$char = strtolower( $color[ $nr ] );

			for ( $i = $amount; $i > 0; $i -- ) {
				if ( $shade == 'lighter' ) {
					switch ( $char ) {
						case 9:
							$char = 'a';
							break;
						case 'f':
							$char = 'f';
							break;
						default:
							$char ++;
					}
				} else if ( $shade == 'darker' ) {
					switch ( $char ) {
						case 'a':
							$char = '9';
							break;
						case '0':
							$char = '0';
							break;
						default:
							$char = chr( ord( $char ) - 1 );
					}
				}
			}
			$nr ++;
			$newcolor .= $char;
		}

		$newcolor = $prepend . $newcolor;

		return $newcolor;
	}
}

if ( ! function_exists( 'svq_generate_dynamic_css' ) ) :
	function svq_generate_dynamic_css() {
		global $kleo_config;
		$dynamic_css = get_template_directory() . '/assets/css/dynamic.php';

		ob_start(); // Capture all output (output buffering)
		require( $dynamic_css ); // Generate CSS
		$css = ob_get_clean(); // Get generated CSS (output buffering)
		$css = kleo_compress( $css );

		if ( ! is_dir( $kleo_config['custom_style_path'] ) ) {
			// dir doesn't exist, make it
			wp_mkdir_p( $kleo_config['custom_style_path'] );
		}

		svq_fs_put_contents( trailingslashit( $kleo_config['custom_style_path'] ) . 'dynamic.css', $css ); // Save it
	}
endif;


function kleo_compress( $buffer ) {
	/* remove comments */
	$buffer = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer );
	/* remove tabs, spaces, newlines, etc. */
	$buffer = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $buffer );

	return $buffer;
}


/**
 * Get the current page url
 * @return string
 */
function kleo_full_url() {
	if ( ! isset( $_SERVER['SERVER_NAME'] ) ) {
		global $wp;

		return home_url( $wp->request );
	}

	if ( empty( $_SERVER["HTTPS"] ) ) {
		$s = '';
	} else {
		$s = $_SERVER["HTTPS"] === 'on' ? 's' : '';
	}

	$protocol = strtolower( substr( $_SERVER["SERVER_PROTOCOL"], 0, strpos( $_SERVER["SERVER_PROTOCOL"], "/" ) ) ) . $s;
	$port     = ( $_SERVER["SERVER_PORT"] == '80' || $_SERVER["SERVER_PORT"] == '443' ) ? '' : ( ":" . $_SERVER["SERVER_PORT"] );
	$uri      = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
	$segments = explode( '?', $uri, 2 );
	$url      = $segments[0];
	$url      = str_replace( "www.", "", $url );

	return $url;
}


/**
 * Get the Featured image HTML of a post
 *
 * @param integer $post_id
 * @param string $size
 * @param string|array $attr
 * @param bool $archive_only
 *
 * @return string
 * @global object $post
 *
 */
function kleo_get_post_thumbnail( $post_id = null, $size = 'post-thumbnail', $attr = '', $archive_only = true ) {
	$image_url = '';

	if ( has_post_thumbnail( $post_id ) ) {
		$image_url = get_the_post_thumbnail( $post_id, $size, $attr );
	}
	if ( $image_url ) {
		return $image_url;
	} else {
		if ( is_single() && $archive_only === true ) {
			return '';
		}
		global $post;

		if ( sq_option( 'blog_get_image', 1 ) == 1 ) {
			$output = preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', $post->post_content, $matches );

			if ( isset( $matches[1][0] ) ) {
				return '<img src="' . $matches[1][0] . '" alt="' . $post->post_title . '">';
			}
		}

		//Defines a default image
		$image_url = sq_option_url( 'blog_default_image', '' );

		if ( $image_url == '' ) {
			return '';
		} else {
			return '<img src="' . $image_url . '" alt="' . $post->post_title . '">';
		}
	}
}

/**
 * Get the Featured image URL of a post
 *
 * @param int $post_id
 *
 * @return string
 * @global object $post
 *
 */
function kleo_get_post_thumbnail_url( $post_id = null ) {
	$image_url = '';

	$thumb = get_post_thumbnail_id( $post_id );

	// All good. we have a featured image
	$featured_image_url = wp_get_attachment_url( $thumb );
	if ( $featured_image_url ) {
		$image_url = $featured_image_url;
	} elseif ( sq_option( 'blog_get_image', 1 ) == 1 ) {

		global $post;

		if ( ! is_object( $post ) && $post_id != null ) {
			$post = setup_postdata( get_post( $post_id ) );
		}

		ob_start();
		ob_end_clean();

		if ( isset( $post->post_content ) ) {
			preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', $post->post_content, $matches );
			$image_url = isset( $matches[1][0] ) ? $matches[1][0] : null;
		}
	}

	//Defines a default image
	if ( empty( $image_url ) ) {
		$image_url = sq_option_url( 'blog_default_image', '' );
	}

	return $image_url;
}


if ( ! function_exists( 'kleo_parse_multi_attribute' ) ) {
	/**
	 * Parse string like "title:Hello world|weekday:Monday" to array('title' => 'Hello World', 'weekday' => 'Monday')
	 *
	 * @param $value
	 * @param array $default
	 *
	 * @return array
	 */
	function kleo_parse_multi_attribute( $value, $default = array() ) {
		$result       = $default;
		$params_pairs = explode( '|', $value );
		if ( ! empty( $params_pairs ) ) {
			foreach ( $params_pairs as $pair ) {
				$param = preg_split( '/\:/', $pair );
				if ( ! empty( $param[0] ) && isset( $param[1] ) ) {
					$result[ $param[0] ] = rawurldecode( $param[1] );
				}
			}
		}

		return $result;
	}
}


if ( ! function_exists( 'sq_remove_img_srcset' ) ) {

	function sq_remove_img_srcset( $attr ) {
		if ( ! empty( $attr ) ) {
			unset( $attr['srcset'] );
			unset( $attr['sizes'] );
		}

		return $attr;
	}

}


if ( ! function_exists( 'kleo_set_default_unit' ) ) {
	function kleo_set_default_unit( $text, $default = 'px' ) {
		return preg_match( '/(px|em|\%|pt|cm|vh|vw)$/', $text ) ? $text : $text . $default;
	}
}


/**
 * Try to write a file using WP File system API
 *
 * @param string $file_path
 * @param string $contents
 * @param int $mode
 *
 * @return bool
 */
function svq_fs_put_contents( $file_path, $contents, $mode = '' ) {

	global $kleo_config;

	/* Frontend or customizer fallback */
	if ( ! function_exists( 'get_filesystem_method' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}

	if ( $mode == '' ) {
		if ( defined( 'FS_CHMOD_FILE' ) ) {
			$mode = FS_CHMOD_FILE;
		} else {
			$mode = 0644;
		}
	}

	$context                      = $kleo_config['custom_style_path'];
	$allow_relaxed_file_ownership = true;

	do_action( 'svq_fs_put_contents_before' );

	if ( function_exists( 'get_filesystem_method' ) && get_filesystem_method( array(), $context, $allow_relaxed_file_ownership ) === 'direct' ) {
		/* you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL */
		$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, $context, null, $allow_relaxed_file_ownership );

		/* initialize the API */
		if ( ! WP_Filesystem( $creds, $context, $allow_relaxed_file_ownership ) ) {
			/* any problems and we exit */
			return false;
		}

		global $wp_filesystem;
		/* do our file manipulations below */

		$status = $wp_filesystem->put_contents( $file_path, $contents, $mode );

		do_action( 'svq_fs_put_contents_after' );

		return true;

	} else {
		return false;
	}
}


/**
 * Try to get a file content using WP File system API
 *
 * @param $file_path
 *
 * @return bool
 */
function svq_fs_get_contents( $file_path ) {

	global $kleo_config;

	/* Frontend or customizer fallback */
	if ( ! function_exists( 'get_filesystem_method' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}

	$context                      = $kleo_config['custom_style_path'];
	$allow_relaxed_file_ownership = true;

	if ( function_exists( 'get_filesystem_method' ) && get_filesystem_method( array(), $context, $allow_relaxed_file_ownership ) === 'direct' ) {
		/* you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL */
		$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, $context, null, $allow_relaxed_file_ownership );

		/* initialize the API */
		if ( ! WP_Filesystem( $creds, $context, $allow_relaxed_file_ownership ) ) {
			/* any problems and we exit */
			return false;
		}

		global $wp_filesystem;

		/* do our file manipulations below */

		return $wp_filesystem->get_contents( $file_path );

	} else {
		return false;
	}
}


function sq_get_url_link( $text, $fallback = '' ) {

	$dom = new DOMDocument;
	$dom->loadHTML( $text );
	if ( $dom->getElementsByTagName( 'a' ) ) {
		foreach ( $dom->getElementsByTagName( 'a' ) as $node ) {
			return $node->getAttribute( 'href' );
		}
	}

	return $fallback;
}
