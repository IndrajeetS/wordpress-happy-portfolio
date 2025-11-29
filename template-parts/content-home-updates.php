<?php
// Ensure this runs only if it's called within the WordPress environment
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<?php
// The $args variable is automatically available in the template part.

// 1. Extract variables from the $args array with defaults.
$header_a = $args['header_a'] ?? "text-xs text-gray11! duration-75 ease-in rounded-lg p-[5.5px_9px]";
$update_section = $args['update_section'] ?? "w-full mb-14! grid gap-4 sm:grid-cols-1 lg:grid-cols-3 md:grid-cols-2 xl:grid-cols-4";
$updates_item = $args['updates_item'] ?? "group rounded-lg flex flex-col justify-between items-start p-4 bg-grayBg border border-none relative transition-all duration-75 ease-in overflow-hidden hover:bg-gray4";
$item_content = $args['item_content'] ?? "flex-3 mb-0! mt-2.5";
$item_title = $args['item_title'] ?? "flex-3 mb-0! mt-2.5";
$item_date = $args['item_date'] ?? "mt-2!";
?>

<div id="personal-updates" class="mb-3.5! flex justify-between items-center!">
  <h2 class="text-lg! font-medium mb-2! text-gray12!">Personal Updates</h2>
  <a class="<?php echo esc_attr($header_a); ?>" href="/about#personal-updates">
    View All
  </a>
</div>

<div id="home-updates-grid" class="<?php echo esc_attr($update_section); ?>">
  <?php
  $updates = new WP_Query([
    'post_type'      => 'personal_update',
    'posts_per_page' => 15,
    'orderby'        => 'date',
    'order'          => 'DESC',
  ]);

  if ($updates->have_posts()) :
    while ($updates->have_posts()) :
      $updates->the_post();

      // ✅ Correct array syntax — no echo inside array
      $modal_args = array(
          'item_classes' => esc_attr($updates_item),
          'item_content' => esc_attr($item_content),
          'item_title' => esc_attr($item_title),
          'item_date' => esc_attr($item_date),
      );

      // ✅ Pass $modal_args to the template part
      get_template_part('template-parts/content', 'update-item', $modal_args);

    endwhile;
    wp_reset_postdata();
  else :
    echo '<p class="text-gray-500">No personal updates yet.</p>';
  endif;
  ?>
</div>
