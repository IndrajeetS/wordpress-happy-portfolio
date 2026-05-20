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

$post_id = get_the_ID();
$taxonomy = 'reading_list_category';
$link = get_post_meta($post_id, '_wedo_reading_link', true);
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

if ($link):
    ?>
    <a class="group rounded-xl flex flex-row justify-start items-center p-4 relative transition-all duration-300 ease-in border border-border bg-readingBg hover:bg-hoverBg"
        href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title_attr); ?>" target="_blank"
        rel="noopener noreferrer">

        <?php if ($thumbnail_id):
            echo wp_get_attachment_image(
                $thumbnail_id,
                'thumbnail',
                false,
                [
                    'class' => 'w-6 h-6 object-cover rounded-md flex-shrink-0',
                    'loading' => 'lazy',
                    'alt' => $title_attr,
                ]
            );
        endif; ?>

        <div class="flex flex-col ml-3 flex-1">
            <h3 class="text-lg! font-medium text-gray12 w-full leading-tight capitalize">
                <?php the_title(); ?>
            </h3>
            <p class="text-gray11 text-xs! leading-relaxed line-clamp-3 font-[390]">
                <?php echo $category_name; ?>
            </p>
        </div>
    </a>
<?php endif; ?>