<?php
// Ensure this runs only if it's called within the WordPress environment
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

// Get all reading list categories.
$terms = get_terms([
  'taxonomy' => 'reading_list_category',
  'hide_empty' => true,
]);

$reading_page = get_page_by_path('reading');
$reading_url = $reading_page ? get_permalink($reading_page) : home_url('/reading/');
?>

<div class="mb-3.5 flex justify-between items-center">
  <h2 class="text-xl! font-medium m-0!">Reading list</h2>
  <a class="text-xs text-gray11! duration-75 ease-in rounded-lg p-[5.5px_9px] hover:text-primary! tracking-wide"
    href="<?php echo esc_url($reading_url); ?>">View All</a>
</div>
<div id="home-reading-grid"
  class="grid gap-4 sm:grid-cols-1 lg:grid-cols-3 md:grid-cols-2 xl:grid-cols-4 w-full mb-14!">
  <?php
  $reading_lists = new WP_Query([
    'post_type' => 'reading_list',
    'posts_per_page' => 8,
    'orderby' => 'modified',
    'order' => 'DESC',
  ]);

  if ($reading_lists->have_posts()):
    while ($reading_lists->have_posts()):
      $reading_lists->the_post();
      get_template_part('template-parts/content', 'reading-item');
    endwhile;
    wp_reset_postdata();
  else:
    echo '<p class="text-gray-500">No reading lists added yet.</p>';
  endif;
  ?>
</div>