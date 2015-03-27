<?php
function w2s_itemmeta_query_order_items() {
	global $wpdb;
	// Query string to check wp_woocommerce_order_itemmeta for a specified meta_key

	// Live Data
	$sql = "SELECT order_item_id FROM wp_woocommerce_order_items WHERE order_item_name IN ('Conclave 2015')";

	// Run the query via $wpdb
	$query = $wpdb->get_results($sql);
	$results = array();
	foreach( $query as $row ) {
		$results[] = $row->order_item_id;
	}

	return $results;
}

function w2s_itemmeta_query_order_item_data($values) {
	global $wpdb;

	$sql = 'SELECT order_item_id,meta_key,meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id IN ("' . implode('", "', $values) . '")';

	// Run the query via $wpdb
	$query = $wpdb->get_results($sql, ARRAY_A);
	$order_meta = array();
	foreach ($query as $row) {
		$order_item_id = $row['order_item_id'];
		$meta_key = $row['meta_key'];
		$meta_value = $row['meta_value'];
		$order_meta[$order_item_id]['order_item_id'] = $order_item_id;
		$order_meta[$order_item_id][$meta_key] = $meta_value;
	}

	return $order_meta;	
}

add_shortcode('registration-table', 'w2s_registration_table');
function w2s_registration_table( $atts ) {
	ob_start();
	extract( shortcode_atts( array (
		'id' => '',
		'query' => ''
	), $atts ) );
	wp_enqueue_script('tablesorter');
	$user_ID = 'user_'.get_current_user_id();
	$lodge_data_access = get_field('lodge_data_access', $user_ID);

?>
<span>You can sort this table by clicking on the header of the column you want to sort by.</span>
<table id="<?php echo $id; ?>" class="tablesorter">
	<?php if ($lodge_data_access == 'All') { ?>

	<thead>
		<th>Name</th>
		<th>Email</th>
		<th>Phone</th>
		<th>Lodge</th>
		<th>Membership Level</th>
		<th>Age Group</th>
	</thead>

	<?php } else { ?>

	<thead>
		<th>Name</th>
		<th>Email</th>
		<th>Phone</th>
		<th>Membership Level</th>
		<th>Age Group</th>
	</thead>

	<?php } ?>


	<tbody>
		<?php 
			$registrations = w2s_itemmeta_query_order_item_data( w2s_itemmeta_query_order_items() ); 
			$registration_count = 0;
			if ($lodge_data_access == 'All') {
				foreach ($registrations as $registration) {
					$item_id = $registration['_product_id'];
					if ($item_id = 656) {
						$registration_count++;
						if ($registration['lodge'] == 'My lodge is not listed ($35.00)') {
							$registration['lodge'] = $registration['lodge_other'];
						}
						echo '<tr>';
						echo '<td class="name">'.$registration['name'].'</td>';
						echo '<td class="email">'.$registration['email'].'</td>';
						echo '<td class="phone">'.$registration['phone'].'</td>';
						echo '<td class="lodge">'.$registration['lodge'].'</td>';
						echo '<td class="membership-level">'.$registration['membership_level'].'</td>';
						echo '<td class="age-group">'.w2s_age_from_date($registration['birthdate']).'</td>';
						echo '</tr>';
					}
				}
			} else {
				foreach ($registrations as $registration) {
					$item_id = $registration['_product_id'];
					$lodge = $registration['lodge'];
					if ( ($item_id = 656) && ($lodge == $lodge_data_access) ) {
						$registration_count++;
						echo '<tr>';
						echo '<td class="name">'.$registration['name'].'</td>';
						echo '<td class="email">'.$registration['email'].'</td>';
						echo '<td class="phone">'.$registration['phone'].'</td>';
						echo '<td class="membership-level">'.$registration['membership_level'].'</td>';
						echo '<td class="age-group">'.w2s_age_from_date($registration['birthdate']).'</td>';
						echo '</tr>';
					}
				}
			}

		?>
	</tbody>
</table>
<span class="registrationCount">Total Registered: <?php echo $registration_count; ?></span><br /><br /><br />
<a id="conclaveCSV" class="et_pb_promo_button">Download CSV</a>

<?php
	$myvariable = ob_get_clean();
	if ($lodge_data_access) {
		return $myvariable;
	} else {
		return 'You are not authorized to view this page.';
	}
	
	
}