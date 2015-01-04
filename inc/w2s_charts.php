<?php
function percent($num_amount, $num_total) {
	$count1 = $num_amount / $num_total;
	$count2 = $count1 * 100;
	$count = number_format($count2, 0);
	echo $count;
}

function itemmeta_query($value) {
	global $wpdb;
// Query string to check wp_woocommerce_order_itemmeta for a specified meta_key
	$sql = "SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE meta_key IN ('".$value."')";

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

function counter_value_return($key, $counter) {
	$value = 0;
	if($counter[$key]) {
		$value = $counter[$key];
	}

	return $value;
}

add_shortcode('query-function', 'w2s_query_function_test');
function w2s_query_function_test() {
	itemmeta_query('membership_level');
}

add_shortcode('lodge-breakdown', 'w2s_lodge_breakdown');

function w2s_lodge_breakdown() {
	ob_start();
	wp_enqueue_script('chart-settings');
	$count = itemmeta_query('lodge');

?>
<canvas id="lodgeBreakdown" width="400" height="400"></canvas>
<script type="text/javascript">
jQuery(document).ready( function() {

// Chart Options
var options = {
<?php echo the_field('chart-options', 'option'); ?>
}


// Doughnut Chart Data
var doughnutData = [
	{
		value : <?php echo counter_value_return('Tupwee', $count); ?>,
		color : "purple",
		label : "Tupwee <?php  ?>"
	},
	{
		value : <?php echo counter_value_return('Ha-Kin-Skay-A-Ki', $count); ?>,
		color : "#1789D4",
		label : "Ha-Kin-Skay-A-Ki"
	},
	{
		value : <?php echo counter_value_return('Tahosa', $count); ?>,
		color : "#CB4B16",
		label : "Tahosa"
	},
	{
		value : <?php echo counter_value_return('Mic-O-Say', $count); ?>,
		color : "#1F8261",
		label : "Mic-O-Say"
	},
	{
		value : <?php echo counter_value_return('Other', $count); ?>,
		color : "#1F8261",
		label : "Other"
	}	

]


//Get the context of the Doughnut Chart canvas element we want to select
var ctx = document.getElementById("lodgeBreakdown").getContext("2d");

// Create the Doughnut Chart
var mydoughnutChart = new Chart(ctx).Doughnut(doughnutData, options);
});
</script>

<?php
	$myvariable = ob_get_clean();
	return $myvariable;
}

add_shortcode('chart-membership-level', 'w2s_membership_level');

function w2s_membership_level() {
	ob_start();
	wp_enqueue_script('chart-settings');
	$count = itemmeta_query('membership_level');

?>
<canvas id="membershipLevel" width="400" height="400"></canvas>
<script type="text/javascript">
jQuery(document).ready( function() {

// Chart Options
var options = {
<?php echo the_field('chart-options', 'option'); ?>
}


// Doughnut Chart Data
var doughnutData = [
	{
		value : <?php echo counter_value_return('Ordeal', $count); ?>,
		color : "purple",
		label : "Ordeal"
	},
	{
		value : <?php echo counter_value_return('Brotherhood', $count); ?>,
		color : "#1789D4",
		label : "Brotherhood"
	},
	{
		value : <?php echo counter_value_return('Vigil', $count); ?>,
		color : "#CB4B16",
		label : "Vigil"
	},
	{
		value : <?php echo counter_value_return('Guest', $count); ?>,
		color : "#1F8261",
		label : "Guest"
	}

]


//Get the context of the Doughnut Chart canvas element we want to select
var ctx = document.getElementById("membershipLevel").getContext("2d");

// Create the Doughnut Chart
var mydoughnutChart = new Chart(ctx).Doughnut(doughnutData, options);
});
</script>

<?php
	$myvariable = ob_get_clean();
	return $myvariable;
}