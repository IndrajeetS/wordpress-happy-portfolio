<?php
/**
 * Template Part for displaying a single standard post card in a list.
 *
 * This file is intended to be used inside the WordPress Loop.
 *
 * @package HappyPortfolio
 */

// Exit if accessed directly (best practice for any included PHP file)
if (!defined('ABSPATH')) {
    exit;
}

// -----------------------------------------------------
// 1. DATA RETRIEVAL
// -----------------------------------------------------

// Get essential post details directly within the loop
$post_id   = get_the_ID();
$link      = get_permalink();
$post_title_attribute = the_title_attribute(['echo' => false]);

// Get the featured image ID for use with wp_get_attachment_image()
$thumbnail_id = get_post_thumbnail_id($post_id);

// Note: Category code is kept commented out as it was in the original,
// but the variable definition is clean for potential future use.
$categories = get_the_category($post_id);
$category_name = ($categories && !is_wp_error($categories)) ? esc_html($categories[0]->name) : '';


// -----------------------------------------------------
// 2. HTML OUTPUT
// -----------------------------------------------------

if ($link) :
    ?>
    <a class="group rounded-lg flex flex-col justify-between items-start p-4 bg-white border border-[#e6e6e6] relative row-gap-4 transition-all duration-75 ease-in overflow-hidden hover:bg-hoverBg" href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($post_title_attribute); ?>">

        <?php if ($thumbnail_id) :
            // 💡 PERFORMANCE IMPROVEMENT: Using wp_get_attachment_image()
            // This outputs a complete <img> tag with loading="lazy", srcset, and sizes
            // attributes for responsive image delivery.
            echo wp_get_attachment_image(
                $thumbnail_id,
                'medium', // Use 'medium' size (or a custom size tailored for your grid)
                false,
                [
                    'class'   => 'w-full h-38 object-cover rounded-lg',
                    'loading' => 'lazy', // Ensure lazy loading is enabled
                    'alt'     => $post_title_attribute, // Re-use the title for better accessibility
                ]
            );
        endif; ?>

        <div class="flex-3 mb-0! mt-4">
            <h3 class="
            inline-flex! font-[390] tracking-[.1px] leading-[1.1] text-[15px] text-gray12 transition duration-250 ease-in-out text-lg! m-0!
            ">
                <?php the_title(); ?>
                <span class="opacity-0 text-sm! group-hover:opacity-100 transition-opacity duration-300">↗</span>
            </h3>

             <p class="text-gray11! text-xs! mt-1 text-[13.8px] font-[390] overflow-hidden text-ellipsis line-clamp-2">
                <?php
                    // Display a trimmed, controlled excerpt.
                    $content = get_the_content();
                    $content = wp_strip_all_tags($content);
                    echo wp_trim_words($content, 15, '..');
                ?>
            </p>
        </div>
    </a>
<?php endif; ?>
