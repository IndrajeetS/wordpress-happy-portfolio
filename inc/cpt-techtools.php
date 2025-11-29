<?php
/**
 * Custom Post Type: Tech Tools
 */

function happy_register_techtools_cpt() {

    $labels = array(
        'name'                  => _x( 'Tech Tools', 'Post Type General Name' ),
        'singular_name'         => _x( 'Tech Tool', 'Post Type Singular Name' ),
        'menu_name'             => __( 'Tech Tools' ),
        'name_admin_bar'        => __( 'Tech Tool' ),
        'add_new'               => __( 'Add New Tool' ),
        'add_new_item'          => __( 'Add New Tech Tool' ),
        'edit_item'             => __( 'Edit Tech Tool' ),
        'new_item'              => __( 'New Tech Tool' ),
        'view_item'             => __( 'View Tech Tool' ),
        'search_items'          => __( 'Search Tech Tools' ),
    );

    $args = array(
        'label'                 => __( 'Tech Tools' ),
        'labels'                => $labels,
        'public'                => true,
        'show_in_rest'          => true, // Gutenberg support
        'menu_icon'             => 'dashicons-hammer',
        'supports'              => array( 'title', 'thumbnail' ), // Only image + title
        'has_archive'           => true,
        'rewrite'               => array( 'slug' => 'techtools' ),
    );

    register_post_type( 'techtools', $args );
}

add_action( 'init', 'happy_register_techtools_cpt' );
