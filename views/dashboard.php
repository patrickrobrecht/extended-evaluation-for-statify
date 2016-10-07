<?php
	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) exit;
	
	// Get the selected post if one is set, otherwise: all posts.
	if ( isset( $_POST['post'] ) && check_admin_referer( 'dashboard' ) ) {
		$selected_post = sanitize_text_field( $_POST['post'] );
	} else {
		if ( isset( $_GET['post'] ) ) {
			$selected_post = sanitize_text_field( $_GET['post'] );
		} else {
			$selected_post = '';
		}
	}
	if ( $selected_post == 'all' ) {
		$selected_post = '';
	}
	
	// Get the data necessary for all tabs.
	$years = eefstatify_get_years();
	$months = eefstatify_get_months();
	$views_for_all_months = eefstatify_get_views_for_all_months( $selected_post );
	
	// Get the selected tab.
	if ( isset( $_GET['year'] ) && strlen( $_GET['year'] ) == 4 ) {
		$selected_year = intval( $_GET['year'] );
		
		// Get the data shown on daily details tab for one year.
		$days = eefstatify_get_days();
		$views_for_all_days = eefstatify_get_views_for_all_days( $selected_post );
	} else {
		$selected_year = 0; // 0 = show overview tab
		
		// Get data shown on overview tab.
		$views_for_all_years = eefstatify_get_views_for_all_years( $selected_post );
		$post_types = eefstatify_get_post_types();
	}
?>
<div class="wrap eefstatify">
	<h1><?php _e( 'Extended Evaluation for Statify', 'extended-evaluation-for-statify' ); ?></h1>

	<h2 class="nav-tab-wrapper">
		<a href="<?php echo admin_url( 'admin.php?page=extended_evaluation_for_statify_dashboard' ) . '&post=' . $selected_post; ?>" 
			class="<?php eefstatify_echo_tab_class( $selected_year == 0 ); ?>"><?php _e( 'Overview', 'extended-evaluation-for-statify' ); ?></a>
	<?php foreach( $years as $year ) { ?>
		<a href="<?php echo admin_url( 'admin.php?page=extended_evaluation_for_statify_dashboard' ) . '&year=' . $year . '&post=' . $selected_post; ?>"
			class="<?php eefstatify_echo_tab_class( $selected_year == $year ); ?>"><?php echo esc_html( $year ); ?></a>
	<?php } ?>
	</h2>
<?php 
	if ( $selected_year == 0 ) { // overview tab
?>
	<h2><?php _e( 'Monthly / Yearly Views', 'extended-evaluation-for-statify' ); ?>
		<?php echo eefstatify_get_post_type_name_and_title_from_url( $selected_post ); ?>
		<?php $filename_monthly = eefstatify_get_filename( __( 'Monthly Views', 'extended-evaluation-for-statify' ) 
							. '-' . eefstatify_get_post_title_from_url( $selected_post ) );
			eefstatify_echo_export_button( $filename_monthly ); ?></h2>
<?php } else { ?>
	<h2><?php echo __( 'Daily Views', 'extended-evaluation-for-statify' ) . ' ' . esc_html( $selected_year ); ?>
		<?php echo eefstatify_get_post_type_name_and_title_from_url( $selected_post ); ?>
		<?php $filename_daily = eefstatify_get_filename( __( 'Daily Views', 'extended-evaluation-for-statify' )
							. '-' . $selected_year
							. '-' . eefstatify_get_post_title_from_url( $selected_post ) );
			eefstatify_echo_export_button( $filename_daily ); ?></h2>
<?php } ?>
	<form method="post" action="">
		<?php wp_nonce_field( 'dashboard' ); ?>
		<fieldset>
			<legend><?php _e( 'Per default the views of all posts are shown. To restrict the evaluation to one post/page, select one.', 'extended-evaluation-for-statify' ); ?></legend>
			<label for="post"><?php _e( 'Post/Page', 'extended-evaluation-for-statify' );?></label>
			<select id="post" name="post" required="required">
				<option value="all"><?php _e( 'all posts', 'extended-evaluation-for-statify' ); ?></option>
				<?php $posts = eefstatify_get_post_urls();
					foreach ($posts as $post) { ?>
				<option value="<?php echo $post['target']; ?>" <?php if ( $post['target'] == $selected_post ) 
					echo 'selected="selected"'?>><?php echo eefstatify_get_post_title_from_url( $post['target'] ); ?></option>
				<?php } ?>
			</select>
			<button type="submit" class="button-secondary"><?php _e( 'Select post/page', 'extended-evaluation-for-statify' ); ?></button>
		</fieldset>
	</form>
