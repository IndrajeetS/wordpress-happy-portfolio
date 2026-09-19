<?php
/**
 * Template Part: Tabbed Listing
 *
 * Renders a tabbed interface for browsing items organized by taxonomy categories.
 * Supports single-page application (SPA) mode with JavaScript-driven tab switching.
 *
 * This template part handles:
 * - Dynamic tab generation from taxonomy terms
 * - Special "Favourites" filter button with tooltip
 * - Responsive tab navigation with horizontal scrolling
 * - Page title and content display
 * - Flexible item rendering through template parts
 *
 * Expected arguments from get_template_part():
 * @param string $taxonomy      Taxonomy slug (default: 'reading_list_category')
 * @param string $post_type     Custom post type (default: 'reading_list')
 * @param string $item_part     Template part for rendering items (default: 'list-tool-item')
 *
 * Data attributes passed to JavaScript:
 * @data-taxonomy   Sanitized taxonomy slug
 * @data-posttype   Sanitized post type name
 * @data-itempart   Sanitized item template part name
 *
 * Dependencies:
 * - WordPress AJAX handlers (via app.js)
 * - Iconify library for SVG icons
 * - Tailwind CSS for styling
 *
 * @file
 * @package WordPress_Happy_Portfolio
 */

if (!defined('ABSPATH'))
  exit;

// Required arguments
// $args come from the get_template_part call in page-reading.php/page-tools.php
// Parse taxonomy, post_type, and item_part from template arguments or GET query parameters
$taxonomy = $args['taxonomy'] ?? ($_GET['taxonomy'] ?? 'reading_list_category');
$post_type = $args['post_type'] ?? ($_GET['post_type'] ?? 'reading_list');

// Optional template part for each item
$item_part = $args['item_part'] ?? ($_GET['item_part'] ?? 'list-tool-item');

// Sanitize all inputs to prevent injection attacks
$taxonomy = sanitize_text_field($taxonomy);
$post_type = sanitize_text_field($post_type);
$item_part = sanitize_text_field($item_part);

// Fetch all taxonomy terms that have associated items (hide_empty = true)
// These terms become tabs in the interface
$terms = get_terms([
  'taxonomy' => $taxonomy,
  'hide_empty' => true,
]);
?>

<?php
/**
 * Detect and store the favourite/favorites category slug
 * Searches through all terms to find one matching favourite naming conventions
 * Only one favourite tab is supported; uses the first match found
 */
$favourite_slugs = ['favourite', 'favorites', 'my-favorites'];
$active_favourite_slug = null;

if (!is_wp_error($terms) && !empty($terms)) {
  foreach ($terms as $term) {
    // Check if this term's slug matches any favourite naming convention
    if (in_array($term->slug, $favourite_slugs, true)) {
      $active_favourite_slug = $term->slug;
      break; // ✅ pick first match
    }
  }
}
?>

<?php
/**
 * Main tabbed listing container
 *
 * Data attributes are consumed by JavaScript (app.js) to dynamically fetch and render items
 * via AJAX when tabs are clicked
 *
 * @id       tabbed-listing-root   Root container for the entire component
 * @class    py-8                   Padding (top/bottom)
 * @class    max-w-xl               Maximum width constraint
 * @class    w-full                 Full width responsive
 * @class    mx-auto                Horizontal centering
 */
?>
<div id="tabbed-listing-root" class="py-8 max-w-xl w-full mx-auto" data-taxonomy="<?php echo esc_attr($taxonomy); ?>"
  data-posttype="<?php echo esc_attr($post_type); ?>" data-itempart="<?php echo esc_attr($item_part); ?>">

  <!-- Page title and description -->
  <h1 class="mb-4 font-medium!"><?php the_title(); ?></h1>

  <!-- Page introductory content -->
  <div class="text-gray10 mb-12 introductory_content">
    <?php the_content(); ?>
  </div>

  <!-- Tab Navigation Section -->
  <div class="relative border-b border-gray-200 mb-4">

    <!-- Tab buttons container with horizontal scroll for mobile -->
    <div class="flex space-x-6 overflow-x-auto pr-16 pb-0">

      <!-- "Recently Added" tab - shows all items, always first -->
      <button
        class="cursor-pointer text-gray10 hover:text-gray12 wedo-tab-btn active border-b-3 border-black text-sm py-2.5 whitespace-nowrap"
        data-term="all">
        Recently Added
      </button>

      <!-- Render individual category tabs from taxonomy terms -->
      <!-- Favourite/Favorites tabs are handled separately below -->
      <?php
      if (!is_wp_error($terms) && !empty($terms)):
        $skip_terms = $favourite_slugs;

        foreach ($terms as $term):
          // Skip favourite tabs - they're rendered separately as a filter button
          if (in_array($term->slug, $skip_terms))
            continue;
          ?>
          <!-- Category tab button -->
          <!-- data-term is used by app.js to fetch items for this category via AJAX -->
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
      <!-- Favourite filter button - positioned absolutely on the right -->
      <!-- Rendered separately to give it special styling and positioning -->
      <!-- Includes hover tooltip displaying the favourite category name -->
      <button class="absolute right-0 top-1/2 -translate-y-1/2
       bg-nav-badgeBg
       pl-2 pr-2 py-1.5
       cursor-pointer
       text-nav-badgeText
       hover:text-menuLabel
       rounded-sm
       wedo-tab-btn group" data-term="<?php echo esc_attr($active_favourite_slug); ?>" aria-label="Favourite">

        <!-- Filter icon from Iconify library -->
        <span class="iconify text-sm text-nav-badgeText!" data-icon="mynaui:filter" data-height="18"
          data-width="18"></span>

        <!-- Tooltip shown on hover -->
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

  <!-- Items container -->
  <!-- This div is populated dynamically by JavaScript via AJAX when tabs are clicked -->
  <!-- Items are rendered using the template part specified in $item_part -->
  <!-- Spacing differs based on post type: reading_list gets tighter spacing (gap-2), others have no gap -->
  <div id="tools-list" class="w-full flex flex-col <?php echo ($post_type == 'reading_list') ? "gap-2" : "gap-0"; ?>"
    data-tab-container="">
  </div>
</div>