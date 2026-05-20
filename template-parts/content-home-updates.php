<?php
// Ensure this runs only if it's called within the WordPress environment
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}
?>

<?php
// The $args variable is automatically available in the template part.

// 1. Extract variables from the $args array with defaults.
$header_a = $args['header_a'] ?? "text-xs text-gray11! duration-75 ease-in rounded-lg p-[5.5px_9px] hover:text-primary! tracking-wide";
$update_section = $args['update_section'] ?? "w-full mb-14 grid gap-4 sm:grid-cols-1 lg:grid-cols-3 md:grid-cols-2 xl:grid-cols-4";
$updates_item = $args['updates_item'] ?? "rounded-lg flex flex-col justify-between items-start p-4 bg-grayBg border border-none relative transition-all duration-75 ease-in overflow-hidden hover:bg-gray4";
$item_content = $args['item_content'] ?? "flex-3 mb-0 mt-2.5";
$item_title = $args['item_title'] ?? "flex-3 mb-0 mt-2.5";
$item_date = $args['item_date'] ?? "mt-0";

// Check if we should render as a slider (grid view by default on home page)
$is_grid = empty($args['update_section']) || (strpos($args['update_section'], 'grid') !== false);

// Run the query early to check post count and determine layouts
$updates = new WP_Query([
  'post_type' => 'personal_update',
  'posts_per_page' => 15,
  'orderby' => 'date',
  'order' => 'DESC',
]);

$post_count = $updates->post_count;
$is_slider = $is_grid && ($post_count > 0);
$show_nav = $is_slider && ($post_count > 4);
?>

<div id="personal-updates" class="mb-3.5 flex justify-between items-center">
  <h2 class="text-xl! font-medium m-0!">Personal Updates</h2>
  <div class="flex items-center space-x-2">
    <?php if ($show_nav): ?>
      <div class="flex items-center gap-1.5 ml-2" id="updates-nav-buttons">
        <button id="updates-prev"
          class="w-6 h-6 flex items-center justify-center rounded-md bg-gray2 dark:bg-gray3 hover:bg-gray4 dark:hover:bg-gray4 text-gray12 transition-all cursor-pointer border border-gray4 dark:border-gray5"
          aria-label="Previous Updates">
          <span class="iconify" data-icon="lucide:chevron-left" data-width="14" data-height="14"></span>
        </button>
        <button id="updates-next"
          class="w-6 h-6 flex items-center justify-center rounded-md bg-gray2 dark:bg-gray3 hover:bg-gray4 dark:hover:bg-gray4 text-gray12 transition-all cursor-pointer border border-gray4 dark:border-gray5"
          aria-label="Next Updates">
          <span class="iconify" data-icon="lucide:chevron-right" data-width="14" data-height="14"></span>
        </button>
      </div>
    <?php endif; ?>
    <a class="<?php echo esc_attr($header_a); ?>" href="/about#personal-updates">
      View All
    </a>
  </div>
</div>

<?php if ($is_slider): ?>
  <!-- Slider Layout (Swiper) -->
  <div class="updates-swiper-container swiper w-full mb-14 relative overflow-hidden">
    <div class="swiper-wrapper">
      <?php
      if ($updates->have_posts()):
        while ($updates->have_posts()):
          $updates->the_post();

          $modal_args = array(
            'item_classes' => $updates_item,
            'item_content' => esc_attr($item_content),
            'item_title' => esc_attr($item_title),
            'item_date' => esc_attr($item_date),
            'is_slider' => true,
          );
          ?>
          <div class="swiper-slide h-auto flex">
            <?php get_template_part('template-parts/content', 'update-item', $modal_args); ?>
          </div>
          <?php
        endwhile;
        wp_reset_postdata();
      else:
        echo '<p class="text-gray-500">No personal updates yet.</p>';
      endif;
      ?>
    </div>
  </div>
<?php else: ?>
  <!-- List Layout (Untouched) -->
  <div id="home-updates-grid" class="<?php echo esc_attr($update_section); ?>">
    <?php
    if ($updates->have_posts()):
      while ($updates->have_posts()):
        $updates->the_post();

        $modal_args = array(
          'item_classes' => $updates_item,
          'item_content' => esc_attr($item_content),
          'item_title' => esc_attr($item_title),
          'item_date' => esc_attr($item_date),
        );

        get_template_part('template-parts/content', 'update-item', $modal_args);

      endwhile;
      wp_reset_postdata();
    else:
      echo '<p class="text-gray-500">No personal updates yet.</p>';
    endif;
    ?>
  </div>
<?php endif; ?>