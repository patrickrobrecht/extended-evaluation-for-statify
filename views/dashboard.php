<?php
/**
 * The dashboard page.
 *
 * @package extended-evaluation-for-statify
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Get the selected post if one is set, otherwise: all posts.
if ( isset( $_POST['post'] ) && check_admin_referer( 'dashboard' ) ) {
	$selected_post = sanitize_text_field( wp_unslash( $_POST['post'] ) );
} elseif ( isset( $_GET['post'] ) ) {
	$selected_post = sanitize_text_field( wp_unslash( $_GET['post'] ) );
} else {
	$selected_post = '';
}

// Get the data necessary for all tabs.
$years = eefstatify_get_years();
$months = eefstatify_get_months();
$views_for_all_months = eefstatify_get_views_for_all_months( $selected_post );

// Get the selected tab.
if ( isset( $_GET['year'] ) && 4 === strlen( sanitize_text_field( wp_unslash( $_GET['year'] ) ) ) ) {
	$selected_year = (int) sanitize_text_field( wp_unslash( $_GET['year'] ) );

	// Get the data shown on daily details tab for one year.
	$days = eefstatify_get_days();
	$views_for_all_days = eefstatify_get_views_for_all_days( $selected_post );
} else {
	$selected_year = 0; // 0 = show overview tab.

	// Get data shown on overview tab.
	$views_for_all_years = eefstatify_get_views_for_all_years( $selected_post );
	$post_types = eefstatify_get_post_types();
}
?>
<div class="wrap eefstatify">
	<h1><?php esc_html_e( 'Statify – Extended Evaluation', 'extended-evaluation-for-statify' ); ?></h1>

	<nav class="nav-tab-wrapper wp-clearfix" aria-label="<?php esc_html_e( 'Overview and Years', 'extended-evaluation-for-statify' ); ?>">
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=extended_evaluation_for_statify_dashboard&post=' . $selected_post ) ); ?>"
			class="<?php eefstatify_echo_tab_class( 0 === $selected_year ); ?>"><?php esc_html_e( 'Overview', 'extended-evaluation-for-statify' ); ?></a>
	<?php foreach ( $years as $year ) { ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=extended_evaluation_for_statify_dashboard&year=' . $year . '&post=' . $selected_post ) ); ?>"
			class="<?php eefstatify_echo_tab_class( $selected_year === $year ); ?>"><?php echo esc_html( $year ); ?></a>
	<?php } ?>
	</nav>
	<form method="post" action="">
		<?php wp_nonce_field( 'dashboard' ); ?>
		<fieldset>
			<legend><?php esc_html_e( 'Per default the views of all posts are shown. To restrict the evaluation to one post/page, enter their path or name.', 'extended-evaluation-for-statify' ); ?></legend>
			<?php eefstatify_echo_post_selection( $selected_post ); ?>
			<button type="submit" class="button-secondary"><?php esc_html_e( 'Select post/page', 'extended-evaluation-for-statify' ); ?></button>
		</fieldset>
	</form>
<?php
if ( 0 === $selected_year ) {
	// Display the overview tab.
	$filename_monthly = eefstatify_get_filename(
		__( 'Monthly Views', 'extended-evaluation-for-statify' )
		. '-' . eefstatify_get_post_title_from_url( $selected_post )
	);
	?>
	<?php if ( count( $views_for_all_months ) === 0 ) { ?>
	<p><?php esc_html_e( 'No data available.', 'extended-evaluation-for-statify' ); ?></p>
	<?php } else { ?>
	<section>
		<?php
		eefstatify_echo_chart_container(
			'chart-monthly',
			__( 'Monthly Views', 'extended-evaluation-for-statify' ),
			eefstatify_get_post_title_from_url( $selected_post )
		);
		eefstatify_echo_chart_container(
			'chart-yearly',
			__( 'Yearly Views', 'extended-evaluation-for-statify' ),
			eefstatify_get_post_title_from_url( $selected_post )
		);
		?>
		<script type="text/javascript">
			eefstatifyLineChart(
				'#chart-monthly',
				[
					<?php
					foreach ( $views_for_all_months as $month => $views ) {
						echo "['" . esc_js( eefstatify_get_month_year_name( $month ) ) . "'," . esc_js( $views ) . '],';
					}
					?>
				]
			);
			eefstatifyLineChart(
				'#chart-yearly',
				[
					<?php
					foreach ( $views_for_all_years as $year => $views ) {
						echo "['" . esc_js( $year ) . "'," . esc_js( $views ) . '],';
					}
					?>
				]
			);
		</script>
	</section>
	<section>
		<h3><?php esc_html_e( 'Monthly / Yearly Views', 'extended-evaluation-for-statify' ); ?>
			<?php
			echo esc_html( eefstatify_get_post_type_name_and_title_from_url( $selected_post ) );
			eefstatify_echo_export_button( $filename_monthly );
			?>
			</h3>
		<table id="table-data" class="wp-list-table widefat striped">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Year', 'extended-evaluation-for-statify' ); ?></th>
					<?php foreach ( $months as $month ) { ?>
					<th scope="col"><?php echo esc_html( eefstatify_get_month_name( $month ) ); ?></th>
					<?php } ?>
					<th scope="col" class="right sum"><?php esc_html_e( 'Sum', 'extended-evaluation-for-statify' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $years as $year ) { ?>
				<tr>
					<th scope="row"><a href="<?php echo esc_url( admin_url( 'admin.php?page=extended_evaluation_for_statify_dashboard&year=' . $year ) ); ?>"><?php echo esc_html( $year ); ?></a></th>
					<?php foreach ( $months as $month ) { ?>
					<td class="right"><?php eefstatify_echo_number( eefstatify_get_monthly_views( $views_for_all_months, $year, $month ) ); ?></td>
					<?php } ?>
					<td class="right sum"><?php eefstatify_echo_number( eefstatify_get_yearly_views( $views_for_all_years, $year ) ); ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</section>
	<?php } ?>
	<?php
} else {
	$filename_daily = eefstatify_get_filename(
		__( 'Daily Views', 'extended-evaluation-for-statify' )
		. '-' . $selected_year . '-' . eefstatify_get_post_title_from_url( $selected_post )
	);
	?>
	<section>
		<?php
		eefstatify_echo_chart_container(
			'chart-daily',
			__( 'Daily Views', 'extended-evaluation-for-statify' ) . ' ' . $selected_year,
			eefstatify_get_post_title_from_url( $selected_post )
		);
		eefstatify_echo_chart_container(
			'chart-monthly',
			__( 'Monthly Views', 'extended-evaluation-for-statify' ) . ' ' . $selected_year,
			eefstatify_get_post_title_from_url( $selected_post )
		);
		?>
		<script>
			eefstatifyLineChart(
				'#chart-daily',
				[
					<?php
					foreach ( $months as $month ) {
						$days = eefstatify_get_days( $month, $selected_year );
						foreach ( $days as $day ) {
							$views = eefstatify_get_daily_views( $views_for_all_days, $selected_year, $month, $day );
							echo "['" . esc_js( $day ) . '. ' . esc_js( eefstatify_get_month_name( $month ) ) . "'," . esc_js( $views ) . '],';
						}
					}
					?>
				],
				'daily'
			);
			eefstatifyLineChart(
				'#chart-monthly',
				[
					<?php
					foreach ( $months as $month ) {
						$views = eefstatify_get_monthly_views( $views_for_all_months, $selected_year, $month );
						echo "['" . esc_js( eefstatify_get_month_name( $month ) ) . "'," . esc_js( $views ) . '],';
					}
					?>
				]
			);
		</script>
	</section>
	<section>
		<h3><?php echo esc_html( __( 'Daily Views', 'extended-evaluation-for-statify' ) . ' ' . $selected_year ); ?>
			<?php
			echo esc_html( eefstatify_get_post_type_name_and_title_from_url( $selected_post ) );
			eefstatify_echo_export_button( $filename_daily );
			?>
		</h3>
		<table id="table-data" class="wp-list-table widefat striped">
			<thead>
				<tr>
					<td></td>
					<?php foreach ( $months as $month ) { ?>
					<th scope="col"><?php echo esc_html( eefstatify_get_month_name( $month ) ); ?></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $days as $day ) { ?>
				<tr>
					<th scope="row"><?php echo esc_html( $day ); ?></th>
					<?php foreach ( $months as $month ) { ?>
					<td class="right"><?php eefstatify_echo_number( eefstatify_get_daily_views( $views_for_all_days, $selected_year, $month, $day ) ); ?></td>
					<?php } ?>
				</tr>
				<?php } ?>
				<tr class="sum">
					<td><?php esc_html_e( 'Sum', 'extended-evaluation-for-statify' ); ?></td>
					<?php foreach ( $months as $month ) { ?>
					<td class="right"><?php eefstatify_echo_number( eefstatify_get_monthly_views( $views_for_all_months, $selected_year, $month ) ); ?></td>
					<?php } ?>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Average', 'extended-evaluation-for-statify' ); ?></td>
					<?php foreach ( $months as $month ) { ?>
					<td class="right"><?php eefstatify_echo_number( eefstatify_get_average_daily_views_of_month( $views_for_all_months, $selected_year, $month ) ); ?></td>
					<?php } ?>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Minimum', 'extended-evaluation-for-statify' ); ?></td>
					<?php
					$daily_views = [];
					foreach ( $months as $month ) {
						$daily_views[ $month ] = eefstatify_get_daily_views_of_month( $views_for_all_days, $selected_year, $month );
						?>
					<td class="right"><?php eefstatify_echo_number( min( $daily_views[ $month ] ) ); ?></td>
					<?php } ?>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Maximum', 'extended-evaluation-for-statify' ); ?></td>
					<?php foreach ( $months as $month ) { ?>
					<td class="right"><?php eefstatify_echo_number( max( $daily_views[ $month ] ) ); ?></td>
					<?php } ?>
				</tr>
			</tbody>
		</table>
	</section>
<?php } ?>
</div>
