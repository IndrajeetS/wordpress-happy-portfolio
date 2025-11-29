<?php
/**
 * Template Part for displaying a dynamic Post/Personal Update Card.
 *
 * Handles logic for external links and post type differentiation.
 * Intended to be used inside the WordPress Loop.
 *
 * @package HappyPortfolio
 */

if (!defined('ABSPATH')) {
    exit;
}

// -----------------------------------------------------
// 1. DATA RETRIEVAL AND LINK LOGIC
// -----------------------------------------------------

$post_id            = get_the_ID();
$post_type          = get_post_type($post_id);
$is_personal_update = $post_type === 'personal_update';
$title_attr         = the_title_attribute(['echo' => false]);

// --- CRITICAL LINK ASSIGNMENT LOGIC ---
$link          = get_permalink($post_id);
$external_link = '';
$link_target   = ''; // target="_blank" | empty
$link_rel      = ''; // rel="noopener noreferrer" | empty
$link_icon     = '→'; // Default icon is internal arrow

if ($is_personal_update) {
    // Retrieve and validate the external link
    $retrieved_link = get_post_meta($post_id, '_external_link', true);

    if (!empty($retrieved_link) && filter_var($retrieved_link, FILTER_VALIDATE_URL)) {
        // Use external link, set target/rel, and change icon
        $external_link = esc_url($retrieved_link);
        $link          = $external_link;
        $link_target   = '_blank';
        $link_rel      = 'noopener noreferrer';
        $link_icon     = '↗';
    }
}
// --- END CRITICAL LINK ASSIGNMENT LOGIC ---


// Get the featured image ID for responsive output
$thumbnail_id = get_post_thumbnail_id($post_id);


// -----------------------------------------------------
// 2. DYNAMIC CLASS ASSIGNMENT (Using passed $args)
// -----------------------------------------------------

// The $args variable is automatically available in the template part.
// Use the null coalescing operator (??) to set a default if the argument wasn't passed.
$item_classes = $args['item_classes'] ?? "group rounded-lg flex flex-col justify-between items-start p-4 bg-grayBg border border-none relative transition-all duration-75 ease-in overflow-hidden hover:bg-gray4";
$item_content = $args['item_content'] ?? "flex-3 mb-0! mt-2.5";
$item_title = $args['item_title'] ?? "block";
$item_date = $args['item_date'] ?? "";

// -----------------------------------------------------
// 3. HTML OUTPUT
// -----------------------------------------------------

if ($link) :
?>

   <a class="<?php echo esc_attr($item_classes); ?>"
      href="<?php echo esc_url($link); ?>"
      title="<?php echo esc_attr($title_attr); ?>"
      <?php if (!empty($link_target)) : ?>
        target="<?php echo esc_attr($link_target); ?>"
        rel="<?php echo esc_attr($link_rel); ?>"
      <?php endif; ?>
    >
        <?php if ($thumbnail_id) :
            // 💡 PERFORMANCE IMPROVEMENT: Using wp_get_attachment_image()
            echo wp_get_attachment_image(
                $thumbnail_id,
                'thumbnail', // A small size suitable for a 32x32 display
                false,
                [
                    'class'   => 'mr-2.5! w-8 h-8 object-cover rounded-lg',
                    'loading' => 'lazy',
                    'alt'     => $title_attr,
                ]
            );
        endif; ?>

        <div class="<?php echo esc_attr($item_content); ?> flex-2">
            <div class="flex-2">
                <h3 class="w-fit text-sm! font-medium! text-gray12 <?php echo esc_attr($item_title); ?>">
                    <?php the_title(); ?>
                    <span class="opacity-0 text-sm! group-hover:opacity-100 transition-opacity duration-300">
                        <?php echo esc_html($link_icon); ?>
                    </span>
                </h3>

             <p class="text-gray11! text-sm! mt-2">
                    <?php
                    // Get the content and display a trimmed excerpt (15 words)
                    $content = get_the_content();
                    $content = wp_strip_all_tags($content);
                    echo wp_trim_words($content, 15, '..');
                ?>
                </p>
            </div>
            <p class="<?php echo esc_attr($item_date); ?> text-xs!">
                <?php echo esc_html(get_the_date('M Y')); ?>
            </p>
        </div>
    </a>
<?php endif; ?>
