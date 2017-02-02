<?php
add_shortcode('chart-donut', 'w2s_donut_chart');

function w2s_donut_chart( $atts ) {
	ob_start();
	extract( shortcode_atts( array (
		'id' => '',
		'query' => '',
		'heading' => 'Registration Chart'
	), $atts ) );	
	wp_enqueue_script('chart-settings');
	$count = w2s_itemmeta_query_counts($query);

?>
<h3><?php echo $heading; ?></h3>
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
	$user_ID = 'user_'.get_current_user_id();
	$lodge_data_access = get_field('lodge_data_access', $user_ID);
	if ($lodge_data_access) {
		return $myvariable;
	} else {
		return 'You are not authorized to view this page.';
	}
}

add_shortcode('membership-percentage', 'w2s_membership_percentage');

function w2s_membership_percentage( $atts ) {
	ob_start();
	extract( shortcode_atts( array (
		'id' => '',
		'heading' => 'Registration Chart',
		'lodge' => '',
		'lodge_slug' => ''
	), $atts ) );	
	wp_enqueue_script('chart-settings');
	if ($lodge_slug == '') {
		$lodge_slug = $lodge;
	}
	$counts = w2s_itemmeta_query_counts('lodge');
	$lodge_registered = $counts[$lodge];
	$lodge_goal_field_name = $lodge_slug.'_arrowman_goal';
	$lodge_goal = get_field($lodge_goal_field_name, 'option');
	$goal_remaining = $lodge_goal - $lodge_registered;
	$lodge_membership_field_name = $lodge_slug.'_registered_arrowmen';
	$lodge_membership = get_field($lodge_membership_field_name, 'option');

?>
<h3><?php echo $heading; ?></h3>
<h4>Members Registered: <?php echo $lodge_registered; ?> / <?php echo $lodge_membership; ?> (<?php echo w2s_percent($lodge_registered, $lodge_membership); ?>) </h4>
<canvas id="<?php echo $id; ?>" class="doughnut-chart" width="400" height="400"></canvas>
<div id="<?php echo $id; ?>-legend"></div>
<script type="text/javascript">
jQuery(document).ready( function() {

// Chart Options
var options = {
<?php echo get_field('chart-options', 'option'); ?>
}

// Doughnut Chart Data
var doughnutData = 
[
  {
    "value": <?php echo $lodge_registered; ?>,
    "color": "#3A7D44",
    "label": "Registered"
  },
  {
    "value": <?php echo $goal_remaining; ?>,
    "color": "#ccc",
    "label": "To Reach Goal"
  }
]


//Get the context of the Doughnut Chart canvas element we want to select
var ctx = document.getElementById("<?php echo $id; ?>").getContext("2d");

// Create the Doughnut Chart
var myDoughnutChart = new Chart(ctx).Doughnut(doughnutData, options);
document.getElementById('<?php echo $id; ?>-legend').innerHTML = myDoughnutChart.generateLegend();
});
</script>

<?php
	$myvariable = ob_get_clean();
	$user_ID = 'user_'.get_current_user_id();
	$lodge_data_access = get_field('lodge_data_access', $user_ID);
	if ($lodge_data_access) {
		return $myvariable;
	} else {
		return 'You are not authorized to view this page.';
	}
}

add_shortcode('age-chart', 'w2s_age_chart');
function w2s_age_chart( $atts ) {
	ob_start();
	extract( shortcode_atts( array (
		'id' => '',
		'heading' => 'Registration Chart',
		'lodge' => '',
		'lodge_slug' => ''
	), $atts ) );	
	wp_enqueue_script('chart-settings');
	if ($lodge_slug == '') {
		$lodge_slug = $lodge;
	}
	$count = w2s_itemmeta_age();


?>
<h3><?php echo $heading; ?></h3>
<canvas id="<?php echo $id; ?>" class="doughnut-chart" width="400" height="400"></canvas>
<div id="<?php echo $id; ?>-legend"></div>
<script type="text/javascript">
jQuery(document).ready( function() {

// Chart Options
var options = {
<?php echo get_field('chart-options', 'option'); ?>
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
	$user_ID = 'user_'.get_current_user_id();
	$lodge_data_access = get_field('lodge_data_access', $user_ID);
	if ($lodge_data_access) {
		return $myvariable;
	} else {
		return 'You are not authorized to view this page.';
	}
}

add_shortcode('test-dob', 'w2s_test_dob');
function w2s_test_dob( $atts ) {
	$myvariable = print_r(w2s_itemmeta_age('adult'));
	return $myvariable;
}