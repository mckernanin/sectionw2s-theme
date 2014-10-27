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
	wp_enqueue_script( 'sublime-child-main-js', get_stylesheet_directory_uri() . '/js/main.js', array(), '20120206', true );
	// Enqueue Google fonts, don't @import them. Combine them into 1 request by separating the font families with a "|", like this:
	// wp_enqueue_style('googlefonts', '//fonts.googleapis.com/css?family=PT+Serif:400,400italic|Oswald:400,700');
}
add_action( 'wp_enqueue_scripts', 'sublime_child_scripts' );

// Sublime Activation Hook

function sublime_activation_hook() {

	if(!get_page_by_title('Home')) {
		$new_page = array(
			'slug' => 'home',
			'title' => 'Home',
			'content' => ""
		);
		$new_page_id = wp_insert_post( array(
			'post_title' => $new_page['title'],
			'post_type' 	=> 'page',
			'post_name'	 => $new_page['slug'],
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_content' => $new_page['content'],
			'post_status' => 'publish',
			'post_author' => 1,
			'menu_order' => 0
		));
	} //Create Home page if one doesn't exist
	
	if(!get_page_by_title('Terms of Use')) {
		$new_page = array(
			'slug' => 'terms',
			'title' => 'Terms of Use',
			'content' => ""
		);
		$new_page_id = wp_insert_post( array(
			'post_title' => $new_page['title'],
			'post_type' 	=> 'page',
			'post_name'	 => $new_page['slug'],
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_content' => $new_page['content'],
			'post_status' => 'publish',
			'post_author' => 1,
			'menu_order' => 0
		));
	} //Create Terms of Use page if one doesn't exist
	if(!get_page_by_title('Privacy Policy')) {
		$new_page = array(
			'slug' => 'privacy',
			'title' => 'Privacy Policy',
			'content' => ""
		);
		$new_page_id = wp_insert_post( array(
			'post_title' => $new_page['title'],
			'post_type' 	=> 'page',
			'post_name'	 => $new_page['slug'],
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_content' => $new_page['content'],
			'post_status' => 'publish',
			'post_author' => 1,
			'menu_order' => 0
		));
	} //Create Privacy Policy page if one doesn't exist
	
	if(!get_page_by_title('Blog')) {
		$new_page = array(
			'slug' => 'blog',
			'title' => 'Blog',
			'content' => ""
		);
		$new_page_id = wp_insert_post( array(
			'post_title' => $new_page['title'],
			'post_type' 	=> 'page',
			'post_name'	 => $new_page['slug'],
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_content' => $new_page['content'],
			'post_status' => 'publish',
			'post_author' => 1,
			'menu_order' => 0
		));
	} //Create Blog page if one doesn't exist
	
	// Use a static front page
	$home = get_page_by_title( 'Home' );
	update_option( 'page_on_front', $home->ID );
	update_option( 'show_on_front', 'page' );

	// Set the blog page
	$blog   = get_page_by_title( 'Blog' );
	update_option( 'page_for_posts', $blog->ID );
	
	update_option ('permalink_structure','/%year%/%month%/%day%/%postname%/'); //Dont know which to use
	
	$site_title = get_bloginfo('name');
	$site_desc = get_bloginfo ( 'description' );
	
	if ($site_title == "My blog"){
		update_option('blogname', 'Sublime Digital - New Site');
	}
	if ($site_desc == "Just another WordPress site"){
		update_option('blogdescription', 'Just another fine website by the happy folks at Sublime Digital');
	}
	
	
}


function sublime_divi_child_setup() {

	if (isset( $_GET['activated'] )) sublime_activation_hook();

}

add_action( 'after_setup_theme', 'sublime_divi_child_setup' );



?>