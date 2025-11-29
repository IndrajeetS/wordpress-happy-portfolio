<?php
// PHP AJAX Handler (functions.php or similar)

// Ensure the action hook matches the function name!
// add_action('wp_ajax_filter_resources', 'filter_resources_handler');
// add_action('wp_ajax_nopriv_filter_resources', 'filter_resources_handler');


function filter_resources_handler() {

    // 1. Sanitize and retrieve POST TYPE directly from the query string
    // We expect the JS to send 'reading_list' or 'resource_tools'
    $post_type = isset($_GET['post_type'])
        ? sanitize_text_field($_GET['post_type'])
        // Defaulting to an array of all possible types is safer than 'post'
        : ['reading_list', 'resource_tools'];

    // 2. Sanitize and retrieve other necessary variables
    $term      = isset($_GET['term'])      ? sanitize_text_field($_GET['term'])      : 'all';
    $taxonomy  = isset($_GET['taxonomy'])  ? sanitize_text_field($_GET['taxonomy'])  : 'category';
    $item_part = isset($_GET['item_part']) ? sanitize_text_field($_GET['item_part']) : 'list-tool-item';

    // 3. Force post_type into a single value if it's currently an array (only for safety)
    // If we kept the default as an array, the query would run for both.
    if (is_array($post_type)) {
        // Since we are debugging the reading list, let's force the query to prioritize it if missing.
        $post_type = 'reading_list';
    }

    $args = [
        'post_type'      => $post_type,
        'posts_per_page' => -1,
        'orderby'        => 'date', // Add a sensible order
        'order'          => 'DESC',
        'suppress_filters' => true, // CRITICAL: Prevents other plugins from changing the query
    ];

    // 4. Term filter (Correct)
    if ($term && $term !== 'all' && $taxonomy !== 'category') {
        $args['tax_query'] = [
            [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $term,
            ]
        ];
    }

    // DEBUG: Log the final query type to the server logs
    error_log("Final AJAX Query: Post Type=$post_type, Term=$term");

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/content', $item_part);
        }
    } else {
        echo '<p class="text-gray-500">No items found.</p>';
    }

    wp_reset_postdata();
    wp_die();
}
?>
