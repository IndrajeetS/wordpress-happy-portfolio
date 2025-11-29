<?php
/**
 * Custom Post Types (CPT) and Taxonomies
 *
 * @package HappyPortfolio
 * @subpackage Includes
 */

/**
 * Register Custom Post Type: Reading Lists
 */
function wedo_register_reading_list_cpt() {
     $labels = [
        'name'               => __( 'Reading Lists', 'wedo' ),
        'singular_name'      => __( 'Reading List', 'wedo' ),
        'menu_name'          => __( 'Reading Lists', 'wedo' ),
        'name_admin_bar'     => __( 'Reading List', 'wedo' ),
        'add_new'            => __( 'Add New', 'wedo' ),
        'add_new_item'       => __( 'Add New Reading List', 'wedo' ),
        'new_item'           => __( 'New Reading List', 'wedo' ),
        'edit_item'          => __( 'Edit Reading List', 'wedo' ),
        'view_item'          => __( 'View Reading List', 'wedo' ),
        'all_items'          => __( 'All Reading Lists', 'wedo' ),
        'search_items'       => __( 'Search Reading Lists', 'wedo' ),
        'parent_item_colon'  => __( 'Parent Reading Lists:', 'wedo' ),
        'not_found'          => __( 'No Reading Lists found.', 'wedo' ),
        'not_found_in_trash' => __( 'No Reading Lists found in Trash.', 'wedo' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'menu_icon'          => 'dashicons-book',
        'show_in_rest'       => true,
        'has_archive'        => true,
        'supports'           => ['title', 'thumbnail'],
        'rewrite'            => ['slug' => 'reading-list'],
    ];

    register_post_type( 'reading_list', $args );
}
add_action( 'init', 'wedo_register_reading_list_cpt' );

/**
 * Register Taxonomy: Reading List Categories
 */
function wedo_register_reading_list_categories() {
    $labels = [
        'name'              => __( 'Reading List Categories', 'wedo' ),
        'singular_name'     => __( 'Reading List Category', 'wedo' ),
        'search_items'      => __( 'Search Reading List Categories', 'wedo' ),
        'all_items'         => __( 'All Reading List Categories', 'wedo' ),
        'parent_item'       => __( 'Parent Category', 'wedo' ),
        'edit_item'         => __( 'Edit Reading List Category', 'wedo' ),
        'update_item'       => __( 'Update Reading List Category', 'wedo' ),
        'add_new_item'      => __( 'Add New Reading List Category', 'wedo' ),
        'new_item_name'     => __( 'New Reading List Category Name', 'wedo' ),
        'menu_name'         => __( 'Reading List Categories', 'wedo' ),
    ];

    $args = [
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'reading-list-category'],
    ];
    register_taxonomy( 'reading_list_category', ['reading_list'], $args );
}
add_action( 'init', 'wedo_register_reading_list_categories' );

/**
 * Add Meta Box for External Link
 */
function wedo_add_reading_list_meta_box() {
    add_meta_box(
        'wedo_reading_list_details',
        __( 'Reading Details', 'wedo' ),
        'wedo_render_reading_list_meta_box',
        'reading_list',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wedo_add_reading_list_meta_box' );

/**
 * Render Meta Box HTML
 */
function wedo_render_reading_list_meta_box( $post ) {
    $external_link = get_post_meta( $post->ID, '_wedo_reading_link', true );
    ?>
    <div class="field-group" style="margin-top:10px;">
        <label for="wedo_reading_link" style="font-weight:600; display:block; margin-bottom:4px;">
            <?php _e( 'External Link', 'wedo' ); ?>
        </label>
        <input
            type="url"
            name="wedo_reading_link"
            id="wedo_reading_link"
            value="<?php echo esc_attr( $external_link ); ?>"
            placeholder="https://example.com"
            style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;"
        >
    </div>
    <?php
}

/**
 * Save Meta Box Data
 */
function wedo_save_reading_list_meta( $post_id ) {
     if ( array_key_exists( 'wedo_reading_link', $_POST ) ) {
        update_post_meta( $post_id, '_wedo_reading_link', sanitize_text_field( $_POST['wedo_reading_link'] ) );
    }
}
add_action( 'save_post_reading_list', 'wedo_save_reading_list_meta' );

// EVERYTHING AFTER THIS POINT HAS BEEN REMOVED.
