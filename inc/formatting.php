<?php
	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) exit;

	/**
	 * Echos the export form for the given parameters
	 * 
	 * @param string $export the export type
	 * @param array $parameters additional parameters
	 * @param string $button_title_added title extension for the export button (optional)
	 */
	function eefstatify_echo_export_form( $export, $parameters = array() ) {
?>
<form method="post" action="">
	<?php wp_nonce_field( 'export' ); ?>
	<input type="hidden" name="export" value="<?php echo esc_attr( $export ); ?>">
	<?php foreach( $parameters as $name => $value ) { ?>
	<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr ( $value ); ?>">
	<?php } ?>
	<button type="submit" class="button-secondary"><?php _e( 'Export', 'extended-evaluation-for-statify' ); ?></button>
</form>
<?php	
	}
	
	/**
	 * Echo the class(es) for an navigation tab.
	 * 
	 * @param boolean $is_active_tab
	 */
	function eefstatify_echo_tab_class( $is_active_tab ) {
		echo 'nav-tab';
		if ( $is_active_tab ) {
			echo ' nav-tab-active';
		}
	}
	
	/**
	 * Returns true if and only if the given string is a valid date in the YYYY-MM-DD format.
	 * 
	 * @param string $date a string
	 * @return boolean true if and only if the given string is a valid date in the YYYY-MM-DD format 
	 */
	function eefstatify_is_valid_date_string( $date ) {
		if ( strlen( $date ) != 10 ) {
			return false;
		}
		$year = substr( $date, 0, 4 );
		$month = substr( $date, 5, 2 );
		$day = substr( $date, 8, 2 );
		return checkdate( $month, $day, $year );
	}
	
	/**
	 * Returns a string with the given date period. 
	 * 
	 * @param string $start the start date of the date period. 
	 * @param string $end the end date of the date period.
	 * @param boolean $show whether to show the date period
	 * @return string the formatted date period string
	 */
	function eefstatify_get_date_period_string( $start, $end, $show ) {
		if ( $show ) {
			return ' ' . __( 'from', 'extended-evaluation-for-statify' ) . ' ' . $start 
				 . ' ' . __( 'to', 'extended-evaluation-for-statify' ) . ' ' . $end;
		} else {
			return '';
		}
	}
	
	/**
	 * Get a file name for the given export.
	 * 
	 * @param string $export_name a name for the export option
	 * @return string the concatenation of the site name, the export name and the current date and time 
	 */
	function eefstatify_get_filename( $export_name ) {
		$sitename = sanitize_key( get_bloginfo( 'name' ) );
		$export_name = strtolower( $export_name );
		$export_name = str_replace( ' ', '-', $export_name );
		return $sitename . '-' . $export_name . '-export-' . date( 'Y-m-d-H-i-s' );
	}
	
	/**
	 * Echo the given number if greater 0 else a dash.
	 * 
	 * @param string $string a numeric value as string
	 * return string the formatted number or a dash
	 */
	function eefstatify_echo_number( $string ) {
		$number = intval( $string );
		if ( $number < 0 ) {
			echo '&mdash;';
		} else {
			echo number_format_i18n( $number, 0 );
		}
	}

	/**
	 * Get the first three characters of the month name.
	 * 
	 * @param unknown $month_as_int an integer 
	 * @return void|string the first three characters of the month name (e. g. Jan for January)
	 */
	function eefstatify_get_month_name( $month_as_int ) {
		$month_as_int = intval( $month_as_int );
		if ( in_array( $month_as_int, eefstatify_get_months() ) ) {
			return date_i18n( 'M', strtotime( '2016-' . $month_as_int . '-1' ) );
		}
		return;
	}

	/**
	 * Returns the given number if greater 0 else a dash.
	 * 
	 * @param string $string a numeric value as string
	 * @return string the number or a dash.
	 */
	function eefstatify_get_number_for_csv( $string ) {
		$number = intval( $string );
		if ( $number < 0 ) {
			return '-';
		} else {
			return $string;
		}
	}

	/**
	 * Returns the post title for the given post URL.
	 * 
	 * @param unknown $url an URL of a post
	 * @return string the title or the URL itself as fallback
	 */
	function eefstatify_get_post_title_from_url( $url ) {
		if ( $url == '' ) {
			return __( 'all posts', 'extended-evaluation-for-statify' );
		}
		if ( $url == '/' ) {
			return __( 'Home Page', 'extended-evaluation-for-statify' );
		}
		$post_id = url_to_postid( $url );
		return ( $post_id == 0 ) ? esc_url( $url ) : get_the_title( $post_id );
	}
	
	/**
	 * Returns the post type for the given post URL.
	 *
	 * @param unknown $url an URL of a post
	 * @return string the post type or the empty string as fallback
	 */
	function eefstatify_get_post_type_from_url( $url ) {
		$post_id = url_to_postid( $url );
		return ( $post_id == 0 ) ? '' : get_post_type( $post_id );
	}
	
	/**
	 * Returns the post type name for the given post URL.
	 *
	 * @param unknown $url an URL of a post
	 * @return string the post type name or the URL itself as fallback
	 */
	function eefstatify_get_post_type_name_from_url( $url ) {
		$post_type = eefstatify_get_post_type_from_url( $url );
		return ( $post_type == '' ) ? '' : get_post_type_object( $post_type )->labels->singular_name;
	}
	
	/**
	 * Returns the post type and name for the given post URL.
	 * 
	 * @param unknown $url an URL of a post
	 * @return string the post type name and the title.
	 */
	function eefstatify_get_post_type_name_and_title_from_url( $url ) {
		return sprintf ( __( 'for %s %s', 'extended-evaluation-for-statify' ), 
				eefstatify_get_post_type_name_from_url( $url ),
				eefstatify_get_post_title_from_url( $url ) );
	}
	
	/**
	 * Returns the post types of the site: post, page and custom post types.
	 * 
	 * @return array an array of post type slugs
	 */
	function eefstatify_get_post_types() {
		$types_args = array(
				'public' => true,
				'_builtin' => false,
		);
		$types = array_merge( array( 'post', 'page' ), get_post_types( $types_args ) );
		return $types;
	}
	