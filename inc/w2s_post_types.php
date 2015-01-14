<?php

add_action('init', 'w2s_register_cpt_people');
function w2s_register_cpt_people() {
register_post_type('people', 
		array(
		'label' => 'People',
		'description' => '',
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => array('slug' => 'people', 'with_front' => true),
		'query_var' => true,
		'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes','post-formats'),
		'labels' => array (
			'name' => 'People',
			'singular_name' => 'Person',
			'menu_name' => 'People',
			'add_new' => 'Add Person',
			'add_new_item' => 'Add New Person',
			'edit' => 'Edit',
			'edit_item' => 'Edit Person',
			'new_item' => 'New Person',
			'view' => 'View Person',
			'view_item' => 'View Person',
			'search_items' => 'Search People',
			'not_found' => 'No People Found',
			'not_found_in_trash' => 'No People Found in Trash',
			'parent' => 'Parent Person',
			)
		) 
	); 
}

add_action('init', 'w2s_register_cpt_positions');
function w2s_register_cpt_positions() {
register_post_type('positions', 
	array(
		'label' => 'COC Positions',
		'description' => '',
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => array('slug' => 'positions', 'with_front' => true),
		'query_var' => true,
		'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes','post-formats'),
		'labels' => array (
			'name' => 'COC Positions',
			'singular_name' => 'COC Position',
			'menu_name' => 'COC Positions',
			'add_new' => 'Add COC Position',
			'add_new_item' => 'Add New COC Position',
			'edit' => 'Edit',
			'edit_item' => 'Edit COC Position',
			'new_item' => 'New COC Position',
			'view' => 'View COC Position',
			'view_item' => 'View COC Position',
			'search_items' => 'Search COC Positions',
			'not_found' => 'No COC Positions Found',
			'not_found_in_trash' => 'No COC Positions Found in Trash',
			'parent' => 'Parent COC Position',
			)
		) 
	); 
}