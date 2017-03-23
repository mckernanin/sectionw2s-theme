<?php
function w2s_percent($num_amount, $num_total) {
	$count1 = $num_amount / $num_total;
	$count2 = $count1 * 100;
	$count = number_format($count2, 2);
	echo $count.'%';
}

function w2s_itemmeta_query_counts($value) {
	global $wpdb;
	// Query string to check wp_woocommerce_order_itemmeta for a specified meta_key

	// Live Data
	$sql = "SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id > 1327 AND meta_key IN ('".$value."')";

	// Test Data
	// $sql = "SELECT meta_value FROM wp_kevin_test_data WHERE meta_key IN ('".$value."')";

	// Run the query via $wpdb
	$query = $wpdb->get_results($sql, ARRAY_N);

	// Create empty array $counter, which will contain the count of meta values. This will help build the graph data.
	$counter = array();

	// Check each query result for a new value, and add them to $counter
	foreach ($query as $row) {
		$meta_value =  $row[0];
		if (!in_array($row, $counter)) {
			$counter[$meta_value] = 0;
		}
	}

	/* Working on making this query all happen at the same time

		foreach ($query as $row) {
			$meta_value =  $row[0];
			if ( !in_array($row, $counter) ) {
				$counter[$meta_value] = 1;
			} else {
				$counter[$meta_value]++;
			}
		}
	*/

	/*
	At this point, the value of $counter is something like this:

	Array
	(
	    [Tahosa] => 0,
	    [Tupwee] => 0
	)

	These nested foreach loops will run through $query, looking for matches between the $row and $key
	*/

	foreach ($counter as $key => $value) {
		foreach ($query as $row) {
			if (in_array($key, $row)) {
				$counter[$key]++;
			}
		}
	}

	/*
		Now, $counter is an array that looks like this:
			Array
			(
			    [Tahosa] => 2,
			    [Tupwee] => 1
			)
	*/

	return $counter;

}

function w2s_itemmeta_age() {
	global $wpdb;
	// Query string to check wp_woocommerce_order_itemmeta for a specified meta_key

	// Live Data
	$sql = "SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id > 1327 AND meta_key IN ('birthdate')";

	// Run the query via $wpdb
	$query = $wpdb->get_results($sql, ARRAY_N);
	// print_r($query);

	// Create empty array $counter, which will contain the count of meta values. This will help build the graph data.
	$counter = array('Youth' => 0, 'Youth+' => 0, 'Adult' => 0);

	/*
	These nested foreach loops will run through $query, looking for matches between the $row and $key
	*/

	foreach ($query as $row) {
		//date in mm/dd/yyyy format; or it can be in other formats as well
		$birthDate = $row['0'];
		//explode the date to get month, day and year
		$birthDate = explode("/", $birthDate);
		//get age from date or birthdate
		$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
		? ((date("Y") - $birthDate[2]) - 1)
		: (date("Y") - $birthDate[2]));
		if ($age < '18') {
			$counter['Youth']++;
		} elseif ( ($age < '21') && ($age > '18')) {
			$counter['Youth+']++;
		} elseif ($age > '20') {
			$counter['Adult']++;
		}

	}

	return $counter;

}

function w2s_age_from_date($birthDate) {
		$birthDate = explode("/", $birthDate);
		$birthDate[0] = abs( $birthDate[0] );
		$birthDate[1] = abs( $birthDate[1] );
		$birthDate[2] = abs( $birthDate[2] );
		$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
		? ((date("Y") - $birthDate[2]) - 1)
		: (date("Y") - $birthDate[2]));
		if ($age < '18') {
			$age_group = 'Youth';
		} elseif ( ($age < '21') && ($age > '18')) {
			$age_group = 'Youth+';
		} elseif ($age > '20') {
			$age_group = 'Adult';
		}

		return $age_group;
}

function w2s_counter_value_return($key, $counter) {
	$value = 0;
	if($counter[$key]) {
		$value = $counter[$key];
	}

	return $value;
}

function w2s_rand_color() {
	$rand = array(
		'EEF4D4',
		'DAEFB3',
		'EA9E8D',
		'D64550',
		'1C2826',
		'546A76',
		'F8F272',
		'9E2B25',
		'51355A',
		'3E6259',
		'3A7D44',
		'13505B',
		'CF5C36',
		'FFF9A5',
		'7BDFF2',
		'D3E298'
	);
    $color = '#'.$rand[rand(0,15)];
    return $color;
}

function w2s_donut_data($source) {
	$final_json = array();
	foreach ($source as $key => $value) {
		$json = array();
		$json['value'] = $value;
		$json['color'] = w2s_rand_color();
		$json['label'] = $key;
		$final_json[] = $json;
	}
	echo json_encode($final_json);
}
