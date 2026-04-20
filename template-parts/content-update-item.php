<?php
if (!defined('ABSPATH')) {
    exit;
}

// -----------------------------------------------------
// 1. DATA
// -----------------------------------------------------

$post_id = get_the_ID();
$post_type = get_post_type($post_id);
$is_personal_update = $post_type === 'personal_update';
$title_attr = the_title_attribute(['echo' => false]);

$link = get_permalink($post_id);
$link_target = '';
$link_rel = '';
$link_icon = '↗';

// External link logic
if ($is_personal_update) {
    $external_link = get_post_meta($post_id, '_external_link', true);

    if (!empty($external_link) && filter_var($external_link, FILTER_VALIDATE_URL)) {
        $link = esc_url($external_link);
        $link_target = '_blank';
        $link_rel = 'noopener noreferrer';
        $link_icon = '↗';
    }
}

// -----------------------------------------------------
// 2. IMAGE VALIDATION (STRICT)
// -----------------------------------------------------

$thumbnail_id = get_post_thumbnail_id($post_id);

$is_image_valid = (
    $thumbnail_id &&
    wp_attachment_is_image($thumbnail_id) &&
    wp_get_attachment_url($thumbnail_id)
);

// -----------------------------------------------------
// 3. CLASSES (UNCHANGED STYLE)
// -----------------------------------------------------

$item_classes = "group isolate relative z-0 hover:z-10 rounded-lg flex flex-col justify-between items-start p-4 bg-grayBg transition-all duration-75 ease-in overflow-hidden hover:bg-gray4";

$item_content = $args['item_content'] ?? "flex-3 mb-0!";
$item_title = $args['item_title'] ?? "block";
$item_date = $args['item_date'] ?? "";

// -----------------------------------------------------
// 4. OUTPUT
// -----------------------------------------------------

if ($link):
?>

<div class="<?php echo esc_attr($item_classes); ?> flex flex-row gap-2 items-start">

    <?php if ($is_image_valid): ?>
        <div class="w-10 h-10 p-1 flex items-center justify-center rounded-md overflow-hidden 
        bg-white dark:bg-gray3 border border-gray4 dark:border-gray5 flex-shrink-0">
            <?php echo wp_get_attachment_image(
                $thumbnail_id,
                'full',
                false,
                [
                    'class' => 'max-w-full max-h-full object-contain',
                    'loading' => 'lazy',
                    'alt' => esc_attr($title_attr),
                ]
            ); ?>
        </div>
    <?php endif; ?>

    <div class="flex-1 flex flex-col justify-between">

        <div class="flex flex-row justify-between">
            <h3 class="text-lg! font-medium text-gray12 group-hover:text-accent">
                <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title_attr); ?>"
                   <?php if ($link_target): ?> target="<?php echo esc_attr($link_target); ?>"
                   rel="<?php echo esc_attr($link_rel); ?>" <?php endif; ?>>
                    
                    <?php the_title(); ?>

                    <!-- ✅ FIXED ICON -->
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 inline-block align-middle ml-1 text-[0.9em] leading-none">
                        <?php echo esc_html($link_icon); ?>
                    </span>

                </a>
            </h3>

            <p class="<?php echo esc_attr($item_date); ?> text-xs! mt-2">
                <?php echo esc_html(get_the_date('M Y')); ?>
            </p>
        </div>

        <div class="text-gray11! text-sm!">
            <?php the_content(); ?>
        </div>

    </div>
</div>

<?php endif; ?>