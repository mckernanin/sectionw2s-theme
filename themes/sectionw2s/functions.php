<?php

// Page Slug Body Class
function add_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

// Use this action and function to remove / replace functions of Divi
add_action( 'after_setup_theme', 'remove_parent_theme_features', 10 );

function remove_parent_theme_features() {
	// Replace a shortcode by first removing it, like this:
	// remove_shortcode( 'et_pb_filterable_portfolio' );
	//
	// Then replace the shortcode with our own custom function, but using the original shortcode name:
	// add_shortcode( 'et_pb_filterable_portfolio', 'sublime_child_filterable_portfolio' );
	include_once 'inc/elegant-theme-update.php';
}

// For simplicity's sake, create a file for each shortcode, and place it in the /inc directory.
// Then include it here, like this: require_once('inc/sublime_child_filterable_portfolio.php');
require_once 'inc/w2s_post_types.php';
require_once 'inc/w2s_chart_support_functions.php';
require_once 'inc/w2s_charts.php';
require_once 'inc/w2s_registration_table.php';

// Enqueue scripts & styles here
function sublime_child_scripts() {
	wp_enqueue_script( 'mix-it-up', '//cdn.jsdelivr.net/jquery.mixitup/latest/jquery.mixitup.min.js', array(), 'v1.5.6', true );
	wp_register_script( 'tablesorter', '//cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.18.4/js/jquery.tablesorter.min.js', true );
	wp_enqueue_script( 'main-js', get_stylesheet_directory_uri() . '/js/main.js', array(), '20120206', true );
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_script( 'chart-js', get_stylesheet_directory_uri() . '/js/chart.js', true );
}
add_action( 'wp_enqueue_scripts', 'sublime_child_scripts' );

function sublime_load_adminjs() {

	if ( is_admin() ) {
		wp_enqueue_script( 'sublime_admin_scripts', get_stylesheet_directory_uri() . '/js/admin.js', array( 'jquery' ) );
	}
}

add_action( 'admin_init', 'sublime_load_adminjs' );

if ( function_exists( 'acf_add_options_page' ) ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Website Settings',
		'menu_title'	=> 'Website Settings',
		'menu_slug' 	=> 'website-settings',
		'capability'	=> 'manage_options',
		'redirect'		=> false,
	));

	acf_add_options_page(array(
		'page_title' 	=> 'Conclave 2015',
		'menu_title'	=> 'Conclave 2015',
		'menu_slug' 	=> 'conclave-2015',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
	));
};

require_once 'inc/woocommerce-custom.php';

if ( ! class_exists( 'BlockSemalt' ) ) :

	class BlockSemalt {

		static function enable() {
			include_once( ABSPATH . '/wp-admin/includes/misc.php' );
			$htaccess_file = ABSPATH . '.htaccess';

			$rules = "RewriteEngine on\n";
			$rules .= "RewriteCond %{HTTP_REFERER} ^http://([^.]+\.)*semalt\.com [NC]\n";
			$rules .= "RewriteRule (.*) http://www.semalt.com [R=301,L]\n";

			$rules = explode( "\n", $rules );
			insert_with_markers( $htaccess_file, 'Block Semalt', $rules );
		}

		static function disable() {
			include_once( ABSPATH . '/wp-admin/includes/misc.php' );
			$htaccess_file = ABSPATH . '.htaccess';

			insert_with_markers( $htaccess_file, 'Block Semalt', '' );
		}
	}

	register_activation_hook( __FILE__, array( 'BlockSemalt', 'enable' ) );
	register_deactivation_hook( __FILE__, array( 'BlockSemalt', 'disable' ) );

endif;

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );
add_action( 'woocommerce_before_shop_loop_item_title', 'w2s_template_loop_product_thumbnail' );

function w2s_template_loop_product_thumbnail() {
	global $product;
	echo '<span class="et_shop_image">';
	echo woocommerce_get_product_thumbnail();
	echo '<span class="et_overlay">';
	if ( ! wp_is_mobile() ) {
		if ( $product->has_child() ) {
			echo '<a href=' . esc_attr( get_permalink( $product->id ) ) . ' class="button alt add_to_cart_button">Select A Size</a>';
		} else {
			echo '<button type="submit" data-quantity="1" data-product_id="' . esc_attr( $product->id ) . '" class="button alt add_to_cart_button product_type_simple">Add to cart</button>';
		}
		echo '<a href=' . esc_attr( get_permalink( $product->id ) ) . ' class="button alt add_to_cart_button">Product Details</a>';
	}
	echo '</span>';
	echo '</span>';
	if ( wp_is_mobile() ) {
		echo '<span class="mobile-buttons"><button type="submit" data-quantity="1" data-product_id="' . esc_attr( $product->id ) . '" class="button alt add_to_cart_button product_type_simple">Add to cart</button>';
		echo '<a href=' . esc_attr( get_permalink( $product->id ) ) . ' class="button alt add_to_cart_button">Product Details</a></span>';
	}
	// printf( '<span class="et_shop_image">%1$s<span class="et_overlay"></span></span>',
	// 	woocommerce_get_product_thumbnail()
	// );
}

add_filter( 'query_vars', 'add_my_var' );
function add_my_var( $public_query_vars ) {
	$public_query_vars[] = 'show_dietary';
	return $public_query_vars;
}

function vigil_position_notes_field( $input, $input_info, $field, $text, $value, $form_id ) {
	//build field name, must match List field syntax to be processed correctly
	$input_field_name = 'input_' . $field->id . '[]';
	$tabindex         = GFCommon::get_tabindex();
	$new_input        = '<textarea name="' . $input_field_name . '" ' . $tabindex . ' class="textarea medium" cols="50" rows="10">' . $value . '</textarea>';
	return $new_input;
}

function vigil_position_type( $input_info, $field, $column, $value, $form_id ) {
	$data = array(
		'type'    => 'select',
		'choices' => 'Unit,Chapter,Lodge,Section,District,Council,Other',
	);
	return $data;
}
add_filter( 'gform_column_input_content_6_3_4', 'vigil_position_notes_field', 10, 6 );
add_filter( 'gform_column_input_6_3_1', 'vigil_position_type', 10, 6 );
