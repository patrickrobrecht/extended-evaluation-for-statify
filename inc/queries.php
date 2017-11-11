<?php
/**
 * Data queries.
 *
 * @package extended-evaluation-for-statify
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Returns the numeric values for all days in a month.
 *
 * @param int $month the month as value between 1 and 12 (default: 1).
 * @param int $year a year (default: 0).
 * @return array an array of integers (1, 2, ..., 31).
 */
function eefstatify_get_days( $month = 1, $year = 0 ) {
	if ( 2 === $month ) {
		if ( checkdate( 2, 29, $year ) ) {
			return range( 1, 29 );
		} else {
			return range( 1, 28 );
		}
	}
	if ( in_array( $month, array( 4, 6, 9, 11 ), true ) ) {
		return range( 1, 30 );
	}
	return range( 1, 31 );
}

/**
 * Returns the numeric values of all months.
 *
 * @return array an array of integers (1, 2, ..., 12)
 */
function eefstatify_get_months() {
	return range( 1, 12 );
}

/**
 * Returns the years Statify has collected data for in descending order.
 *
 * @return array an array of integers (e. g. 2016, 2015)
 */
function eefstatify_get_years() {
	global $wpdb;
	$results = $wpdb->get_results(
		"SELECT DISTINCT YEAR(`created`) as `year`
		FROM `$wpdb->statify`
		ORDER BY `year` DESC",
		ARRAY_A
	);
	$years = array();
	foreach ( $results as $result ) {
		array_push( $years, intval( $result['year'] ) );
	}
	return $years;
}

/**
 * Returns the views for all days.
 * If the given URL is not the empty string, the result is restricted to the given post.
 *
 * @param string $post_url the URL of the post to select for (or the empty string for all posts).
 * @return array an array with date as key and views as value
 */
function eefstatify_get_views_for_all_days( $post_url = '' ) {
	global $wpdb;
	if ( '' === $post_url ) {
		// For all posts.
		$results = $wpdb->get_results(
			"SELECT `created` as `date`, COUNT(`created`) as `count`
			FROM `$wpdb->statify`
			GROUP BY `created`
			ORDER BY `created`",
			ARRAY_A
		);
	} else {
		// Only for selected posts.
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT `created` as `date`, COUNT(`created`) as `count`
				FROM `$wpdb->statify`
				WHERE `target` = %s
				GROUP BY `created`
				ORDER BY `created`",
				$post_url
			),
			ARRAY_A
		);
	}
	$views_for_all_days = array();
	foreach ( $results as $result ) {
		$views_for_all_days[ $result['date'] ] = $result['count'];
	}
	return $views_for_all_days;
}

/**
 * Returns the views for one day. If the date does not exists (e. g. 30th February),
 * this method returns -1.
 *
 * @param array $views_for_all_days an array with the daily views.
 * @param int   $year the year.
 * @param int   $month the month.
 * @param int   $day the day.
 * @return int number the number of views (or -1 if the date is invalid).
 */
function eefstatify_get_daily_views( $views_for_all_days, $year, $month, $day ) {
	$year = str_pad( $year, 4, '0', STR_PAD_LEFT );
	$month = str_pad( $month, 2, '0', STR_PAD_LEFT );
	$day = str_pad( $day, 2, '0', STR_PAD_LEFT );
	if ( checkdate( $month, $day, $year ) ) {
		$date = $year . '-' . $month . '-' . $day;
		if ( array_key_exists( $date, $views_for_all_days ) ) {
			return $views_for_all_days[ $date ];
		} else {
			return 0;
		}
	} else {
		// No valid date.
		return -1;
	}
}

/**
 * Returns the views for all months.
 * If the given URL is not the empty string, the result is restricted to the given post.
 *
 * @param string $post_url the URL of the post to select for (or the empty string for all posts).
 * @return array an array with month as key and views as value.
 */
function eefstatify_get_views_for_all_months( $post_url = '' ) {
	global $wpdb;
	if ( '' === $post_url ) {
		// For all posts.
		$results = $wpdb->get_results(
			"SELECT DATE_FORMAT(`created`, '%Y-%m') as `date`, COUNT(`created`) as `count`
			FROM `$wpdb->statify`
			GROUP BY `date`
			ORDER BY `date`",
			ARRAY_A
		);
	} else {
		// Only for selected posts.
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT DATE_FORMAT(`created`, '%Y-%m') as `date`, COUNT(`created`) as `count`
                FROM `$wpdb->statify`
                WHERE `target` = %s
                GROUP BY `date`
                ORDER BY `date`",
				$post_url
			),
			ARRAY_A
		);
	}
	$views_for_all_months = array();
	foreach ( $results as $result ) {
		$views_for_all_months[ $result['date'] ] = $result['count'];
	}
	return $views_for_all_months;
}

