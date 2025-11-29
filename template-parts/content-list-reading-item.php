<?php
/**
 * Template Part for displaying a single Reading List item with categories.
 *
 * This file is intended to be used inside the WordPress Loop.
 *
 * @package HappyPortfolio
 */

if (!defined('ABSPATH')) {
    exit;
}

// -----------------------------------------------------
// 1. DATA RETRIEVAL AND SETUP (UNCHANGED)
// -----------------------------------------------------

$post_id    = get_the_ID();
$title_attr = the_title_attribute(['echo' => false]);
$link       = get_post_meta($post_id, '_wedo_reading_link', true);
$taxonomy   = 'reading_list_category';

// Get the featured image ID for responsive output
$thumbnail_id = get_post_thumbnail_id($post_id);

// Color setup for tags
$colors = [
    'bg-yellowTagBg',
    'bg-redTagBg',
    'bg-purpleTagBg',
    'bg-blueTagBg',
    'bg-greenTagBg'
];
$colorIndex = 0;

$terms = get_the_terms($post_id, $taxonomy);
$is_favourite = false;
$display_terms = []; // Array to store terms to be displayed

// Process terms: identify 'Favourite' and prepare others for display
if (!empty($terms) && !is_wp_error($terms)) {
    foreach ($terms as $term) {
        $term_name = trim($term->name);
        if ($term_name === 'Favourite') {
            $is_favourite = true;
        } else {
            $display_terms[] = $term;
        }
    }
}


// -----------------------------------------------------
// 2. HTML OUTPUT (UPDATED)
// -----------------------------------------------------

if ($link) :
?>
<a class="group rounded-lg flex flex-row justify-start items-center p-3 bg-white border border-[#e6e6e6] relative row-gap-4 transition-all duration-75 ease-in hover:bg-hoverBg"
   href="<?php echo esc_url($link); ?>"
   title="<?php echo esc_attr($title_attr); ?>"
   target="_blank"
   rel="noopener noreferrer">

    <?php if ($thumbnail_id) :
        // 💡 PERFORMANCE IMPROVEMENT: Using wp_get_attachment_image()
        echo wp_get_attachment_image(
            $thumbnail_id,
            'thumbnail', // Standard small size
            false,
            [
                'class'   => 'w-6 h-6 object-cover rounded-md mb-0',
                'loading' => 'lazy',
                'alt'     => $title_attr,
            ]
        );
    endif; ?>

    <div class="flex flex-col ml-3 flex-1">
        <h3 class="text-sm! mb-0! font-medium!">
            <?php the_title(); ?>
            <span class="opacity-0 text-xs! group-hover:opacity-100 transition-opacity duration-300">↗</span>
        </h3>
        <div class="flex justify-between items-center">
            <p class="text-gray11! text-xs! mb-0! hidden md:flex"><?php echo esc_url(substr($link, 0, 50)) . (strlen($link) > 50 ? '...' : ''); ?></p>

            <div class="flex flex-wrap gap-2 text-gray11 text-xs mb-0">
                <?php if (!empty($display_terms)) : ?>
                    <?php foreach ($display_terms as $term) : ?>
                        <?php
                            // Get color based on index
                            $colorClass = $colors[$colorIndex % count($colors)];
                            $colorIndex++;
                        ?>

                        <span class="text-gray11! text-xs! mb-0 px-2 py-0.5 rounded-md <?php echo $colorClass; ?>">
                            <?php echo esc_html($term->name); ?>
                        </span>

                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

     <?php if ($is_favourite): ?>
    <div class="inline-block absolute top-[-2.5px] right-3 z-10">
        <div class="group/favorite">

        <span class="iconify text-base text-gray10!" data-icon="material-symbols:bookmark-sharp"></span>

        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 p-2 bg-gray12 text-white text-xs! whitespace-nowrap rounded opacity-0 group-hover/favorite:opacity-100 transition-opacity duration-300 pointer-events-none z-50">
        One of my favorites. You set the filter to only show favorites.
        </span>
        </div>
    </div>
    <?php endif; ?>
</a>
<?php endif; ?>



