<?php
/**
 * Template Part for displaying a single Reading List item with categories.
 *
 * @package HappyPortfolio
 */

if (!defined('ABSPATH')) {
    exit;
}

// -----------------------------------------------------
// 1. DATA SETUP
// -----------------------------------------------------

$post_id = get_the_ID();
$title_attr = the_title_attribute(['echo' => false]);
$link = get_post_meta($post_id, '_wedo_reading_link', true);
$taxonomy = 'reading_list_category';
$thumbnail_id = get_post_thumbnail_id($post_id);

// Color palette
$colors = [
    'bg-yellowTagBg',
    'bg-redTagBg',
    'bg-purpleTagBg',
    'bg-blueTagBg',
    'bg-greenTagBg'
];

$terms = get_the_terms($post_id, $taxonomy);
$is_favourite = false;
$display_terms = [];

// Separate "Favourite"
if (!empty($terms) && !is_wp_error($terms)) {
    foreach ($terms as $term) {
        if (trim($term->name) === 'Favourite') {
            $is_favourite = true;
        } else {
            $display_terms[] = $term;
        }
    }
}

// -----------------------------------------------------
// 2. HTML OUTPUT
// -----------------------------------------------------

if ($link):
    ?>
    <a class="group rounded-lg flex flex-row justify-start items-center p-3 relative row-gap-4 transition-all duration-75 ease-in border border-border bg-readingBg dark:bg-readingBg hover:bg-hoverBg"
        href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title_attr); ?>" target="_blank"
        rel="noopener noreferrer">

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

        <div class="flex flex-col ml-3 flex-1">
            <h3 class="text-sm! mb-0! font-medium!">
                <?php the_title(); ?>
                <span class="opacity-0 text-xs! group-hover:opacity-100 transition-opacity duration-300">↗</span>
            </h3>

            <div class="flex justify-between items-center">
                <p class="text-gray11! text-xs! mb-0! hidden md:flex">
                    <?php echo esc_url(substr($link, 0, 50)) . (strlen($link) > 50 ? '...' : ''); ?>
                </p>

                <div class="flex flex-wrap gap-2 text-gray11 text-xs mb-0">
                    <?php if (!empty($display_terms)): ?>
                        <?php foreach ($display_terms as $term): ?>
                            <?php
                            $colorClass = wedo_get_term_color($term->slug, $colors);
                            ?>
                            <span
                                class="text-(--color-gray12) text-xs px-2 py-0.5 rounded-md border border-(--color-border) bg-(--color-gray2) dark:bg-(--color-gray3)">
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
                    <span
                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 p-2 bg-gray12 text-white text-xs! whitespace-nowrap rounded opacity-0 group-hover/favorite:opacity-100 transition-opacity duration-300 pointer-events-none z-50">
                        One of my favorites. You set the filter to only show favorites.
                    </span>
                </div>
            </div>
        <?php endif; ?>

    </a>
<?php endif; ?>