/**
 * Returns the views for one month.
 *
 * @param array $views_for_all_months an array with the monthly views.
 * @param int   $year the year.
 * @param int   $month the month.
 * @return int the view for the given month.
 */
function eefstatify_get_monthly_views( $views_for_all_months, $year, $month ) {
	$year = str_pad( $year, 4, '0', STR_PAD_LEFT );
	$month = str_pad( $month, 2, '0', STR_PAD_LEFT );
	$date = $year . '-' . $month;
	if ( array_key_exists( $date, $views_for_all_months ) ) {
		return $views_for_all_months[ $date ];
	} else {
		return 0;
	}
}

/**
 * Returns the average daily views in the given month.
 *
 * @param array $views_for_all_months an array with the monthly views.
 * @param int   $year the year.
 * @param int   $month the month.
 * @return float the average daily views in the given month.
 */
function eefstatify_get_average_daily_views_of_month( $views_for_all_months, $year, $month ) {
	$views_in_month = eefstatify_get_monthly_views( $views_for_all_months, $year, $month );
	$days_in_month = count( eefstatify_get_days( $month, $year ) );
	return round( $views_in_month / $days_in_month );
}

/**
 * Returns an array with the daily views for all days in the given month.
 *
 * @param array $views_for_all_days an array with the daily views.
 * @param int   $year the year.
 * @param int   $month the month.
 * @return array array with the daily views for all days in the given day.
 */
function eefstatify_get_daily_views_of_month( $views_for_all_days, $year, $month ) {
	$days = eefstatify_get_days( $month, $year );
	$views = array();
	foreach ( $days as $day ) {
		array_push( $views, intval( eefstatify_get_daily_views( $views_for_all_days, $year, $month, $day ) ) );
	}
	return $views;
}

/**
 * Returns the views for all years.
 *
 * If the given URL is not the empty string, the result is restricted to the given post.
 *
 * @param string $post_url the URL of the post to select for (or the empty string for all posts).
 * @return array an array with the year as key and views as value.
 */
function eefstatify_get_views_for_all_years( $post_url = '' ) {
	global $wpdb;
	if ( '' === $post_url ) {
		// For all posts.
		$results = $wpdb->get_results(
			"SELECT YEAR(`created`) as `date`, COUNT(`created`) as `count`
			FROM `$wpdb->statify`
			GROUP BY `date`",
			ARRAY_A
		);
	} else {
		// Only for selected posts.
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT YEAR(`created`) as `date`, COUNT(`created`) as `count`
				FROM `$wpdb->statify`
				WHERE `target` = %s
				GROUP BY `date`",
				$post_url
			),
			ARRAY_A
		);
	}
	$views_for_all_years = array();
	foreach ( $results as $result ) {
		$views_for_all_years[ $result['date'] ] = $result['count'];
	}
	return $views_for_all_years;
}

/**
 * Returns the views for one year. If the date does not exists (e. g. 30th February),
 * this method returns -1.
 *
 * @param array $views_for_all_years an array with the yearly views.
 * @param int   $year the year.
 * @return int the views of the given year.
 */
function eefstatify_get_yearly_views( $views_for_all_years, $year ) {
	$year = str_pad( $year, 4, '0', STR_PAD_LEFT );
	if ( array_key_exists( $year, $views_for_all_years ) ) {
		return $views_for_all_years[ $year ];
	} else {
		return 0;
	}
}

/**
 * Returns the most popular posts with their views count (in the date period if set).
 *
 * @param string $start the start date of the period.
 * @param string $end the end date of the period.
 * @return array an array with the most popular posts, ordered by view count.
 */
function eefstatify_get_views_of_most_popular_posts( $start = '', $end = '' ) {
	global $wpdb;
	if ( '' === $start && '' === $end ) {
		return $wpdb->get_results(
			"SELECT COUNT(`target`) as `count`, `target` as `url`
			FROM `$wpdb->statify`
			GROUP BY `target`
			ORDER BY `count` DESC",
			ARRAY_A
		);
	} else {
		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT COUNT(`target`) as `count`, `target` as `url`
				FROM `$wpdb->statify`
				WHERE `created` >= %s AND `created` <= %s
				GROUP BY `target`
				ORDER BY `count` DESC",
				$start,
				$end
			),
			ARRAY_A
		);
	}
}

