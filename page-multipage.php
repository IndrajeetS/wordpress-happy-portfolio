<?php
// page-multipage.php -- FULLY UPDATED FOR SPA MODE (OPTION A)

if (!defined('ABSPATH')) exit;

// Required arguments
// $args come from the get_template_part call in page-reading.php/page-tools.php
$taxonomy  = $args['taxonomy']  ?? ($_GET['taxonomy']  ?? 'reading_list_category');
$post_type = $args['post_type'] ?? ($_GET['post_type'] ?? 'reading_list');

// Optional template part for each item
$item_part = $args['item_part'] ?? ($_GET['item_part'] ?? 'list-tool-item');

$taxonomy  = sanitize_text_field($taxonomy);
$post_type = sanitize_text_field($post_type);
$item_part = sanitize_text_field($item_part);

// Get category terms for tabs
$terms = get_terms([
  'taxonomy'   => $taxonomy,
  'hide_empty' => true,
]);
?>

<div id="mutipage-content"
     class="py-8 max-w-xl w-full mx-auto"
     data-taxonomy="<?php echo esc_attr($taxonomy); ?>"
     data-posttype="<?php echo esc_attr($post_type); ?>"
     data-itempart="<?php echo esc_attr($item_part); ?>">

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
      if (!is_wp_error($terms) && !empty($terms)) :
        $skip_terms = ['favourite', 'favorites', 'my-favorites'];

        foreach ($terms as $term) :
          if (in_array($term->slug, $skip_terms)) continue;
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

    <button
      class="absolute right-0 top-1/2 -translate-y-1/2 bg-white pl-3 pr-2 py-2 cursor-pointer text-gray10 hover:text-gray12 border-l border-gray-200 wedo-tab-btn"
      data-term="favourite"
      aria-label="Favourite">

      <span class="iconify text-sm mr-1 text-gray10!" data-icon="mynaui:filter" data-height="18" data-width="18"></span>

      </svg>
    </button>
  </div>

  <div id="tools-list" class="w-full flex flex-col  <?php echo ($post_type == 'reading_list') ? "gap-2" : "gap-0"; ?>" data-tab-container>
  </div>
</div>
