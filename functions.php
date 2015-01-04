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


// Enqueue scripts & styles here
function sublime_child_scripts() {
	wp_enqueue_script( 'mix-it-up', '//cdn.jsdelivr.net/jquery.mixitup/latest/jquery.mixitup.min.js', array(), 'v1.5.6', true);	
	wp_enqueue_script( 'main-js', get_stylesheet_directory_uri() . '/js/main.js', array(), '20120206', true );
	wp_enqueue_style('dashicons');
}
add_action( 'wp_enqueue_scripts', 'sublime_child_scripts' );

function sublime_load_adminjs() {
  
    if(is_admin()){
        wp_enqueue_script('sublime_admin_scripts', get_bloginfo('stylesheet_directory').'/js/admin.js', array('jquery'));
    }   
}

add_action('admin_init', 'sublime_load_adminjs');


?>