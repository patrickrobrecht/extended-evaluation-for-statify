<?php
	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) exit;
	
	// Get the data necessary for all tabs.
	$post_types = eefstatify_get_post_types();
	
	// Get the selected tab.
	if ( isset ( $_GET['posttype'] ) ) {
		$selected_post_type = $_GET['posttype'];
	} else {
		$selected_post_type = 'popular'; // popular = show most popular content
	}
	
	// Reset variables and Get post parameters for the dates if submitted..
	$valid_start = false;
	$valid_end = false;
	$message = '';
	$start = isset( $_POST['start'] ) ? $_POST['start'] : '';
	$end = isset( $_POST['end'] ) ? $_POST['end'] : '';
	
	// Check for at least one date set and valid wp_nonce
	if ( ( $start != '' || $end != '' ) && check_admin_referer( 'content' ) ) {
		$valid_start = eefstatify_is_valid_date_string( $start );
		$valid_end = eefstatify_is_valid_date_string( $end );
		if ( !$valid_start || !$valid_end ) { // Error message if at least one date is not valid.
			$message = __( 'No valid date period set. Please enter a valid start and a valid end date!', 'extended-evaluation-for-statify' );
		}
	}	
?>
<div class="wrap eefstatify">
	<h1><?php _e( 'Extended Evaluation for Statify', 'extended-evaluation-for-statify' ); ?>
			&rsaquo; <?php _e( 'Content', 'extended-evaluation-for-statify' ); ?></h1>
	<h2 class="nav-tab-wrapper">
		<a href="<?php echo admin_url( 'admin.php?page=extended_evaluation_for_statify_content' ); ?>" 
			class="<?php eefstatify_echo_tab_class( $selected_post_type == 'popular' ); ?>">
				<?php _e( 'Most Popular Content', 'extended-evaluation-for-statify' ); ?></a>
	<?php foreach($post_types as $post_type) { ?>
		<a href="<?php echo admin_url( 'admin.php?page=extended_evaluation_for_statify_content' ) . '&posttype=' . sanitize_text_field( $post_type ); ?>" 
			class="<?php eefstatify_echo_tab_class( $selected_post_type == $post_type ); ?>">
				<?php echo get_post_type_object( $post_type )->labels->name; ?></a>
	<?php } ?>
	</h2>
