<?php
/**
 * Taxonomy: Project Categories
 */

function happy_register_project_category_taxonomy() {

    $labels = array(
        'name'              => _x( 'Project Categories', 'taxonomy general name' ),
        'singular_name'     => _x( 'Project Category', 'taxonomy singular name' ),
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
        'rewrite'           => array( 'slug' => 'project-category', 'with_front' => false ),
    );

    register_taxonomy( 'project_category', array( 'projects' ), $args );
}

add_action( 'init', 'happy_register_project_category_taxonomy' );
