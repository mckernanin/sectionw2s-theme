<?php
function w2s_itemmeta_query_order_items() {
	global $wpdb;
	// Query string to check wp_woocommerce_order_itemmeta for a specified meta_key

	// Live Data
	$sql = "SELECT order_item_id FROM wp_woocommerce_order_items WHERE order_item_name IN ('Conclave 2016')";

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

	// $sql = 'SELECT order_item_id,meta_key,meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id IN ("' . implode('", "', $values) . '")';
	$sql = 'SELECT order_item_id,meta_key,meta_value FROM wp_woocommerce_order_itemmeta';

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
	$show_dietary = get_query_var('show_dietary');
	$tahosa_party_var = get_query_var( 'tahosa_party' );

?>
<span>You can sort this table by clicking on the header of the column you want to sort by.</span>
<table id="<?php echo $id; ?>" class="tablesorter">
	<?php if ( ($lodge_data_access == 'All') || ($show_dietary == true) ) { ?>

	<thead>
		<th>Name</th>
		<th>Email</th>
		<th>Opt In</th>
		<th>Phone</th>
		<th>Lodge</th>
		<th>Membership Level</th>
		<th>Age Group</th>
		<?php if ($show_dietary == true) { ?>
			<th>Dietary Needs</th>
		<?php } ?>
		<th>Sunday Breakfast</th>
		<th>Discount</th>
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
			if ( ($lodge_data_access == 'All') || ($show_dietary == true) ) {
				foreach ($registrations as $registration) {
					$item_id = $registration['_product_id'];
					if ($item_id == 1096) {
						$registration_count++;
						if ($registration['lodge'] == 'My lodge is not listed ($35.00)') {
							$registration['lodge'] = $registration['lodge_other'];
						}
						$registrationDiscountID = $registration['order_item_id'] + 1;
						$registrationDiscountAmount = $registrations[$registrationDiscountID]['discount_amount'];
						$registrationFinalCost = '$'.$registrationDiscountAmount;
						echo '<tr class="row-'.$registration['order_item_id'].'">';
						echo '<td class="name">'.$registration['Name'].'</td>';
						echo '<td class="email">'.$registration['Email'].'</td>';
						echo '<td class="email">'.$registration['Would you like to receive email updates from Section W2S?'].'</td>';
						echo '<td class="phone">'.$registration['Phone'].'</td>';
						echo '<td class="lodge">'.$registration['Lodge'].'</td>';
						echo '<td class="membership-level">'.$registration['Membership Level'].'</td>';
						echo '<td class="age-group">'.w2s_age_from_date($registration['Birth Date']).'</td>';
						if ($show_dietary == true) {
						echo '<td class="age-group">'.w2s_age_from_date( $registration['Birth Date']).'</td>';
						if ( $show_dietary == true) {
							echo '<td class="dietary">'.$registration['Dietary Restrictions'].'</td>';
						}
						echo '<td class="sunday-breakfast">'.$registration['Are you staying for breakfast on Sunday?'].'</td>';
						echo '<td class="amount-paid">'.$registrationFinalCost.'</td>';
						echo '</tr>';
					}
				}
			} elseif ( 'true' === $tahosa_party_var ) {
				echo 'These guys like to party!';
				foreach ( $registrations as $registration) {
					$item_id = $registration['_product_id'];
					$tahosa_party = $registration['Tahosa Lodge Party Pack'];
					if ( $item_id == 1096 && 'Yes ( $5.00)' === $tahosa_party ) {
						$registration_count++;
						if ( $registration['lodge'] == 'My lodge is not listed ( $35.00)') {
							$registration['lodge'] = $registration['lodge_other'];
						}
						$registrationDiscountID = $registration['order_item_id'] + 1;
						$registrationDiscountAmount = $registrations[ $registrationDiscountID]['discount_amount'];
						$registrationFinalCost = '$'.$registrationDiscountAmount;
						echo '<tr class="row-'.$registration['order_item_id'].'">';
						echo '<td class="name">'.$registration['Name'].'</td>';
						echo '<td class="email">'.$registration['Email'].'</td>';
						echo '<td class="email">'.$registration['Would you like to receive email updates from Section W2S?'].'</td>';
						echo '<td class="phone">'.$registration['Phone'].'</td>';
						echo '<td class="lodge">'.$registration['Lodge'].'</td>';
						echo '<td class="membership-level">'.$registration['Membership Level'].'</td>';
						echo '<td class="age-group">'.w2s_age_from_date( $registration['Birth Date']).'</td>';
						if ( $show_dietary == true) {
							echo '<td class="dietary">'.$registration['Dietary Restrictions'].'</td>';
						}
						echo '<td class="sunday-breakfast">'.$registration['Are you staying for breakfast on Sunday?'].'</td>';
						echo '<td class="amount-paid">'.$registrationFinalCost.'</td>';
						echo '</tr>';
					}
				}
			} else {
				foreach ($registrations as $registration) {
					$item_id = $registration['_product_id'];
					$lodge = $registration['lodge'];
					if ( ($item_id = 1096) && ($lodge == $lodge_data_access) ) {
						$registration_count++;
						echo '<tr>';
						echo '<td class="name">'.$registration['Name'].'</td>';
						echo '<td class="email">'.$registration['Email'].'</td>';
						echo '<td class="phone">'.$registration['Phone'].'</td>';
						echo '<td class="membership-level">'.$registration['Membership Level'].'</td>';
						echo '<td class="age-group">'.w2s_age_from_date($registration['Birth Date']).'</td>';
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