<?php 
	if ( $selected_year == 0 ) { // overview tab
?>
	<section class="two-charts">
		<div id="chart-monthly"></div>
		<div id="chart-yearly""></div>
		<script type="text/javascript">
		jQuery(function() {
			jQuery('#chart-monthly').highcharts({
				chart: {
					type: 'column'
				},
				title: {
					text: '<?php _e( 'Monthly Views', 'extended-evaluation-for-statify' ); ?>'
				},
				subtitle: {
					text: '<?php echo get_bloginfo( 'name' ) . ' ' . eefstatify_get_post_title_from_url( $selected_post ); ?>'
				},
				xAxis: {
					categories: [ '<?php echo implode( "','", array_keys( $views_for_all_months ) ); ?>' ],
				},
				yAxis: {
					title: {
						text: '<?php _e( 'Views', 'extended-evaluation-for-statify' ); ?>'
					},
					min: 0
				},
				legend: {
					enabled: false,
				},
				series: [ {
					name: '<?php _e( 'Views', 'extended-evaluation-for-statify' ); ?>',
					data: [ <?php echo implode( ',', $views_for_all_months ); ?> ]
				} ],
				credits: {
					enabled: false
				},
				exporting: {
					filename: '<?php echo $filename_monthly; ?>'
				}
			});
		});
		jQuery(function() {
			jQuery('#chart-yearly').highcharts({
				chart: {
					type: 'column'
				},
				title: {
					text: '<?php _e( 'Yearly Views', 'extended-evaluation-for-statify' ); ?>'
				},
				subtitle: {
					text: '<?php echo get_bloginfo( 'name' ) . ' ' . eefstatify_get_post_title_from_url( $selected_post ); ?>'
				},
				xAxis: {
					categories: [ '<?php echo implode( "','", array_keys( $views_for_all_years ) ); ?>' ],
				},
				yAxis: {
					title: {
						text: '<?php _e( 'Views', 'extended-evaluation-for-statify' ); ?>'
					},
					min: 0
				},
				legend: {
					enabled: false,
				},
				series: [ {
					name: '<?php _e( 'Views', 'extended-evaluation-for-statify' ); ?>',
					data: [ <?php echo implode( ',', $views_for_all_years ); ?> ]
				} ],
				credits: {
					enabled: false
				},
				exporting: {
					filename: '<?php echo eefstatify_get_filename( __( 'Yearly Views', 'extended-evaluation-for-statify' ) 
							. '-' . eefstatify_get_post_title_from_url( $selected_post ) ); ?>'
				}
			});
		});
		</script>
	</section>
	<section>
		<table id="table-data" class="wp-list-table widefat">
			<thead>
			 	<tr>
			 		<th scope="col"><?php _e('Year', 'extended-evaluation-for-statify' ); ?></th>
			 	<?php foreach ( $months as $month ) { ?>
			 		<th scope="col"><?php echo eefstatify_get_month_name( $month ); ?></th>
			 	<?php } ?>
			 		<th scope="col" class="right sum"><?php _e( 'Sum', 'extended-evaluation-for-statify' ); ?></th>
			 	</tr>
			 </thead>
			 <tbody>
			 <?php foreach( $years as $year ) { ?>
			 	<tr>
			 		<th scope="row"><a href="<?php echo admin_url( 'admin.php?page=extended_evaluation_for_statify_dashboard' ) . '&year=' . $year; ?>"><?php echo $year; ?></a></th>
	            <?php foreach ( $months as $month ) { ?>
	            	<td class="right"><?php eefstatify_echo_number( eefstatify_get_monthly_views( $views_for_all_months, $year, $month ) ); ?></td>
	            <?php } ?>
	            	<td class="right sum"><?php eefstatify_echo_number( eefstatify_get_yearly_views( $views_for_all_years, $year ) ); ?></td>
	            </tr>
	      	<?php } ?>
			</tbody>
		</table>
	</section>
