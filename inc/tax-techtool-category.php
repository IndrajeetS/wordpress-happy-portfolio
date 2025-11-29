<?php
/**
 * Taxonomy: Tech Tool Categories
 */

function happy_register_techtool_category_taxonomy() {

    $labels = array(
        'name'              => _x( 'Tech Tool Categories', 'taxonomy general name' ),
        'singular_name'     => _x( 'Tech Tool Category', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Categories' ),
        'all_items'         => __( 'All Categories' ),
        'parent_item'       => __( 'Parent Category' ),
        'parent_item_colon' => __( 'Parent Category:' ),
        'edit_item'         => __( 'Edit Category' ),
        'update_item'       => __( 'Update Category' ),
        'add_new_item'      => __( 'Add New Category' ),
        'new_item_name'     => __( 'New Category Name' ),
        'menu_name'         => __( 'Categories' ),
    );

    $args = array(
        'hierarchical'      => true, // behaves like categories
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true, // Gutenberg compatible
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'techtool-category' ),
    );

    register_taxonomy( 'techtool_category', array( 'techtools' ), $args );
}

add_action( 'init', 'happy_register_techtool_category_taxonomy' );
