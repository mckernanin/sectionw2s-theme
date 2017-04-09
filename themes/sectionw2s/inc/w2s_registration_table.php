<?php
function w2s_itemmeta_query_order_items() {
	global $wpdb;
	// Query string to check wp_woocommerce_order_itemmeta for a specified meta_key

	// Live Data
	$sql = "SELECT order_item_id FROM wp_woocommerce_order_items WHERE order_item_name IN ('Conclave 2017')";

	// Run the query via $wpdb
	$query = $wpdb->get_results( $sql );
	$results = array();
	foreach ( $query as $row ) {
		$results[] = $row->order_item_id;
	}

	return $results;
}

function w2s_itemmeta_query_order_item_data( $values ) {
	global $wpdb;

	// $sql = 'SELECT order_item_id,meta_key,meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id IN ("' . implode('", "', $values) . '")';
	$sql = 'SELECT order_item_id,meta_key,meta_value FROM wp_woocommerce_order_itemmeta';

	// Run the query via $wpdb
	$query = $wpdb->get_results( $sql, ARRAY_A );
	$order_meta = array();
	foreach ( $query as $row ) {
		$order_item_id = $row['order_item_id'];
		$meta_key = $row['meta_key'];
		$meta_value = $row['meta_value'];
		$order_meta[ $order_item_id ]['order_item_id'] = $order_item_id;
		$order_meta[ $order_item_id ][ $meta_key ] = $meta_value;
	}

	return $order_meta;
}

add_shortcode( 'registration-table', 'w2s_registration_table' );
function w2s_registration_table( $atts ) {
	ob_start();
	extract( shortcode_atts( array(
		'id' => '',
		'query' => '',
	), $atts ) );
	wp_enqueue_script( 'tablesorter' );
	$user_id = 'user_' . get_current_user_id();
	$lodge_data_access = get_field( 'lodge_data_access', $user_id );
	$show_dietary = get_query_var( 'show_dietary' );
	$tahosa_party_var = get_query_var( 'tahosa_party' );

?>
<span>You can sort this table by clicking on the header of the column you want to sort by.</span>
<table id="<?php echo $id; ?>" class="tablesorter">
	<?php if ( ( 'All' === $lodge_data_access ) || ( true == $show_dietary ) ) { ?>

	<thead>
		<th>Name</th>
		<th>Email</th>
		<th>Opt In</th>
		<th>Phone</th>
		<th>Lodge</th>
		<th>Membership Level</th>
		<th>Age Group</th>
		<?php if ( true == $show_dietary ) { ?>
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
		if ( isset( $_GET['tahosa_party'] ) ) {
			echo 'These guys like to party!';
			foreach ( $registrations as $registration ) {
				$item_id = $registration['_product_id'];
				$tahosa_party = $registration['Tahosa Lodge Party Pack'];
				if ( 1327 == $item_id && 'Yes ($5.00)' === $tahosa_party ) {
					$registration_count++;
					if ( 'My lodge is not listed' == $registration['lodge'] ) {
						$registration['lodge'] = $registration['lodge_other'];
					}
					$$registration_discount_id = $registration['order_item_id'] + 1;
					$registration_discount_amount = $registrations[ $registration_discount_id ]['discount_amount'];
					$registration_final_cost = '$' . $registration_discount_amount;
					echo '<tr class="row-' . $registration['order_item_id'] . '">';
					echo '<td class="name">' . $registration['Name'] . '</td>';
					echo '<td class="email">' . $registration['Email'] . '</td>';
					echo '<td class="email">' . $registration['Would you like to receive email updates from Section W2S?'] . '</td>';
					echo '<td class="phone">' . $registration['Phone'] . '</td>';
					echo '<td class="lodge">' . $registration['Lodge'] . '</td>';
					echo '<td class="membership-level">' . $registration['Membership Level'] . '</td>';
					echo '<td class="age-group">' . w2s_age_from_date( $registration['Birth Date'] ) . '</td>';
					if ( true == $show_dietary ) {
						echo '<td class="dietary">' . $registration['Dietary Restrictions'] . '</td>';
					}
					echo '<td class="sunday-breakfast">' . $registration['Are you staying for breakfast on Sunday?'] . '</td>';
					echo '<td class="amount-paid">' . $registration_final_cost . '</td>';
					echo '</tr>';
				}
			}
		} elseif ( ( 'All' === $lodge_data_access ) || ( true == $show_dietary ) ) {
			foreach ( $registrations as $registration ) {
				$item_id = $registration['_product_id'];
				if ( 1327 == $item_id ) {
					$registration_count++;
					if ( 'My lodge is not listed ($35.00)' == $registration['lodge'] ) {
						$registration['lodge'] = $registration['lodge_other'];
					}
					$registration_discount_id = $registration['order_item_id'] + 1;
					$registration_discount_amount = $registrations[ $registration_discount_id ]['discount_amount'];
					$registration_final_cost = '$' . $registration_discount_amount;
					echo '<tr class="row-' . $registration['order_item_id'] . '">';
					echo '<td class="name">' . $registration['Name'] . '</td>';
					echo '<td class="email">' . $registration['Email'] . '</td>';
					echo '<td class="email">' . $registration['Would you like to receive email updates from Section W2S?'] . '</td>';
					echo '<td class="phone">' . $registration['Phone'] . '</td>';
					echo '<td class="lodge">' . $registration['Lodge'] . '</td>';
					echo '<td class="membership-level">' . $registration['Membership Level'] . '</td>';
					echo '<td class="age-group">' . w2s_age_from_date( $registration['Birth Date'] ) . '</td>';
					if ( true == $show_dietary ) {
						echo '<td class="dietary">' . $registration['Dietary Restrictions'] . '</td>';
					}
					echo '<td class="sunday-breakfast">' . $registration['Are you staying for breakfast on Sunday?'] . '</td>';
					echo '<td class="amount-paid">' . $registration_final_cost . '</td>';
					echo '</tr>';
				}
			}
		} else {
			foreach ( $registrations as $registration ) {
				$item_id = $registration['_product_id'];
				$lodge = $registration['lodge'];
				if ( ( $item_id = 1327) && ( false !== strpos( $lodge_data_access, $lodge ) ) {
					$registration_count++;
					echo '<tr>';
					echo '<td class="name">' . $registration['Name'] . '</td>';
					echo '<td class="email">' . $registration['Email'] . '</td>';
					echo '<td class="phone">' . $registration['Phone'] . '</td>';
					echo '<td class="membership-level">' . $registration['Membership Level'] . '</td>';
					echo '<td class="age-group">' . w2s_age_from_date( $registration['Birth Date'] ) . '</td>';
					echo '</tr>';
				}
			}
		}// End if().

		?>
	</tbody>
</table>
<span class="registrationCount">Total Registered: <?php echo $registration_count; ?></span><br /><br /><br />
<a id="conclaveCSV" class="et_pb_promo_button" href="#!">Download CSV</a>

<?php
	$myvariable = ob_get_clean();
if ( $lodge_data_access ) {
	return $myvariable;
} else {
	return 'You are not authorized to view this page.';
}
}
