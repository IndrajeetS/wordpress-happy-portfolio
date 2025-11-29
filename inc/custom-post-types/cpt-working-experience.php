<?php
if (!defined('ABSPATH')) exit;

/**
 * Register Custom Post Type: Working Experience
 */
function happy_register_working_experience_cpt() {

    $labels = [
        'name'               => 'Working Experience',
        'singular_name'      => 'Working Experience',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Experience',
        'edit_item'          => 'Edit Experience',
        'new_item'           => 'New Experience',
        'view_item'          => 'View Experience',
        'search_items'       => 'Search Experiences',
        'not_found'          => 'No experiences found',
        'not_found_in_trash' => 'No experiences found in Trash'
    ];

    $args = [
        'label'               => 'Working Experience',
        'labels'              => $labels,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-laptop',
        'supports'            => ['title', 'editor', 'thumbnail'],
        'has_archive'         => false,
        'rewrite'             => ['slug' => 'working-experience'],
        'show_in_rest'        => true,
    ];

    register_post_type('working_experience', $args);
}
add_action('init', 'happy_register_working_experience_cpt');
