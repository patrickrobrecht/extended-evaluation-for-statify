<?php
/**
 * Plugin Name: Statify – Extended Evaluation
 * Plugin URI: https://patrick-robrecht.de/wordpress/
 * Description: Extended evaluation for the compact, easy-to-use and privacy-compliant Statify plugin.
 * Version: 2.3
 * Author: Patrick Robrecht
 * Author URI: https://patrick-robrecht.de/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: extended-evaluation-for-statify
 *
 * @package extended-evaluation-for-statify
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Includes.
include_once 'inc/queries.php';
include_once 'inc/formatting.php';

/**
 * Requires Statify to be installed and activated during installation.
 */
function eefstatify_activate() {
	if ( ! eefstatify_is_statify_active() ) {
		deactivate_plugins( __FILE__ );
		wp_die(
			esc_html( __( 'Statify – Extended Evaluation requires the plugin Statify which has to be installed and activated! Please install and activate Statify before activating this plugin!', 'extended-evaluation-for-statify' ) ),
			esc_html( __( 'Activation Error: Statify – Extended Evaluation requires Statify!', 'extended-evaluation-for-statify' ) ),
			array(
				'response' => 200,
				'back_link' => true,
			)
		);
	}
}
// Hook to run during plugin activation.
register_activation_hook( __FILE__, 'eefstatify_activate' );

/**
 * Delete database entries created by the plugin on uninstall.
 */
function eefstatify_uninstall() {
	// Nothing to do.
}
// Hook to run during plugin uninstall.
register_uninstall_hook( __FILE__, 'eefstatify_uninstall' );

/**
 * Check for Statify plugin being installed.
 * If it isn't, display message and deactivate this plugin.
 */
function eefstatify_check_status() {
	if ( ! eefstatify_is_statify_active() ) {
		// Display warning in the admin area.
		echo '<div class="error"><p>'
				. esc_html( __( 'Statify – Extended Evaluation requires the plugin Statify which has to be installed and activated! Please install and activate Statify before activating this plugin!', 'extended-evaluation-for-statify' ) )
				. '</p></div>';
		// Deactivate this plugin.
		deactivate_plugins( __FILE__ );
	}
}
// Add status check to the admin notices.
add_action( 'admin_notices', 'eefstatify_check_status' );

/**
 * Test whether Statify is active.
 *
 * @return boolean true if and only if Statify is installed and active.
 */
function eefstatify_is_statify_active() {
	return is_plugin_active( 'statify/statify.php' );
}

/**
 * Load text domain for translation.
 */
function eefstatify_load_plugin_textdomain() {
	load_plugin_textdomain( 'extended-evaluation-for-statify' );
}
// Add text domain during initialization.
add_action( 'init', 'eefstatify_load_plugin_textdomain' );

/**
 * Register and load the style sheet.
 */
function eefstatify_register_and_load_css() {
	wp_register_style(
		'extended-evaluation-for-statify',
		plugins_url(
			'/css/style.css',
			__FILE__
		),
		array()
	);

	wp_enqueue_style( 'extended-evaluation-for-statify' );
}
// Load css file.
add_action( 'admin_print_styles', 'eefstatify_register_and_load_css' );

/**
 * Register the Highcharts libraries and load these and JQuery.
 */
function eefstatify_register_and_load_scripts() {
	wp_register_script(
		'highcharts',
		plugins_url(
			'/js/highcharts.js',
			__FILE__
		),
		array( 'jquery' )
	);
	wp_register_script(
		'highcharts-exporting',
		plugins_url(
			'/js/exporting.js',
			__FILE__
		)
	);
	wp_register_script(
		'table-to-csv',
		plugins_url(
			'/js/table-to-csv.js',
			__FILE__
		)
	);
	wp_enqueue_script( 'highcharts' );
	wp_enqueue_script( 'highcharts-exporting' );
	wp_enqueue_script( 'table-to-csv' );
}
// Load JavaScript libraries.
add_action( 'admin_print_scripts', 'eefstatify_register_and_load_scripts' );

/**
 * Create an item and submenu items in the WordPress admin menu.
 */
function eefstatify_add_menu() {
	add_menu_page(
		__( 'Statify – Extended Evaluation', 'extended-evaluation-for-statify' ), // page title.
		'Statify', // title in the menu.
		'see_statify_evaluation',
		'extended_evaluation_for_statify_dashboard',
		'eefstatify_show_dashboard',
		'dashicons-chart-bar',
		50
	);
	add_submenu_page(
		'extended_evaluation_for_statify_dashboard',
		__( 'Content', 'extended-evaluation-for-statify' )
				. ' &mdash; ' . __( 'Statify – Extended Evaluation', 'extended-evaluation-for-statify' ),
		__( 'Content', 'extended-evaluation-for-statify' ),
		'see_statify_evaluation',
		'extended_evaluation_for_statify_content',
		'eefstatify_show_content'
	);
	add_submenu_page(
		'extended_evaluation_for_statify_dashboard',
		__( 'Referrers', 'extended-evaluation-for-statify' )
				. ' &mdash; ' . __( 'Statify – Extended Evaluation', 'extended-evaluation-for-statify' ),
		__( 'Referrers', 'extended-evaluation-for-statify' ),
		'see_statify_evaluation',
		'extended_evaluation_for_statify_referrer',
		'eefstatify_show_referrer'
	);
}
// Register the menu building function.
add_action( 'admin_menu', 'eefstatify_add_menu' );

/**
 * Adds a custom capability for users who see the evaluation pages.
 */
function eefstatify_add_capability() {
	// Only administrators can see the evaluation by default.
	$role = get_role( 'administrator' );
	$role->add_cap( 'see_statify_evaluation' );

	// Remove old role Statify Analyst.
	$role = get_role( 'statify_evaluator' );
	if ( $role ) {
		remove_role( 'statify_evaluator' );
	}
}
// Adds the capability.
add_action( 'admin_init', 'eefstatify_add_capability' );

/**
 * Checks whether the current user is in the admin area and has the capability to see the evaluation.
 *
 * @return true if and only if the current user is allowed to see plugin pages
 */
function eefstatify_current_user_can_see_evaluation() {
	return is_admin() && current_user_can( 'see_statify_evaluation' );
}

/**
 * Show the dashboard page.
 */
function eefstatify_show_dashboard() {
	if ( eefstatify_current_user_can_see_evaluation() ) {
		include_once 'views/dashboard.php';
	}
}

/**
 * Show the most popular content statistics page.
 */
function eefstatify_show_content() {
	if ( eefstatify_current_user_can_see_evaluation() ) {
		include_once 'views/content.php';
	}
}

/**
 * Show the referrer statistics page.
 */
function eefstatify_show_referrer() {
	if ( eefstatify_current_user_can_see_evaluation() ) {
		include_once 'views/referrers.php';
	}
}
