<?php
/**
 * Template Part for displaying a single Reading List item.
 *
 * This file is intended to be used inside the WordPress Loop, likely for a custom post type.
 *
 * @package HappyPortfolio
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// -----------------------------------------------------
// 1. DATA RETRIEVAL
// -----------------------------------------------------

$post_id    = get_the_ID();
$taxonomy   = 'reading_list_category';
$link       = get_post_meta($post_id, '_wedo_reading_link', true);
$title_attr = the_title_attribute(['echo' => false]);

// Get the featured image ID for responsive output
$thumbnail_id = get_post_thumbnail_id($post_id);

// Get the first term/category name
$category_name = '';
$terms = get_the_terms($post_id, $taxonomy);
if ($terms && !is_wp_error($terms)) {
    $category_name = esc_html($terms[0]->name);
}

// -----------------------------------------------------
// 2. HTML OUTPUT
// -----------------------------------------------------

if ($link) :
    ?>
    <a class="group rounded-lg flex flex-row justify-between items-start p-3 bg-white border border-[#e6e6e6] relative row-gap-4 transition-all duration-75 ease-in overflow-hidden hover:bg-hoverBg"
       href="<?php echo esc_url($link); ?>"
       title="<?php echo esc_attr($title_attr); ?>"
       target="_blank"
       rel="noopener noreferrer">

        <?php if ($thumbnail_id) :
            // 💡 PERFORMANCE IMPROVEMENT: Using wp_get_attachment_image()
            // Using 'thumbnail' size is appropriate for a small 24x24px element.
            echo wp_get_attachment_image(
                $thumbnail_id,
                'thumbnail', // Standard small size (e.g., 150x150)
                false,
                [
                    'class'   => 'w-6 h-6 object-cover rounded-md mb-0', // Custom Tailwind classes for sizing
                    'loading' => 'lazy',
                    'alt'     => $title_attr,
                ]
            );
        // Fallback for cases where no featured image is set, using a simple SVG or Font Icon (optional)
        // else : ?>
            <?php endif; ?>

        <div class="flex-3 mb-0! ml-3">
            <h3 class="text-sm! mb-1! font-medium!">
                <?php the_title(); ?>
            </h3>
            <p class="text-gray11! text-xs! mb-0">
                <?php echo $category_name; // Outputs the retrieved category name ?>
                <span class="opacity-0 text-xs! group-hover:opacity-100 transition-opacity duration-300">↗</span>
            </p>
        </div>
    </a>
<?php endif; ?>
