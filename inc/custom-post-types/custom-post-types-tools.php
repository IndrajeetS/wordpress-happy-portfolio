<?php
/**
 * Custom Post Type and Taxonomy Registration
 *
 * @package HappyPortfolio
 * @subpackage Includes
 */

/**
 * Register Custom Post Type: Resource Tools
 */
function wedo_register_resource_tools_cpt() {
    $labels = [
        'name'               => __( 'Resources', 'wedo' ),
        'singular_name'      => __( 'Resource', 'wedo' ),
        'menu_name'          => __( 'Resources', 'wedo' ),
        'name_admin_bar'     => __( 'Resource', 'wedo' ),
        'add_new'            => __( 'Add New', 'wedo' ),
        'add_new_item'       => __( 'Add New Resource', 'wedo' ),
        'new_item'           => __( 'New Resource', 'wedo' ),
        'edit_item'          => __( 'Edit Resource', 'wedo' ),
        'view_item'          => __( 'View Resource', 'wedo' ),
        'all_items'          => __( 'All Resources', 'wedo' ),
        'search_items'       => __( 'Search Resources', 'wedo' ),
        'parent_item_colon'  => __( 'Parent Resources:', 'wedo' ),
        'not_found'          => __( 'No resources found.', 'wedo' ),
        'not_found_in_trash' => __( 'No resources found in Trash.', 'wedo' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'menu_icon'          => 'dashicons-hammer',
        'show_in_rest'       => true,
        'has_archive'        => true,
        'supports'           => ['title', 'editor', 'thumbnail'],
        'rewrite'            => ['slug' => 'resources'],
    ];

    register_post_type( 'resource_tools', $args );
}
add_action( 'init', 'wedo_register_resource_tools_cpt' );

/**
 * Register Taxonomy: Resource Categories
 */
function wedo_register_resource_categories() {
    $labels = [
        'name'              => __( 'Categories', 'wedo' ),
        'singular_name'     => __( 'Category', 'wedo' ),
        'search_items'      => __( 'Search Categories', 'wedo' ),
        'all_items'         => __( 'All Categories', 'wedo' ),
        'parent_item'       => __( 'Parent Category', 'wedo' ),
        'edit_item'         => __( 'Edit Category', 'wedo' ),
        'update_item'       => __( 'Update Category', 'wedo' ),
        'add_new_item'      => __( 'Add New Category', 'wedo' ),
        'new_item_name'     => __( 'New Category Name', 'wedo' ),
        'menu_name'         => __( 'Categories', 'wedo' ),
    ];

    $args = [
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'resource-category'],
    ];

    register_taxonomy( 'resource_category', ['resource_tools'], $args );
}
add_action( 'init', 'wedo_register_resource_categories' );

/**
 * Add Meta Box for External Link
 */
function wedo_add_resource_meta_box() {
    add_meta_box(
        'wedo_resource_details',
        __( 'Resource Details', 'wedo' ),
        'wedo_render_resource_meta_box',
        'resource_tools',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wedo_add_resource_meta_box' );

/**
 * Render Meta Box HTML
 */
function wedo_render_resource_meta_box( $post ) {
    $external_link = get_post_meta( $post->ID, '_wedo_resource_link', true );
    ?>
    <div class="field-group" style="margin-top:10px;">
        <label for="wedo_resource_link" style="font-weight:600; display:block; margin-bottom:4px;">
            <?php _e( 'External Link (optional)', 'wedo' ); ?>
        </label>
        <input
            type="url"
            name="wedo_resource_link"
            id="wedo_resource_link"
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
function wedo_save_resource_meta( $post_id ) {
    if ( array_key_exists( 'wedo_resource_link', $_POST ) ) {
        update_post_meta( $post_id, '_wedo_resource_link', sanitize_text_field( $_POST['wedo_resource_link'] ) );
    }
}
add_action( 'save_post_resource_tools', 'wedo_save_resource_meta' );

/**
 * AJAX handler for filtering resources.
 */
/**
 * AJAX handler for filtering resources and reading lists (Unified Handler).
 * This function handles the query based on parameters sent by JavaScript.
 */
function unified_filter_ajax_handler() {

    // Use the post_type and item_part passed from the JS data attributes
    $post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : 'reading_list';
    $taxonomy  = isset($_GET['taxonomy'])  ? sanitize_text_field($_GET['taxonomy'])  : 'reading_list_category';
    $item_part = isset($_GET['item_part']) ? sanitize_text_field($_GET['item_part']) : 'list-tool-item';
    $term      = isset($_GET['term'])      ? sanitize_text_field($_GET['term'])      : 'all';

    $args = [
        'post_type'      => $post_type, // ✅ Uses the correct CPT from JS
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'suppress_filters' => true, // Prevents conflicts with other WP hooks
    ];

    if ( $term !== 'all' ) {
        $args['tax_query'] = [[
            'taxonomy' => $taxonomy, // ✅ Uses the correct Taxonomy from JS
            'field'    => 'slug',
            'terms'    => $term,
        ]];
    }

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) :
        while ( $query->have_posts() ) : $query->the_post();
            // ✅ Uses the correct template part from JS (e.g., 'grid-reading-item')
            get_template_part( 'template-parts/content', $item_part );
        endwhile;
    else :
        echo '<p class="text-gray-500">No items found in this category.</p>';
    endif;

    wp_reset_postdata();
    wp_die();
}

// Ensure the action hook is 'filter_resources' as used by wedo-custom.js
add_action( 'wp_ajax_filter_resources', 'unified_filter_ajax_handler' );
add_action( 'wp_ajax_nopriv_filter_resources', 'unified_filter_ajax_handler' );
