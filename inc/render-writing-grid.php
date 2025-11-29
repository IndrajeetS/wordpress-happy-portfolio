<?php
function render_recent_writing_grid($args = array()) {
    // Define default arguments
    $defaults = array(
        'posts_per_page' => 4,    // Default for home page
        'show_header'    => true, // Default to show the header
        'title'          => 'Recent writing',
        'grid_col'       => 'sm:grid-cols-1 lg:grid-cols-3 md:grid-cols-2 xl:grid-cols-4',
    );

    // Merge provided arguments with defaults
    $args = wp_parse_args($args, $defaults);

    // Extract variables
    $posts_per_page = (int) $args['posts_per_page'];
    $show_header    = (bool) $args['show_header'];
    $title          = sanitize_text_field($args['title']);
    $grid_col       = sanitize_text_field($args['grid_col']);

    ob_start();

    // The header section (conditionally displayed)
    if ($show_header) :
        $posts_page_permalink = get_permalink(get_option('page_for_posts'));
    ?>
        <div class="mb-3.5! flex justify-between items-center!">
            <h2 class="text-lg! font-medium m-0!"><?php echo esc_html($title); ?></h2>
            <?php if ($posts_page_permalink) : ?>
                <a class="text-xs text-gray11! duration-75 ease-in rounded--lg -mr-[5px] rounded-lg p-[5.5px_9px]"
                   href="/blog/">View All</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- The dynamic grid -->
    <div id="home-reading-grid" class="grid gap-4 <?php echo esc_attr($grid_col); ?> w-full mb-14!">
        <?php
        $posts_query = new WP_Query([
            'post_type'      => 'post',
            'posts_per_page' => $posts_per_page,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        if ($posts_query->have_posts()) :
            while ($posts_query->have_posts()) : $posts_query->the_post();
                get_template_part('template-parts/content', 'post-item');
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p class="text-gray-500">No posts found.</p>';
        endif;
        ?>
    </div>

    <?php
    return ob_get_clean();
}
?>
