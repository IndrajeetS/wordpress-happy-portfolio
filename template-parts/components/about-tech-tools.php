<?php
/**
 * ABOUT PAGE — TECH TOOLS LIST
 *
 * Category (left) → Tools inside (right)
 *
 * @package HappyPortfolio
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get all Tech Tool categories
$categories = get_terms([
    'taxonomy'   => 'techtool_category',
    'hide_empty' => false,
]);

if (!empty($categories) && !is_wp_error($categories)) :
?>
<div id="about-tech-tools" class="space-y-4 mt-8">

    <?php foreach ($categories as $category) : ?>

        <div class="flex items-start gap-3">

            <div class="min-w-[120px] text-gray11 text-base font-medium">
                <?php echo esc_html($category->name); ?>:
            </div>

            <div class="flex-1 flex flex-wrap gap-2">

                <?php
                // Query for tools in the current category
                $tools = get_posts([
                    'post_type'      => 'techtools',
                    'posts_per_page' => -1,
                    'tax_query'      => [
                        [
                            'taxonomy' => 'techtool_category',
                            'field'    => 'slug',
                            'terms'    => $category->slug,
                        ]
                    ]
                ]);

                if (!empty($tools) && !is_wp_error($tools)) :
                    foreach ($tools as $tool) :

                        // 1. Get the attachment ID for the tool icon
                        $thumbnail_id = get_post_thumbnail_id($tool->ID);

                        // 2. Get the tool title for the alt attribute
                        $tool_title = get_the_title($tool->ID);
                ?>

                <?php if ($thumbnail_id) : ?>
                    <?php
                    // 💡 PERFORMANCE IMPROVEMENT: Using wp_get_attachment_image()
                    // The 'thumbnail' size is generally suitable for small icons like 28x28px.
                    echo wp_get_attachment_image(
                        $thumbnail_id,
                        'thumbnail', // Use a small, generated size
                        false,
                        [
                            'class'   => 'w-7 h-7 object-contain',
                            'loading' => 'lazy',
                            'alt'     => esc_attr($tool_title) . ' icon',
                            'title'   => esc_attr($tool_title), // Add title for hover tooltip
                        ]
                    );
                    ?>
                <?php endif; ?>

                <?php
                    endforeach;
                endif;
                ?>

            </div>

        </div>

    <?php endforeach; ?>

</div>
<?php endif; ?>
