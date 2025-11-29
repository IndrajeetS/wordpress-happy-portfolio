<?php
// Ensure this runs only if it's called within the WordPress environment
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

  // Get all tool categories
  $terms = get_terms([
    'taxonomy'   => 'reading_list_category',
    'hide_empty' => true,
  ]);
  ?>

  <div class="mb-3.5! flex justify-between items-center!">
    <h2 class="text-lg! font-medium m-0!">Reading list</h2>
    <a class="text-xs
          text-gray11!
            duration-75 ease-in
            rounded--lg
            -mr-[5px]
            rounded-lg
            p-[5.5px_9px]" href="/reading/">View All</a>
  </div>
  <div id="home-reading-grid" class="grid gap-4 sm:grid-cols-1 lg:grid-cols-3 md:grid-cols-2 xl:grid-cols-4 w-full mb-14!">
    <!-- Default: All tools -->
    <?php
    $tools = new WP_Query([
      'post_type'      => 'reading_list',
      'posts_per_page' => 8, // Limit to 4 posts
      'orderby'        => 'modified', // Order by 'modified' (updated) date
      'order'          => 'DESC', // List in descending order (latest first)
    ]);

    if ($tools->have_posts()) :
      while ($tools->have_posts()) : $tools->the_post();
        get_template_part('template-parts/content', 'reading-item');
      endwhile;
      wp_reset_postdata();
    else :
      echo '<p class="text-gray-500">No tools added yet.</p>';
    endif;
    ?>
  </div>
