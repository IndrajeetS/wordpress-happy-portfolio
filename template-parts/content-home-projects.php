<?php
// Ensure this runs only if it's called within the WordPress environment
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

$projects = new WP_Query([
  'post_type' => 'projects',
  'posts_per_page' => 4, // Limit to 4 posts
  'orderby' => 'modified', // Order by 'modified' (updated) date
  'order' => 'DESC', // List in descending order (latest first)
]);

if ($projects->have_posts()):
?>

<div class="mb-3.5 flex justify-between items-center">
  <h2 class="text-xl! font-medium m-0!">Projects</h2>
  <a href="<?php echo esc_url(home_url('/projects/')); ?>"
    class="text-xs text-gray11! duration-75 ease-in rounded-lg p-[5.5px_9px] hover:text-primary! tracking-wide">View All</a>
</div>
<div id="home-projects-grid" class="grid gap-4 sm:grid-cols-1 lg:grid-cols-3 md:grid-cols-2 xl:grid-cols-4 w-full mb-14!">
  <?php
    while ($projects->have_posts()):
      $projects->the_post();
      get_template_part('template-parts/content', 'tool-item'); // Using the same item style
    endwhile;
    wp_reset_postdata();
  ?>
</div>

<?php endif; ?>
