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
			eefstatifyTableToCsv.apply(this, [jQuery('#<?php echo esc_html( $table_id ); ?>'), '<?php echo esc_html( $filename ); ?>.csv']);
		});
	});
	</script>
	<?php
}

/**
 * Outputs the fieldset for the date range selection.
 *
 * @param bool   $valid_start whether a start date is set.
 * @param string $start the start date.
 * @param bool   $valid_end whether a start date is set.
 * @param string $end the start date.
 */
function eefstatify_echo_date_selection( $valid_start, $start, $valid_end, $end ) {
	if ( ! $valid_start ) {
		$start = '';
	}
	if ( ! $valid_end ) {
		$end = '';
	}
	?>
	<fieldset>
		<legend><?php esc_html_e( 'Restrict date period: Please enter start and end date in the YYYY-MM-DD format', 'extended-evaluation-for-statify' ); ?></legend>
		<label for="dateRange"><?php esc_html_e( 'Date range', 'extended-evaluation-for-statify' ); ?></label>
		<select id="dateRange" onchange="eefstatifySelectDateRange()">
			<option value="default"><?php esc_html_e( 'default (all the time)', 'extended-evaluation-for-statify' ); ?></option>
			<option value="lastYear"><?php esc_html_e( 'last year', 'extended-evaluation-for-statify' ); ?></option>
			<option value="lastWeek"><?php esc_html_e( 'last week', 'extended-evaluation-for-statify' ); ?></option>
			<option value="yesterday"><?php esc_html_e( 'yesterday', 'extended-evaluation-for-statify' ); ?></option>
			<option value="today"><?php esc_html_e( 'today', 'extended-evaluation-for-statify' ); ?></option>
			<option value="thisWeek"><?php esc_html_e( 'this week', 'extended-evaluation-for-statify' ); ?></option>
			<option value="last28days"><?php esc_html_e( 'last 28 days', 'extended-evaluation-for-statify' ); ?></option>
			<option value="lastMonth"><?php esc_html_e( 'last month', 'extended-evaluation-for-statify' ); ?></option>
			<option value="thisMonth"><?php esc_html_e( 'this month', 'extended-evaluation-for-statify' ); ?></option>
			<option value="thisYear"><?php esc_html_e( 'this year', 'extended-evaluation-for-statify' ); ?></option>
			<option value="1stQuarter"><?php esc_html_e( '1st quarter', 'extended-evaluation-for-statify' ); ?></option>
			<option value="2ndQuarter"><?php esc_html_e( '2nd quarter', 'extended-evaluation-for-statify' ); ?></option>
			<option value="3rdQuarter"><?php esc_html_e( '3rd quarter', 'extended-evaluation-for-statify' ); ?></option>
			<option value="4thQuarter"><?php esc_html_e( '4th quarter', 'extended-evaluation-for-statify' ); ?></option>
			<option value="custom"><?php esc_html_e( 'custom', 'extended-evaluation-for-statify' ); ?></option>
		</select>
		<label for="start"><?php esc_html_e( 'Start date', 'extended-evaluation-for-statify' ); ?></label>
		<input id="start" name="start" type="date" value="<?php echo esc_html( $start ); ?>" oninput="eefstatifyDateRangeChange()"
			   pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))">
		<label for="end"><?php esc_html_e( 'End date', 'extended-evaluation-for-statify' ); ?></label>
		<input id="end" name="end" type="date" value="<?php echo esc_html( $end ); ?>" oninput="eefstatifyDateRangeChange()"
			   pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))">
		<button type="submit" class="button-secondary"><?php esc_html_e( 'Select date period', 'extended-evaluation-for-statify' ); ?></button>
	</fieldset>
	<?php
}

/**
 * Outputs the input field for the post selection.
 *
 * @param string $selected_post the path of the selected post.
 */
function eefstatify_echo_post_selection( $selected_post ) {
	$posts = eefstatify_get_post_urls();
	?>
	<label for="post"><?php esc_html_e( 'Post/Page', 'extended-evaluation-for-statify' ); ?></label>
	<input id="post" name="post" type="text" list="posts" value="<?php echo esc_attr( $selected_post ); ?>">
	<datalist id="posts">
		<?php foreach ( $posts as $post ) { ?>
			<option value="<?php echo esc_html( $post['target'] ); ?>"><?php echo esc_html( eefstatify_get_post_title_from_url( $post['target'] ) ); ?></option>
		<?php } ?>
	</datalist>
	<?php
}

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
	}

	return '';
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
	return $sitename . '-' . $export_name . '-export-' . gmdate( 'Y-m-d-H-i-s' );
}

/**
 * Echo the given number if greater 0 else a dash.
 *
 * @param string $string a numeric value as string.
 */
function eefstatify_echo_number( $string ) {
	$number = (int) $string;
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
 * @return string the short month name (e. g. Jan for January).
 */
function eefstatify_get_month_name( $month_as_int ) {
	$month_as_int = (int) $month_as_int;
	if ( in_array( $month_as_int, eefstatify_get_months(), true ) ) {
		return date_i18n( 'M', strtotime( '2016-' . $month_as_int . '-1' ) );
	}
	return '';
}

/**
 * Get a string with the first three characters of the month name and the year.
 *
 * @param string $month the year-month string.
 *
 * @return string short month name and year (e. g. Jan 2018)
 */
function eefstatify_get_month_year_name( $month ) {
	return date_i18n( 'M Y', strtotime( $month . '-1' ) );
}

/**
 * Returns the given number if greater 0 else a dash.
 *
 * @param string $string a numeric value as string.
 * @return string the number or a dash.
 */
function eefstatify_get_number_for_csv( $string ) {
	$number = (int) $string;
	if ( $number < 0 ) {
		return '-';
	}

	return $string;
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

	return array_merge( array( 'post', 'page' ), get_post_types( $types_args ) );
}

/**
 * Echos the div container for the chart.
 *
 * @param string $id the id of the chart container.
 * @param string $title the title.
 * @param string $subtitle the subtitle after the site name.
 * @param array  $legend the items for the legend.
 */
function eefstatify_echo_chart_container( $id, $title, $subtitle = '', $legend = [] ) {
	?>
	<div class="chart-container">
		<div class="chart-title">
			<?php echo esc_html( $title ); ?>
		</div>
		<div class="chart-subtitle">
			<?php echo esc_html( get_bloginfo( 'name' ) . ' ' . $subtitle ); ?>
		</div>
		<div id="<?php echo esc_attr( $id ); ?>"></div>
		<?php if ( count( $legend ) > 0 ) { ?>
			<div class="chart-legend">
				<ol>
					<?php foreach ( $legend as $item ) { ?>
						<li><?php echo esc_html( $item ); ?></li>
					<?php } ?>
				</ol>
			</div>
		<?php } ?>
	</div>
	<?php
}
