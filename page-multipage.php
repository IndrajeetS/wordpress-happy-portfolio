<?php
// page-multipage.php -- FULLY UPDATED FOR SPA MODE (OPTION A)

if (!defined('ABSPATH'))
  exit;

// Required arguments
// $args come from the get_template_part call in page-reading.php/page-tools.php
$taxonomy = $args['taxonomy'] ?? ($_GET['taxonomy'] ?? 'reading_list_category');
$post_type = $args['post_type'] ?? ($_GET['post_type'] ?? 'reading_list');

// Optional template part for each item
$item_part = $args['item_part'] ?? ($_GET['item_part'] ?? 'list-tool-item');

$taxonomy = sanitize_text_field($taxonomy);
$post_type = sanitize_text_field($post_type);
$item_part = sanitize_text_field($item_part);

// Get category terms for tabs
$terms = get_terms([
  'taxonomy' => $taxonomy,
  'hide_empty' => true,
]);
?>

<?php
$favourite_slugs = ['favourite', 'favorites', 'my-favorites'];
$active_favourite_slug = null;

if (!is_wp_error($terms) && !empty($terms)) {
  foreach ($terms as $term) {
    if (in_array($term->slug, $favourite_slugs, true)) {
      $active_favourite_slug = $term->slug;
      break; // ✅ pick first match
    }
  }
}
?>

<div id="mutipage-content" class="py-8 max-w-xl w-full mx-auto" data-taxonomy="<?php echo esc_attr($taxonomy); ?>"
  data-posttype="<?php echo esc_attr($post_type); ?>" data-itempart="<?php echo esc_attr($item_part); ?>">

  <h1 class="mb-4 font-medium!"><?php the_title(); ?></h1>

  <div class="text-gray10 mb-12">
    <?php the_content(); ?>
  </div>

  <div class="relative border-b border-gray-200 mb-4">

    <div class="flex space-x-6 overflow-x-auto pr-16 pb-0">

      <button
        class="cursor-pointer text-gray10 hover:text-gray12 wedo-tab-btn active border-b-3 border-black text-sm py-2.5 whitespace-nowrap"
        data-term="all">
        Recently Added
      </button>

      <?php
      if (!is_wp_error($terms) && !empty($terms)):
        $skip_terms = $favourite_slugs;

        foreach ($terms as $term):
          if (in_array($term->slug, $skip_terms))
            continue;
          ?>
          <button
            class="cursor-pointer text-gray10 hover:text-gray12 wedo-tab-btn border-b-3 border-transparent text-sm py-2.5 whitespace-nowrap"
            data-term="<?php echo esc_attr($term->slug); ?>">
            <?php echo esc_html($term->name); ?>
          </button>
          <?php
        endforeach;
      endif;
      ?>
    </div>

    <?php if ($active_favourite_slug): ?>
      <button class="absolute right-0 top-1/2 -translate-y-1/2
       bg-(--color-nav-badgeBg)
       pl-2 pr-2 py-1.5
       cursor-pointer
       text-(--color-nav-badgeText)
       hover:text-(--color-menuLabel)
       rounded-sm
       wedo-tab-btn group" data-term="<?php echo esc_attr($active_favourite_slug); ?>" aria-label="Favourite">

        <span class="iconify text-sm text-(--color-nav-badgeText)!" data-icon="mynaui:filter" data-height="18"
          data-width="18"></span>

        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2
             px-2 py-1 text-xs whitespace-nowrap
             bg-gray-900 text-white rounded
             opacity-0 pointer-events-none
             group-hover:opacity-100
             transition-opacity duration-200">
          <?php echo esc_html(ucwords(str_replace('-', ' ', $active_favourite_slug))); ?>
        </span>
      </button>
    <?php endif; ?>
  </div>

  <div id="tools-list" class="w-full flex flex-col <?php echo ($post_type == 'reading_list') ? "gap-2" : "gap-0"; ?>"
    data-tab-container>
  </div>
</div>