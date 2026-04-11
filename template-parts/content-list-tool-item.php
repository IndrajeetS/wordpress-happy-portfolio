<?php
/**
 * Template Part for displaying a single Resource/Tool List Item.
 *
 * This file is intended to be used inside the WordPress Loop.
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

$post_id = get_the_ID();
$link = get_post_meta($post_id, '_wedo_external_link', true);
$description = get_post_meta($post_id, '_wedo_techtool_description', true);
$title_attr = the_title_attribute(['echo' => false]);

// Get the featured image ID for responsive output
$thumbnail_id = get_post_thumbnail_id($post_id);

// -----------------------------------------------------
// 2. HTML OUTPUT
// -----------------------------------------------------

if ($link):
    ?>
    <a class="group rounded-lg flex flex-row justify-start items-center px-2 py-0 relative row-gap-4 transition-all duration-75 ease-in overflow-hidden mb-0 w-full bg-readingBg dark:bg-readingBg hover:bg-hoverBg"
        href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title_attr); ?>" target="_blank"
        rel="noopener noreferrer">

        <?php if ($thumbnail_id):
            // 💡 PERFORMANCE IMPROVEMENT: Using wp_get_attachment_image()
            // Using 'thumbnail' size is appropriate for a small 40x40px element.
            echo wp_get_attachment_image(
                $thumbnail_id,
                'thumbnail', // Standard small size (e.g., 150x150)
                false,
                [
                    'class' => 'w-10 h-10 object-cover rounded-md', // Custom Tailwind classes for sizing
                    'loading' => 'lazy',
                    'alt' => $title_attr,
                ]
            );
        endif; ?>

        <div class="py-5 mb-0! ml-8 flex-1 border-0 border-b border-b-gray4">
            <h3 class="text-sm! mb-1! font-semibold">
                <?php the_title(); ?>
                <span class="opacity-0 text-xs! group-hover:opacity-100 transition-opacity duration-300">↗</span>
            </h3>
            <p class="text-xs! text-gray11! mb-1.5">
                <?php
                // Use custom description if available, otherwise fallback to trim(get_the_excerpt())
                echo !empty($description) ? esc_html($description) : trim(get_the_excerpt());
                ?>
            </p>
        </div>
    </a>
<?php endif; ?>