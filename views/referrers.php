<?php
/**
 * The referrers page.
 *
 * @package extended-evaluation-for-statify
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Get the selected post if one is set, otherwise: all posts.
if ( isset( $_POST['post'] ) && check_admin_referer( 'referrers' ) && 'all' !== $_POST['post'] ) {
	$selected_post = sanitize_text_field( wp_unslash( $_POST['post'] ) );
} else {
	$selected_post = '';
}

// Reset variables and get post parameters for the dates if submitted.
$valid_start = false;
$valid_end = false;
$message = '';
$start = '';
$end = '';

// Check for at least one date set and valid wp_nonce.
if ( isset( $_POST['start'] ) && isset( $_POST['end'] ) && check_admin_referer( 'referrers' ) ) {
	$start = sanitize_text_field( wp_unslash( $_POST['start'] ) );
	$end = sanitize_text_field( wp_unslash( $_POST['end'] ) );
	if ( '' !== $start || '' !== $end ) {
		$valid_start = eefstatify_is_valid_date_string( $start );
		$valid_end = eefstatify_is_valid_date_string( $end );
		if ( ! $valid_start || ! $valid_end ) {
			// Error message if at least one date is not valid.
			$message = __( 'No valid date period set. Please enter a valid start and a valid end date!', 'extended-evaluation-for-statify' );
		}
	}
}

$referrers = eefstatify_get_views_for_all_referrers( $selected_post, $start, $end );
$referrers_for_diagram = array_slice( $referrers, 0, 25, true );

$filename = eefstatify_get_filename(
	__( 'Referrers', 'extended-evaluation-for-statify' )
	. eefstatify_get_date_period_string( $start, $end, $valid_start && $valid_end )
	. '-' . eefstatify_get_post_title_from_url( $selected_post )
);
?>
<div class="wrap eefstatify">
	<h1><?php esc_html_e( 'Statify – Extended Evaluation', 'extended-evaluation-for-statify' ); ?>
			&rsaquo; <?php esc_html_e( 'Referrers from other websites', 'extended-evaluation-for-statify' ); ?></h1>
	<?php if ( '' !== $message ) { ?>
	<div class="notice notice-error">
		<p><?php echo esc_html( $message ); ?></p>
	</div>
	<?php } ?>
	<form method="post">
		<?php wp_nonce_field( 'referrers' ); ?>
		<?php eefstatify_echo_date_selection( $valid_start, $start, $valid_end, $end ); ?>
		<fieldset>
			<legend><?php esc_html_e( 'Per default the views of all posts are shown. To restrict the evaluation to one post/page, select one.', 'extended-evaluation-for-statify' ); ?></legend>
			<label for="post"><?php esc_html_e( 'Post/Page', 'extended-evaluation-for-statify' ); ?></label>
			<select id="post" name="post" required="required">
				<option value="all" 
					<?php
					if ( '' === $selected_post ) {
						echo 'selected="selected"';
					}
					?>
					><?php esc_html_e( 'all posts', 'extended-evaluation-for-statify' ); ?></option>
				<?php
				$posts = eefstatify_get_post_urls();
				foreach ( $posts as $post ) {
				?>
				<option value="<?php echo esc_html( $post['target'] ); ?>"
					<?php
					if ( $post['target'] === $selected_post ) {
						echo 'selected="selected"';
					}
					?>
					><?php echo esc_html( eefstatify_get_post_title_from_url( $post['target'] ) ); ?></option>
				<?php } ?>
			</select>
			<button type="submit" class="button-secondary"><?php esc_html_e( 'Select post/page', 'extended-evaluation-for-statify' ); ?></button>
		</fieldset>
	</form>
<?php if ( count( $referrers ) === 0 ) { ?>
	<p><?php esc_html_e( 'No data available.', 'extended-evaluation-for-statify' ); ?></p>
<?php } else { ?>
	<section>
		<div id="chart"></div>
		<script type="text/javascript">
			eefstatifyColumnChart(
				'#chart',
				'<?php esc_html_e( 'Referrers from other websites', 'extended-evaluation-for-statify' ); ?>',
				'<?php echo esc_html( get_bloginfo( 'name' ) . ' ' . eefstatify_get_post_title_from_url( $selected_post ) . eefstatify_get_date_period_string( $start, $end, $valid_start && $valid_end, true ) ); ?>',
				[ 
					<?php
					foreach ( $referrers_for_diagram as $referrer ) {
						echo "'" . esc_html( $referrer['host'] ) . "',";
					}
					?>
				],
				[
					<?php
					foreach ( $referrers_for_diagram as $referrer ) {
						echo esc_html( $referrer['count'] . ',' );
					}
					?>
				],
				'<?php esc_html_e( 'Views', 'extended-evaluation-for-statify' ); ?>',
				'<?php echo esc_html( $filename ); ?>'
			);
		</script>
	</section>	
	<section>
		<h3><?php esc_html_e( 'Referrers from other websites', 'extended-evaluation-for-statify' ); ?>
			<?php echo esc_html( eefstatify_get_date_period_string( $start, $end, $valid_start && $valid_end, true ) ); ?>
			<?php
			echo esc_html( eefstatify_get_post_type_name_and_title_from_url( $selected_post ) );
			eefstatify_echo_export_button( $filename );
			?>
			</h3>
		<table id="table-data" class="wp-list-table widefat striped">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Referring Domain', 'extended-evaluation-for-statify' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Views', 'extended-evaluation-for-statify' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Proportion', 'extended-evaluation-for-statify' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$total = 0;
				foreach ( $referrers as $referrer ) {
					$total += $referrer['count'];
				}
				foreach ( $referrers as $referrer ) {
				?>
				<tr>
					<td><a href="<?php echo esc_url( $referrer['url'] ); ?>" target="_blank"><?php echo esc_html( $referrer['host'] ); ?></a></td>
					<td class="right"><?php eefstatify_echo_number( $referrer['count'] ); ?></td>
					<td class="right"><?php eefstatify_echo_percentage( $referrer['count'] / $total ); ?></td>
				</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td><?php esc_html_e( 'Sum', 'extended-evaluation-for-statify' ); ?></td>
					<td class="right"><?php eefstatify_echo_number( $total ); ?></td>
					<td class="right"><?php eefstatify_echo_percentage( 1 ); ?></td>
				</tr>
			</tfoot>
		</table>
	</section>
	<?php } ?>
</div>