/**
 * Returns the number of views for the post with the given URL (in the date period if set).
 *
 * @param string $url the post URL.
 * @param string $start the start date of the period.
 * @param string $end the end date of the period.
 * @return int the number of views for the post.
 */
function eefstatify_get_views_of_post( $url, $start = '', $end = '' ) {
	global $wpdb;
	if ( '' === $start && '' === $end ) {
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT COUNT(`target`) as `count`
				FROM `$wpdb->statify`
				WHERE `target` = %s",
				$url
			),
			OBJECT
		);
	} else {
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT COUNT(`target`) as `count`
				FROM `$wpdb->statify`
				WHERE `target` = %s AND `created` >= %s AND `created` <= %s",
				$url,
				$start,
				$end
			),
			OBJECT
		);
	}
	return $results[0]->count;
}

/**
 * Returns the most popular referrers with their views count.
 * If the given URL is not the empty string, the result is restricted to the given post.
 *
 * @param string $post_url the URL of the post to select for (or the empty string for all posts).
 * @param string $start the start date of the period.
 * @param string $end the end date of the period.
 * @return array an array with the most referrers, ordered by view count
 */
function eefstatify_get_views_for_all_referrers( $post_url = '', $start = '', $end = '' ) {
	global $wpdb;
	if ( '' === $post_url ) {
		// For all posts.
		if ( '' === $start && '' === $end ) {
			return $wpdb->get_results(
				"SELECT COUNT(`referrer`) as `count`, `referrer` as `url`,
				SUBSTRING_INDEX(SUBSTRING_INDEX(TRIM(LEADING 'www.' FROM(TRIM(LEADING 'https://' FROM TRIM(LEADING 'http://' FROM TRIM(`referrer`))))), '/', 1), ':', 1) as `host`
				FROM `$wpdb->statify`
				WHERE `referrer` != ''
				GROUP BY `host`
				ORDER BY `count` DESC",
				ARRAY_A
			);
		} else {
			return $wpdb->get_results(
				$wpdb->prepare(
					"SELECT COUNT(`referrer`) as `count`, `referrer` as `url`,
					SUBSTRING_INDEX(SUBSTRING_INDEX(TRIM(LEADING 'www.' FROM(TRIM(LEADING 'https://' FROM TRIM(LEADING 'http://' FROM TRIM(`referrer`))))), '/', 1), ':', 1) as `host`
					FROM `$wpdb->statify`
					WHERE `referrer` != '' AND `created` >= %s AND `created` <= %s
					GROUP BY `host`
					ORDER BY `count` DESC",
					$start,
					$end
				),
				ARRAY_A
			);
		}
	} else {
		// Only for selected posts.
		if ( '' === $start && '' === $end ) {
			return $wpdb->get_results(
				$wpdb->prepare(
					"SELECT COUNT(`referrer`) as `count`, `referrer` as `url`,
					SUBSTRING_INDEX(SUBSTRING_INDEX(TRIM(LEADING 'www.' FROM(TRIM(LEADING 'https://' FROM TRIM(LEADING 'http://' FROM TRIM(`referrer`))))), '/', 1), ':', 1) as `host`
					FROM `$wpdb->statify`
					WHERE `referrer` != '' AND target = %s
					GROUP BY `host`
					ORDER BY `count` DESC",
					$post_url
				),
				ARRAY_A
			);
		} else {
			return $wpdb->get_results(
				$wpdb->prepare(
					"SELECT COUNT(`referrer`) as `count`, `referrer` as `url`,
					SUBSTRING_INDEX(SUBSTRING_INDEX(TRIM(LEADING 'www.' FROM(TRIM(LEADING 'https://' FROM TRIM(LEADING 'http://' FROM TRIM(`referrer`))))), '/', 1), ':', 1) as `host`
					FROM `$wpdb->statify`
					WHERE `referrer` != '' AND `target` = %s AND `created` >= %s AND `created` <= %s
					GROUP BY `host`
					ORDER BY `count` DESC",
					$post_url,
					$start,
					$end
				),
				ARRAY_A
			);
		}
	}
}

/**
 * Returns a list of all target URLs.
 *
 * @return array an array of urls
 */
function eefstatify_get_post_urls() {
	global $wpdb;
	return $wpdb->get_results(
		"SELECT DISTINCT `target`
		FROM `$wpdb->statify`
		ORDER BY `target` ASC",
		ARRAY_A
	);
}
