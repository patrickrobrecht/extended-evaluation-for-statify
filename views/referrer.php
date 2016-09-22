<?php
	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) exit;
	
	// Get the selected post if one is set, otherwise: all posts.
	if ( isset ( $_POST['post'] ) && check_admin_referer('referrers') && $_POST['post'] != 'all' ) {
		$selected_post = sanitize_text_field( $_POST['post'] );
	} else {
		$selected_post = '';
	}
	
	// Reset variables and get post parameters for the dates if submitted.
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
	
	$referrers = eefstatify_get_views_for_all_referrers( $selected_post, $start, $end );
	$referrers_for_diagram = array_slice($referrers, 0, 25, true);
?>
<div class="wrap eefstatify">
	<h1><?php _e( 'Extended Evaluation for Statify', 'extended-evaluation-for-statify' ); ?> 
			&rsaquo; <?php _e( 'Referrers from other websites', 'extended-evaluation-for-statify' ); ?></h1>
	<?php if ( $message != '' ) { ?>
	<div class="notice notice-error">
		<p><?php echo $message; ?></p>
	</div>
	<?php } ?>
	<h2><?php _e( 'Referrers from other websites', 'extended-evaluation-for-statify' ); ?>
		<?php echo eefstatify_get_date_period_string( $start, $end, $valid_start && $valid_end, true ); ?>
		<?php echo eefstatify_get_post_type_name_and_title_from_url( $selected_post ); ?>
		<?php $filename = eefstatify_get_filename( __( 'Referrers', 'extended-evaluation-for-statify' )
						. eefstatify_get_date_period_string( $start, $end, $valid_start && $valid_end )
						. '-' . eefstatify_get_post_title_from_url( $selected_post ) );
			eefstatify_echo_export_button( $filename ); ?></h2>
	<form method="post">
		<?php wp_nonce_field( 'referrers' ); ?>
		<fieldset>
			<legend><?php _e( 'Restrict date period: Please enter start and end date in the YYYY-MM-DD format', 'extended-evaluation-for-statify' ); ?></legend>
			<label for="start"><?php _e( 'Start date', 'extended-evaluation-for-statify' );?></label>
			<input id="start" name="start" type="date" value="<?php if ( $valid_start ) echo $start; ?>" required="required"
				pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))">
			<label for="end"><?php _e( 'End date', 'extended-evaluation-for-statify' );?></label>
			<input id="end" name="end" type="date" value="<?php if ( $valid_end ) echo $end; ?>" required="required"
				pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))">
			<input id="post" name="post" type="hidden" value="<?php echo $selected_post; ?>">
			<button type="submit" class="button-secondary"><?php _e( 'Select date period', 'extended-evaluation-for-statify' ); ?></button>
		</fieldset>
	</form>
	<form method="post">
		<?php wp_nonce_field( 'referrers' ); ?>
		<fieldset>
			<legend><?php _e( 'Per default the views of all posts are shown. To restrict the evaluation to one post/page, select one.', 'extended-evaluation-for-statify' ); ?></legend>
			<label for="post"><?php _e( 'Post/Page', 'extended-evaluation-for-statify' );?></label>
			<select id="post" name="post" required="required">
				<option value="all"><?php _e('all posts', 'extended-evaluation-for-statify'); ?></option>
				<?php $posts = eefstatify_get_post_urls();
					foreach ($posts as $post) { ?>
				<option value="<?php echo $post['target']; ?>" <?php if ( $post['target'] == $selected_post ) 
					echo 'selected="selected"'?>><?php echo eefstatify_get_post_title_from_url( $post['target'] ); ?></option>
				<?php } ?>
			</select>
			<input id="start" name="start" type="hidden" value="<?php if ( $valid_start ) echo $start; ?>">
			<input id="end" name="end" type="hidden" value="<?php if ( $valid_end ) echo $end; ?>">
			<button type="submit" class="button-secondary"><?php _e( 'Select post/page', 'extended-evaluation-for-statify' ); ?></button>
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
					text: '<?php _e( 'Referrers from other websites', 'extended-evaluation-for-statify' ); ?>'
				},
				subtitle: {
					text: '<?php echo get_bloginfo( 'name' ) . ' ' . eefstatify_get_post_title_from_url( $selected_post ) . eefstatify_get_date_period_string( $start, $end, $valid_start && $valid_end, true ); ?>'
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
					},
					min: 0
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
					filename: '<?php echo $filename; ?>'
				}
			});
		});
		</script>	
		<div id="chart"></div>
		<table id="table-data" class="wp-list-table widefat">
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