<?php } else { ?>
	<section class="two-charts">
		<div id="chart-daily"></div>
		<div id="chart-monthly"></div>
		<script type="text/javascript">
		jQuery(function() {
			jQuery('#chart-daily').highcharts({
				chart: {
					type: 'line'
				},
				title: {
					text: '<?php echo __( 'Daily Views', 'extended-evaluation-for-statify' ). ' ' . $selected_year; ?>'
				},
				subtitle: {
					text: '<?php echo get_bloginfo( 'name' ) . ' ' . eefstatify_get_post_title_from_url( $selected_post ); ?>'
				},
				xAxis: {
					categories: [ <?php
						$y = '';
						foreach( $months as $month ) {
							$days = eefstatify_get_days( $month, $selected_year );
							foreach( $days as $day ) {
								$views = eefstatify_get_daily_views( $views_for_all_days, $selected_year, $month, $day );
								echo "'" . $day . '. ' . eefstatify_get_month_name( $month ) . "',";
								$y .= $views . ',';
							}
						}
					?>],
				},
				yAxis: {
					title: {
						text: '<?php _e( 'Views', 'extended-evaluation-for-statify' ); ?>'
					},
					min: 0
				},
				legend: {
					enabled: false,
				},
				series: [ {
					name: '<?php _e( 'Views', 'extended-evaluation-for-statify' ); ?>',
					data: [ <?php echo $y; ?> ]
				} ],
				credits: {
					enabled: false	
				},
				exporting: {
					filename: '<?php echo $filename_daily; ?>'
				}
			});
		});
		jQuery(function() {
			jQuery('#chart-monthly').highcharts({
				chart: {
					type: 'column'
				},
				title: {
					text: '<?php echo __( 'Monthly Views', 'extended-evaluation-for-statify' ). ' ' . $selected_year; ?>'
				},
				subtitle: {
					text: '<?php echo get_bloginfo( 'name' ) . ' ' . eefstatify_get_post_title_from_url( $selected_post ); ?>'
				},
				xAxis: {
					categories: [ <?php
						$y = '';
						foreach( $months as $month ) {
							$views = eefstatify_get_monthly_views( $views_for_all_months, $selected_year, $month );
							if ( $views > 0 ) {
								echo "'" . eefstatify_get_month_name( $month ) . "',";
								$y .= $views . ',';
							}
						}
					?>],
				},
				yAxis: {
					title: {
						text: '<?php _e( 'Views', 'extended-evaluation-for-statify' ); ?>'
					},
					min: 0
				},
				legend: {
					enabled: false,
				},
				series: [ {
					name: '<?php _e( 'Views', 'extended-evaluation-for-statify' ); ?>',
					data: [ <?php echo $y; ?> ]
				} ],
				credits: {
					enabled: false	
				},
				exporting: {
					filename: '<?php echo eefstatify_get_filename( __( 'Monthly Views', 'extended-evaluation-for-statify' )
										. '-' . $selected_year
										. '-' . eefstatify_get_post_title_from_url( $selected_post ) ); ?>'
				}
			});
		});		
		</script>
	</section>
	<section>
		<table id="table-data" class="wp-list-table widefat">
			<thead>
			 	<tr>
			 		<td></td>
			 		<?php foreach ( $months as $month ) { ?>
			 		<th><?php echo eefstatify_get_month_name( $month ); ?></th>
			 		<?php } ?>
			 	</tr>
			 </thead>
			 <tbody>
			 	<?php foreach ( $days as $day ) { ?>
	     		<tr>
	     			<th><?php echo $day; ?></th>
	     			<?php foreach ( $months as $month ) { ?>
			 		<td class="right"><?php eefstatify_echo_number( eefstatify_get_daily_views( $views_for_all_days, $selected_year, $month, $day ) ); ?></td>
			 		<?php } ?>
	     		</tr>	
	            <?php } ?>
	            <tr class="sum">
	            	<td><?php _e( 'Sum', 'extended-evaluation-for-statify' ); ?></td>
	            	<?php foreach ( $months as $month ) { ?>
	            	<td class="right"><?php eefstatify_echo_number( eefstatify_get_monthly_views( $views_for_all_months, $selected_year, $month ) ); ?></td>
	            	<?php } ?>
	            </tr>
	            <tr>
	            	<td><?php _e( 'Average', 'extended-evaluation-for-statify' ); ?></td>
	            	<?php foreach ( $months as $month ) { ?>
	            	<td class="right"><?php echo eefstatify_echo_number( eefstatify_get_average_daily_views_of_month( $views_for_all_months, $selected_year, $month ) ); ?></td>
	            	<?php } ?>
	            </tr>
	            <tr>
	            	<td><?php _e( 'Minimum', 'extended-evaluation-for-statify' ); ?></td>
	            	<?php $daily_views = array();
	            		foreach ( $months as $month ) { 
	            			$daily_views[ $month ] = eefstatify_get_daily_views_of_month( $views_for_all_days, $selected_year, $month );
	            	?>
	            	<td class="right"><?php echo eefstatify_echo_number( min( $daily_views[ $month ] ) ); ?></td>
	            	<?php } ?>
	            </tr>
	            <tr>
	            	<td><?php _e( 'Maximum', 'extended-evaluation-for-statify' ); ?></td>
	            	<?php foreach ( $months as $month ) { ?>
	            	<td class="right"><?php echo eefstatify_echo_number( max( $daily_views[ $month ] ) ); ?></td>
	            	<?php } ?>
	            </tr>
			</tbody>
		</table>
	</section>
<?php } ?>
</div>