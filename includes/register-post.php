<?php 
function custom_image_post_type() {
	$labels = array(
		'name'               => _x( 'Slider', 'post type general name', 'pk-slider' ),
		'singular_name'      => _x( 'Slider', 'post type singular name', 'pk-slider' ),
		'menu_name'          => _x( 'Sliders', 'admin menu', 'pk-slider' ),
		'name_admin_bar'     => _x( 'Slider', 'add new on admin bar', 'pk-slider' ),
		'add_new'            => _x( 'Add New', 'Slider', 'pk-slider' ),
		'add_new_item'       => __( 'Add New Slider', 'pk-slider' ),
		'new_item'           => __( 'New Slider', 'pk-slider' ),
		'edit_item'          => __( 'Edit Slider', 'pk-slider' ),
		'view_item'          => __( 'View Slider', 'pk-slider' ),
		'all_items'          => __( 'All Sliders', 'pk-slider' ),
		'search_items'       => __( 'Search Sliders', 'pk-slider' ),
		'parent_item_colon'  => __( 'Parent Sliders:', 'pk-slider' ),
		'not_found'          => __( 'No sliders found.', 'pk-slider' ),
		'not_found_in_trash' => __( 'No sliders found in Trash.', 'pk-slider' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'slider' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'thumbnail' ),
	);

	register_post_type( 'slider', $args );
}
add_action( 'init', 'custom_image_post_type' );