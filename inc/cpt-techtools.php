<?php
/**
 * Custom Post Type: Tech Tools
 */

function happy_register_techtools_cpt()
{

    $labels = array(
        'name' => _x('Tech Stack', 'Post Type General Name'),
        'singular_name' => _x('Tool', 'Post Type Singular Name'),
        'menu_name' => __('Tech Stack'),
        'name_admin_bar' => __('Tech Tool'),
        'add_new' => __('Add New'),
        'add_new_item' => __('Add New Tool'),
        'edit_item' => __('Edit Tool'),
        'new_item' => __('New Tool'),
        'view_item' => __('View Tool'),
        'search_items' => __('Search Tools'),
    );

    $args = array(
        'label' => __('Tech Stack'),
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true, // Gutenberg support
        'menu_icon' => 'dashicons-hammer',
        'supports' => array('title', 'thumbnail'), // Only image + title
        'has_archive' => true,
        'rewrite' => array('slug' => 'tech-tools'),
    );

    register_post_type('techtools', $args);
}
add_action('init', 'happy_register_techtools_cpt');

/**
 * Add Meta Box for External Link (Tech Stack)
 */
function happy_add_techtools_meta_box()
{
    add_meta_box(
        'happy_techtool_details',
        __('Tool Details', 'happy-theme'),
        'happy_render_techtools_meta_box',
        'techtools',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'happy_add_techtools_meta_box');

/**
 * Render Meta Box HTML
 */
function happy_render_techtools_meta_box($post)
{
    $external_link = get_post_meta($post->ID, '_wedo_external_link', true);
    $description = get_post_meta($post->ID, '_wedo_techtool_description', true);
    ?>
    <div class="field-group" style="margin-top:10px;">
        <label for="happy_techtool_link" style="font-weight:600; display:block; margin-bottom:4px;">
            <?php _e('External Link (optional)', 'happy-theme'); ?>
        </label>
        <input type="url" name="happy_techtool_link" id="happy_techtool_link"
            value="<?php echo esc_attr($external_link); ?>" placeholder="https://example.com"
            style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
    </div>

    <div class="field-group" style="margin-top:15px;">
        <label for="happy_techtool_description" style="font-weight:600; display:block; margin-bottom:4px;">
            <?php _e('Short Description', 'happy-theme'); ?>
        </label>
        <textarea name="happy_techtool_description" id="happy_techtool_description" rows="3"
            style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; resize:vertical;"
            placeholder="<?php _e('e.g. A fast, modern frontend framework.', 'happy-theme'); ?>"><?php echo esc_textarea($description); ?></textarea>
    </div>
    <?php
}

/**
 * Save Meta Box Data
 */
function happy_save_techtools_meta($post_id)
{
    if (array_key_exists('happy_techtool_link', $_POST)) {
        update_post_meta($post_id, '_wedo_external_link', sanitize_text_field($_POST['happy_techtool_link']));
    }

    if (array_key_exists('happy_techtool_description', $_POST)) {
        update_post_meta($post_id, '_wedo_techtool_description', sanitize_textarea_field($_POST['happy_techtool_description']));
    }
}
add_action('save_post_techtools', 'happy_save_techtools_meta');