<?php 
	if ( $selected_post_type == 'popular' ) { 
		// show most popular content
		if ( $valid_start && $valid_end ) {
			$views_per_post = eefstatify_get_views_of_most_popular_posts( $start, $end );
		} else {
			$views_per_post = eefstatify_get_views_of_most_popular_posts();
		}
		$views_per_post_for_diagram = array_slice($views_per_post, 0, 25, true);
?>
	<h2><?php _e( 'Most Popular Content', 'extended-evaluation-for-statify' ); ?>
		<?php echo eefstatify_get_date_period_string( $start, $end, $valid_start && $valid_end, true ); ?>
		<?php $filename = eefstatify_get_filename( __( 'Most Popular Content', 'extended-evaluation-for-statify' ) 
						. eefstatify_get_date_period_string( $start, $end, $valid_start && $valid_end ) );
			eefstatify_echo_export_button( $filename ); ?></h2>
	<form method="post" action="">
		<?php wp_nonce_field( 'content' ); ?>
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
		<div id="chart"></div>
		<script type="text/javascript">
		jQuery(function() {
			jQuery('#chart').highcharts({
				chart: {
					type: 'column'
				},
				title: {
					text: '<?php _e( 'Most Popular Content', 'extended-evaluation-for-statify' ); ?>'
				},
				subtitle: {
					text: '<?php echo get_bloginfo( 'name' ) . eefstatify_get_date_period_string( $start, $end, $valid_start && $valid_end, true ); ?>'
				},
				xAxis: {
					categories: [ 
						<?php foreach( $views_per_post_for_diagram as $post ) {
							echo "'" . eefstatify_get_post_title_from_url( $post['url'] ) . "',";
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
					data: [ <?php foreach( $views_per_post_for_diagram as $post ) {
						echo $post['count'] . ','; 
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
	</section>
	<section>		
		<table id="table-data" class="wp-list-table widefat">
			<thead>
				<tr>
					<th scope="col"><?php _e( 'Post/Page', 'extended-evaluation-for-statify' ); ?></th>
					<th scope="col"><?php _e( 'URL', 'extended-evaluation-for-statify' ); ?></th>
					<th scope="col"><?php _e( 'Post Type', 'extended-evaluation-for-statify' ); ?></th>
					<th scope="col"><?php _e( 'Views', 'extended-evaluation-for-statify' ); ?></th>
					<th scope="col"><?php _e( 'Proportion', 'extended-evaluation-for-statify' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$total = 0;
					foreach( $views_per_post as $post ) {
						$total += $post['count'];
					}
					foreach ( $views_per_post as $post ) { ?>
				<tr>
					<td><a href="<?php echo esc_url( $post['url'] ); ?>" target="_blank"><?php echo eefstatify_get_post_title_from_url( $post['url'] ); ?></a></td>
					<td><?php echo esc_url( $post['url'] ); ?></td>
					<td><?php echo eefstatify_get_post_type_name_from_url( $post['url'] ); ?></td>
					<td class="right"><?php eefstatify_echo_number( $post['count'] ); ?></td>
					<td class="right"><?php eefstatify_echo_percentage( $post['count']/$total ); ?></td>   
				</tr>
				<?php }?>
			</tbody>
			<tfoot>
				<tr>
					<td><?php _e( 'Sum', 'extended-evaluation-for-statify' ); ?></td>
					<td></td>
					<td></td>
					<td class="right"><?php echo $total; ?></td>
					<td class="right"><?php eefstatify_echo_percentage( 1 ); ?></td>
				</tr>
			</tfoot>
		</table>
	</section>
<?php 
} else {
	$post_type = $selected_post_type;
?>
	<h2><?php echo get_post_type_object( $post_type )->labels->name; ?>
		<?php echo eefstatify_get_date_period_string( $start, $end, $valid_start && $valid_end, true ); ?>
		<?php $filename = eefstatify_get_filename( get_post_type_object( $post_type )->labels->name
						. eefstatify_get_date_period_string( $start, $end, $valid_start && $valid_end ) );
			eefstatify_echo_export_button( $filename ); ?></h2>
	<form method="post" action="">
		<?php wp_nonce_field( 'content' ); ?>
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
		<div id="chart"></div>
	</section>
	<section>
		<table id="table-data" class="wp-list-table widefat">
			<thead>
				<tr>
					<th scope="col"><?php echo get_post_type_object( $post_type )->labels->singular_name; ?></th>
					<th scope="col"><?php _e( 'URL', 'extended-evaluation-for-statify' ); ?></th>
					<th scope="col"><?php _e( 'Views', 'extended-evaluation-for-statify' ); ?></th>
				</tr>
			</thead>
			<tbody>
		<?php
			$args = array(
				'post_type' => $post_type,
				'post_status' => 'publish',
				'posts_per_page' => -1
			);
			$x = ''; $y = '';
			$index = 0;
			$query = new WP_Query($args);
			if( $query->have_posts() ) {
				while ( $query->have_posts() ) : $query->the_post();
					if ( $valid_start && $valid_end ) {
						$views = eefstatify_get_views_of_post( 
								str_replace( home_url(), "", get_permalink() ),
								$start,
								$end
						);
					} else {
						$views = eefstatify_get_views_of_post( str_replace( home_url(), "", get_permalink() ) );
					}
					$index++;
					if ($index <= 25) {
						$x .= "'" . get_the_title() . "',";
						$y .= $views . ",";
					}
		?>
			    <tr>
			    	<td><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></td>
			    	<td><?php echo wp_make_link_relative( get_the_permalink() ); ?></td>
			    	<td class="right"><?php eefstatify_echo_number( $views ); ?></td>
			    </tr>
		<?php
				endwhile;
			}
			wp_reset_query(); // Restore global post data stomped by the_post().
		?>
			</tbody>
		</table>
	</section>
	<script type="text/javascript">
	jQuery(function() {
		jQuery('#chart').highcharts({
			chart: {
				type: 'column'
			},
			title: {
				text: '<?php echo get_post_type_object( $post_type )->labels->name; ?>'
			},
			subtitle: {
				text: '<?php echo get_bloginfo( 'name' ) . eefstatify_get_date_period_string( $start, $end, $valid_start && $valid_end, true ); ?>'
			},
			xAxis: {
				categories: [ <?php echo $x; ?> ],
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
				filename: '<?php echo $filename; ?>'
			}
		});
	});
	</script>		
	</section>
<?php } ?>
</div>