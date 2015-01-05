<?php
function w2s_percent($num_amount, $num_total) {
	$count1 = $num_amount / $num_total;
	$count2 = $count1 * 100;
	$count = number_format($count2, 0);
	echo $count;
}

function w2s_itemmeta_query_counts($value) {
	global $wpdb;
	// Query string to check wp_woocommerce_order_itemmeta for a specified meta_key

	// Live Data
	$sql = "SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE meta_key IN ('".$value."')";
	
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

	/*
	At this point, the value of $counter is something like this:

	Array
	(
	    [Tahosa] => 0
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

	return $counter;

}

function w2s_counter_value_return($key, $counter) {
	$value = 0;
	if($counter[$key]) {
		$value = $counter[$key];
	}

	return $value;
}

function rand_color() {
	$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
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

add_shortcode('chart-donut', 'w2s_donut_chart');

function w2s_donut_chart( $atts ) {
	ob_start();
	extract( shortcode_atts( array (
		'id' => '',
		'query' => ''
	), $atts ) );	
	wp_enqueue_script('chart-settings');
	$count = w2s_itemmeta_query_counts($query);

?>
<canvas id="<?php echo $id; ?>" class="doughnut-chart" width="400" height="400"></canvas>
<div id="<?php echo $id; ?>-legend"></div>
<script type="text/javascript">
jQuery(document).ready( function() {

// Chart Options
var options = {
<?php echo the_field('chart-options', 'option'); ?>
}

// Doughnut Chart Data
var doughnutData = <?php echo w2s_donut_data($count); ?>


//Get the context of the Doughnut Chart canvas element we want to select
var ctx = document.getElementById("<?php echo $id; ?>").getContext("2d");

// Create the Doughnut Chart
var myDoughnutChart = new Chart(ctx).Doughnut(doughnutData, options);
document.getElementById('<?php echo $id; ?>-legend').innerHTML = myDoughnutChart.generateLegend();
});
</script>

<?php
	$myvariable = ob_get_clean();
	return $myvariable;
}