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
$post_id = get_the_ID();
$link = get_permalink();
$post_title_attribute = the_title_attribute(['echo' => false]);
$post_excerpt = has_excerpt()
    ? get_the_excerpt()
    : wp_trim_words(
        wp_strip_all_tags(get_the_content()),
        20,
        '...'
    );

// Get the featured image ID for use with wp_get_attachment_image()
$thumbnail_id = get_post_thumbnail_id($post_id);

// Note: Category code is kept commented out as it was in the original,
// but the variable definition is clean for potential future use.
$categories = get_the_category($post_id);
$category_name = ($categories && !is_wp_error($categories)) ? esc_html($categories[0]->name) : '';


// -----------------------------------------------------
// 2. HTML OUTPUT
// -----------------------------------------------------

if ($link):
    ?>
    <a class="group rounded-xl flex flex-col p-4 transition-all duration-300 ease-out border border-border bg-readingBg hover:bg-gray2"
        href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($post_title_attribute); ?>">

        <?php if ($thumbnail_id): ?>
            <div
                class="relative aspect-video w-full flex items-center justify-center bg-grayBg dark:bg-gray3 rounded-lg overflow-hidden border border-border/40 mb-4">
                <?php
                echo wp_get_attachment_image(
                    $thumbnail_id,
                    'medium',
                    false,
                    [
                        'class' => 'max-w-[98%] max-h-[98%] object-contain p-4 group-hover:scale-105 transition-transform duration-500',
                        'loading' => 'lazy',
                        'alt' => $post_title_attribute,
                    ]
                );
                ?>
            </div>
        <?php endif; ?>

        <div class="flex flex-col grow">
            <div class="flex justify-between mb-3">
                <?php if (!empty($categories) && !is_wp_error($categories)): ?>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach (array_slice($categories, 0, 2) as $category): ?>
                            <span
                                class="text-[9px] font-bold uppercase text-gray8 tracking-[0.15em] group-hover:text-gray12 transition-all duration-300">
                                <?php echo esc_html($category->name); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <span
                    class="text-[9px] font-bold text-gray8 uppercase tracking-[0.15em] group-hover:text-gray12 transition-all duration-300">
                    <?php echo get_the_date('M d, Y'); ?>
                </span>
            </div>

            <h3 class="text-lg! font-medium text-gray12 w-full leading-tight mb-2">
                <?php the_title(); ?>
            </h3>

            <?php if ($post_excerpt): ?>
                <p class="text-gray11 text-xs! leading-relaxed font-[390]">
                    <?php echo esc_html($post_excerpt); ?>
                </p>
            <?php endif; ?>
        </div>
    </a>
<?php endif; ?>