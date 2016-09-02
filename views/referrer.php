<?php
	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) exit;
	
	// Reset variables and Get post parameters for the dates if submitted..
	$valid_start = false;
	$valid_end = false;
	$message = '';
	$start = isset( $_POST['start'] ) ? $_POST['start'] : '';
	$end = isset( $_POST['end'] ) ? $_POST['end'] : '';
	
	// Check for at least one date set and valid wp_nonce
	if ( ( $start != '' || $end != '' ) && check_admin_referer( 'referrers' ) ) {
		$valid_start = eefstatify_is_valid_date_string( $start );
		$valid_end = eefstatify_is_valid_date_string( $end );
		if ( !$valid_start || !$valid_end ) { // Error message if at least one date is not valid.
			$message = __( 'No valid date period set. Please enter a valid start and a valid end date!', 'extended-evaluation-for-statify' );
		}
	}
	
	if ( $valid_start && $valid_end ) {
		// Get the referrer data for the given range.
		$referrers = eefstatify_get_views_for_all_referrers_for_period( $start, $end );
	} else {
		// Get the referrer data.
		$referrers = eefstatify_get_views_for_all_referrers();
	}
	$referrers_for_diagram = array_slice($referrers, 0, 25, true);
?>
<div class="wrap eefstatify">
	<h1><?php _e( 'Extended Evaluation for Statify', 'extended-evaluation-for-statify' ); ?> 
			&rsaquo; <?php _e( 'Referrer from other websites', 'extended-evaluation-for-statify' ); ?></h1>
	<?php if ( $message != '' ) { ?>
	<div class="notice notice-error">
		<p><?php echo $message; ?></p>
	</div>
	<?php } ?>
	<h2><?php _e( 'Referrer from other websites', 'extended-evaluation-for-statify' ); ?>
		<?php echo eefstatify_get_date_period_string( $start, $end, $valid_start && $valid_end ); ?>
		<?php if ($valid_start && $valid_end ) {
			eefstatify_echo_export_form( 'referrer-date-period', array( 'start' => $start, 'end' => $end ) );
		} else {
			eefstatify_echo_export_form( 'referrer' );
		} ?></h2>
	<form method="post" action="">
		<?php wp_nonce_field( 'referrers' ); ?>
		<fieldset>
			<legend><?php _e( 'Restrict date period: Please enter start and end date in the YYYY-MM-DD format', 'extended-evaluation-for-statify' ); ?></legend>
			<label for="start"><?php _e( 'Start date', 'extended-evaluation-for-statify' );?></label>
			<input id="start" name="start" type="date" value="<?php if ( $valid_start ) echo $start; ?>" required="required"
				pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))">
			<label for="end"><?php _e( 'End date', 'extended-evaluation-for-statify' );?></label>
			<input id="end" name="end" type="date" value="<?php if ( $valid_end ) echo $end; ?>" required="required"
				pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))">
			<button type="submit" class="button-secondary"><?php _e( 'Select date period', 'extended-evaluation-for-statify' ); ?></button>
		</fieldset>
	</form>
	<section>
		<script type="text/javascript">
		jQuery(function() {
			jQuery('#chart').highcharts({
				chart: {
					type: 'column'
				},
				title: {
					text: '<?php _e( 'Referrer from other websites', 'extended-evaluation-for-statify' ); ?>'
				},
				subtitle: {
					text: '<?php echo get_bloginfo( 'name' ) . eefstatify_get_date_period_string( $start, $end, $valid_start && $valid_end ); ?>'
				},
				xAxis: {
					categories: [ 
						<?php foreach( $referrers_for_diagram as $referrer ) {
							echo "'" . esc_html( $referrer['host'] ) . "',";
						}?> ],
				},
				yAxis: {
					title: {
						text: '<?php _e( 'Views', 'extended-evaluation-for-statify' ); ?>'
					}
				},
				legend: {
					enabled: false,
				},
				series: [ {
					name: '<?php _e( 'Views', 'extended-evaluation-for-statify' ); ?>',
					data: [ <?php foreach( $referrers_for_diagram as $referrer ) {
						echo $referrer['count'] . ','; 
						}?> ]
				} ],
				credits: {
					enabled: false
				},
				exporting: {
					filename: '<?php echo eefstatify_get_filename( 'referrer' ); ?>'
				}
			});
		});
		</script>	
		<div id="chart"></div>
		<table class="wp-list-table widefat">
			<thead>
				<tr>
					<th><?php _e( 'Referring Domain', 'extended-evaluation-for-statify' ); ?></th>
					<th><?php _e( 'Views', 'extended-evaluation-for-statify' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $referrers as $referrer ) { ?>
					<tr>
						<td><a href="<?php echo esc_url( $referrer['url'] ); ?>" target="_blank"><?php echo esc_html( $referrer['host'] ); ?></a></td>
	        	    	<td class="right"><?php eefstatify_echo_number( $referrer['count'] ); ?></td>            
	       		  	</tr>
	       		<?php } ?>
	       		
			</tbody>
		</table>
	</section>
</div>