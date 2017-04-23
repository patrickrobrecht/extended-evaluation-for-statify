<?php
/**
 * Formatting functions.
 *
 * @package extended-evaluation-for-statify
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Output the link to a csv export.
 *
 * @param string $filename the file name.
 * @param string $button_id the identifier of the link.
 * @param string $table_id the identifier of the table to export.
 */
function eefstatify_echo_export_button( $filename, $button_id = 'csv-export', $table_id = 'table-data' ) {
?>
	<a class="page-title-action" href="#" id="<?php echo esc_html( $button_id ); ?>" role="button"><?php esc_html_e( 'Export', 'extended-evaluation-for-statify' ); ?></a>
	<script type='text/javascript'>
	jQuery(document).ready(function () {
		jQuery("#<?php echo esc_html( $button_id ); ?>").click(function () {
			exportTableToCSV.apply(this, [jQuery('#<?php echo esc_html( $table_id ); ?>'), '<?php echo esc_html( $filename ); ?>.csv']);
		});
	});
	</script>
<?php }

/**
 * Echo the class(es) for a navigation tab.
 *
 * @param boolean $is_active_tab true if and only if the tab is active.
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
 * @param string $date a string the string to test.
 * @return boolean true if and only if the given string is a valid date in the YYYY-MM-DD format.
 */
function eefstatify_is_valid_date_string( $date ) {
	if ( 10 !== strlen( $date ) ) {
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
 * @param string  $start the start date of the date period.
 * @param string  $end the end date of the date period.
 * @param boolean $show true if and only if the date period shall be shown.
 * @param boolean $formatted_dates true if and only if the dates should be formatted.
 * @return string the formatted date period string.
 */
function eefstatify_get_date_period_string( $start, $end, $show, $formatted_dates = false ) {
	if ( $show ) {
		if ( $formatted_dates ) {
			$format = get_option( 'date_format' );
			$start = date_i18n( $format, strtotime( $start ) );
			$end = date_i18n( $format, strtotime( $end ) );
		}
		return ' ' . __( 'from', 'extended-evaluation-for-statify' ) . ' ' . $start
			. ' ' . __( 'to', 'extended-evaluation-for-statify' ) . ' ' . $end;
	} else {
		return '';
	}
}

/**
 * Get a file name for the given export.
 *
 * @param string $export_name a name for the export option.
 * @return string the concatenation of the site name, the export name and the current date and time.
 */
function eefstatify_get_filename( $export_name ) {
	$sitename = sanitize_key( get_bloginfo( 'name' ) );
	$export_name = str_replace( ' ', '-', $export_name );
	return $sitename . '-' . $export_name . '-export-' . date( 'Y-m-d-H-i-s' );
}

/**
 * Echo the given number if greater 0 else a dash.
 *
 * @param string $string a numeric value as string.
 */
function eefstatify_echo_number( $string ) {
	$number = intval( $string );
	if ( $number < 0 ) {
		echo '&mdash;';
	} else {
		echo esc_html( number_format_i18n( $number, 0 ) );
	}
}

/**
 * Outputs the given number in percent.
 *
 * @param float   $number the number to output.
 * @param integer $decimals the number of digits after the decimal point.
 */
function eefstatify_echo_percentage( $number, $decimals = 2 ) {
	echo esc_html( number_format_i18n( $number * 100, $decimals ) . ' %' );
}

/**
 * Get the first three characters of the month name.
 *
 * @param int $month_as_int an integer.
 * @return string the first three characters of the month name (e. g. Jan for January).
 */
function eefstatify_get_month_name( $month_as_int ) {
	$month_as_int = intval( $month_as_int );
	if ( in_array( $month_as_int, eefstatify_get_months(), true ) ) {
		return date_i18n( 'M', strtotime( '2016-' . $month_as_int . '-1' ) );
	}
	return '';
}

/**
 * Returns the given number if greater 0 else a dash.
 *
 * @param string $string a numeric value as string.
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
 * @param string $url an URL of a post.
 * @return string the title or the URL itself as fallback.
 */
function eefstatify_get_post_title_from_url( $url ) {
	if ( '' === $url ) {
		return __( 'all posts', 'extended-evaluation-for-statify' );
	}
	if ( '/' === $url ) {
		return __( 'Home Page', 'extended-evaluation-for-statify' );
	}
	$post_id = url_to_postid( $url );
	return ( 0 === $post_id ) ? esc_url( $url ) : get_the_title( $post_id );
}

/**
 * Returns the post type for the given post URL.
 *
 * @param string $url an URL of a post.
 * @return string the post type or the empty string as fallback.
 */
function eefstatify_get_post_type_from_url( $url ) {
	$post_id = url_to_postid( $url );
	return ( 0 === $post_id ) ? '' : get_post_type( $post_id );
}

/**
 * Returns the post type name for the given post URL.
 *
 * @param string $url an URL of a post.
 * @return string the post type name or the URL itself as fallback.
 */
function eefstatify_get_post_type_name_from_url( $url ) {
	$post_type = eefstatify_get_post_type_from_url( $url );
	return ( '' === $post_type ) ? '' : get_post_type_object( $post_type )->labels->singular_name;
}

/**
 * Returns the post type and name for the given post URL.
 *
 * @param string $url an URL of a post.
 * @return string the post type name and the title.
 */
function eefstatify_get_post_type_name_and_title_from_url( $url ) {
	return sprintf(
		/* translators: 1: post type 2: post title or URL. */
		__( 'for %1$s %2$s', 'extended-evaluation-for-statify' ),
		eefstatify_get_post_type_name_from_url( $url ),
		eefstatify_get_post_title_from_url( $url )
	);
}

/**
 * Returns the post types of the site: post, page and custom post types.
 *
 * @return array an array of post type slugs.
 */
function eefstatify_get_post_types() {
	$types_args = array(
		'public' => true,
		'_builtin' => false,
	);
	$types = array_merge( array( 'post', 'page' ), get_post_types( $types_args ) );
	return $types;
}
