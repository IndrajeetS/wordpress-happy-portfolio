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
    'taxonomy' => 'techtool_category',
    'hide_empty' => false,
]);

if (!empty($categories) && !is_wp_error($categories)):
    ?>
    <div id="about-tech-tools" class="space-y-4 mt-8">

        <?php foreach ($categories as $category): ?>

            <div class="flex items-start gap-3">

                <div class="min-w-[120px] text-gray11 text-base font-medium">
                    <?php echo esc_html($category->name); ?>:
                </div>

                <div class="flex-1 flex flex-wrap gap-2">

                    <?php
                    // Query for tools in the current category
                    $tools = get_posts([
                        'post_type' => 'techtools',
                        'posts_per_page' => -1,
                        'tax_query' => [
                            [
                                'taxonomy' => 'techtool_category',
                                'field' => 'slug',
                                'terms' => $category->slug,
                            ]
                        ]
                    ]);

                    if (!empty($tools) && !is_wp_error($tools)):
                        foreach ($tools as $tool):

                            // 1. Get the attachment ID for the tool icon
                            $thumbnail_id = get_post_thumbnail_id($tool->ID);

                            // 2. Get the tool title for the alt attribute
                            $tool_title = get_the_title($tool->ID);
                            ?>

                            <?php if ($thumbnail_id): ?>
                                <?php
                                $tool_desc = get_post_meta($tool->ID, '_wedo_techtool_description', true);
                                $full_title = $tool_title . ($tool_desc ? ': ' . $tool_desc : '');
                                ?>

                                <div class="w-7 h-7 flex items-center justify-center rounded-sm overflow-hidden 
                bg-white dark:bg-gray3 border border-gray4 dark:border-gray5">

                                    <?php
                                    echo wp_get_attachment_image(
                                        $thumbnail_id,
                                        'full', // ✅ FIX: no cropping
                                        false,
                                        [
                                            'class' => 'max-w-full max-h-full object-contain',
                                            'loading' => 'lazy',
                                            'alt' => esc_attr($tool_title) . ' icon',
                                            'title' => esc_attr($full_title),
                                        ]
                                    );
                                    ?>

                                </div>
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