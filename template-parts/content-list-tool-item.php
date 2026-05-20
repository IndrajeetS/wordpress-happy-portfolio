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

        <?php if ($thumbnail_id): ?>
            <div class="w-10 h-10 p-1 flex items-center justify-center rounded-md overflow-hidden 
                bg-white dark:bg-gray3 border border-gray4 dark:border-gray5">

                <?php
                echo wp_get_attachment_image(
                    $thumbnail_id,
                    'full',
                    false,
                    [
                        'class' => 'max-w-full max-h-full object-contain',
                        'loading' => 'lazy',
                        'alt' => $title_attr,
                    ]
                );
                ?>
            </div>
        <?php endif; ?>

        <div class="py-5 mb-0 ml-8 flex-1 border-0 border-b border-b-gray4">
            <h3 class="text-lg! font-medium text-gray12 w-full leading-tight mb-0.5">
                <?php the_title(); ?>
            </h3>
            <p class="text-gray11 text-xs! leading-relaxed font-[390]">
                <?php
                // Use custom description if available, otherwise fallback to trim(get_the_excerpt())
                echo !empty($description) ? esc_html($description) : trim(get_the_excerpt());
                ?>
            </p>
        </div>
    </a>
<?php endif; ?>