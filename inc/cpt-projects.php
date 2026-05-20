<?php
/**
 * Custom Post Type: Projects
 */

function happy_register_projects_cpt()
{

    $labels = array(
        'name' => _x('Projects', 'Post Type General Name'),
        'singular_name' => _x('Project', 'Post Type Singular Name'),
        'menu_name' => __('Projects'),
        'name_admin_bar' => __('Project'),
        'add_new' => __('Add New'),
        'add_new_item' => __('Add New Project'),
        'edit_item' => __('Edit Project'),
        'new_item' => __('New Project'),
        'view_item' => __('View Project'),
        'search_items' => __('Search Projects'),
    );

    $args = array(
        'label' => __('Projects'),
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true, // Gutenberg support
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array('title', 'thumbnail'), // Only image + title
        'has_archive' => false,
        'rewrite' => array('slug' => 'project-item', 'with_front' => false),
    );

    register_post_type('projects', $args);
}
add_action('init', 'happy_register_projects_cpt');

/**
 * Add Meta Box for External Link (Projects)
 */
function happy_add_projects_meta_box()
{
    add_meta_box(
        'happy_project_details',
        __('Project Details', 'happy-theme'),
        'happy_render_projects_meta_box',
        'projects',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'happy_add_projects_meta_box');

/**
 * Render Meta Box HTML
 */
function happy_render_projects_meta_box($post)
{
    $external_link = get_post_meta($post->ID, '_wedo_external_link', true);
    $description = get_post_meta($post->ID, '_wedo_project_description', true);
    ?>
    <div class="field-group" style="margin-top:10px;">
        <label for="happy_project_link" style="font-weight:600; display:block; margin-bottom:4px;">
            <?php _e('External Link (optional)', 'happy-theme'); ?>
        </label>
        <input type="url" name="happy_project_link" id="happy_project_link"
            value="<?php echo esc_attr($external_link); ?>" placeholder="https://example.com"
            style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
    </div>

    <div class="field-group" style="margin-top:15px;">
        <label for="happy_project_description" style="font-weight:600; display:block; margin-bottom:4px;">
            <?php _e('Short Description', 'happy-theme'); ?>
        </label>
        <textarea name="happy_project_description" id="happy_project_description" rows="3"
            style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; resize:vertical;"
            placeholder="<?php _e('e.g. A fast, modern frontend framework.', 'happy-theme'); ?>"><?php echo esc_textarea($description); ?></textarea>
    </div>
    <?php
}

/**
 * Save Meta Box Data
 */
function happy_save_projects_meta($post_id)
{
    if (array_key_exists('happy_project_link', $_POST)) {
        update_post_meta($post_id, '_wedo_external_link', sanitize_text_field($_POST['happy_project_link']));
    }

    if (array_key_exists('happy_project_description', $_POST)) {
        update_post_meta($post_id, '_wedo_project_description', sanitize_textarea_field($_POST['happy_project_description']));
    }
}
add_action('save_post_projects', 'happy_save_projects_meta');
