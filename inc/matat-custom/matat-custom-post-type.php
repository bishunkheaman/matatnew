<?php
/**
 * Register post type and taxonmy
 */

/**
 * Create two taxonomies, genres and writers for the post type "brands".
 *
 * @see register_post_type() for registering custom post types.
 */

function brands_setup_post_type() {
    $args = array(
        'public'    => true,
        'label'     => __( 'Brands', 'textdomain' ),
        'menu_icon' => 'dashicons-brands',
    );
    register_post_type( 'brands', $args );
}
add_action( 'init', 'brands_setup_post_type' );


function wpdocs_create_brands_taxonomies() {
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name'              => _x( 'Genres', 'taxonomy general name', 'textdomain' ),
        'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'textdomain' ),
        'search_items'      => __( 'Search Genres', 'textdomain' ),
        'all_items'         => __( 'All Genres', 'textdomain' ),
        'parent_item'       => __( 'Parent Genre', 'textdomain' ),
        'parent_item_colon' => __( 'Parent Genre:', 'textdomain' ),
        'edit_item'         => __( 'Edit Genre', 'textdomain' ),
        'update_item'       => __( 'Update Genre', 'textdomain' ),
        'add_new_item'      => __( 'Add New Genre', 'textdomain' ),
        'new_item_name'     => __( 'New Genre Name', 'textdomain' ),
        'menu_name'         => __( 'Genre', 'textdomain' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'genre' ),
    );

    register_taxonomy( 'genre', array( 'brands' ), $args );

    unset( $args );
    unset( $labels );
}
// hook into the init action and call create_brands_taxonomies when it fires
//add_action( 'init', 'wpdocs_create_brands_taxonomies', 0 );

