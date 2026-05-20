<?php
/**
 * Template Part for displaying a single Resource Tool card.
 *
 * This file is intended to be used inside the WordPress Loop, likely for the 'resource_tools' CPT.
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
    <a class="group rounded-lg flex flex-col justify-start items-start p-3 relative row-gap-4 transition-all duration-75 ease-in overflow-hidden border border-border bg-readingBg dark:bg-readingBg hover:bg-hoverBg"
        href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title_attr); ?>" target="_blank"
        rel="noopener noreferrer">

        <?php if ($thumbnail_id):
            // 💡 PERFORMANCE IMPROVEMENT: Using wp_get_attachment_image()
            // Using 'thumbnail' size is appropriate for a small 32x32px element.
            echo wp_get_attachment_image(
                $thumbnail_id,
                'thumbnail', // Standard small size (e.g., 150x150)
                false,
                [
                    'class' => 'w-8 h-8 object-cover rounded-md mb-2', // Custom Tailwind classes for sizing
                    'loading' => 'lazy',
                    'alt' => $title_attr,
                ]
            );
        endif; ?>

        <h3 class="text-lg! font-medium text-gray12 w-full leading-tight mb-0.5">
            <?php the_title(); ?>
        </h3>
        <p class="text-gray11 text-xs! leading-relaxed font-[390]">
            <?php
            // Use custom description if available, otherwise fallback to trim(get_the_excerpt())
            echo !empty($description) ? esc_html($description) : trim(get_the_excerpt());
            ?>
        </p>
    </a>
<?php endif; ?>