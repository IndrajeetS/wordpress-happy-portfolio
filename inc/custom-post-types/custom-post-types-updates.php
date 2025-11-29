<?php
// --- wedo-personal-update.php ---
// Purpose: Registers the "Personal Update" custom post type with title, description, image, and external link meta field.
// Notes:
//  - Post type slug: personal_update
//  - Supports title, editor (description), thumbnail (featured image)
//  - Includes a custom meta box for an optional external link field

/**
 * Register Custom Post Type: Personal Update
 * ------------------------------------------
 * Adds a new post type for personal updates, visible in the WordPress admin.
 * These updates can contain a title, description, image, and an external link.
 */
function wedo_register_personal_update_cpt() {

  $labels = array(
    'name'                  => 'Personal Updates',
    'singular_name'         => 'Personal Update',
    'menu_name'             => 'Personal Updates',
    'name_admin_bar'        => 'Personal Update',
    'add_new'               => 'Add New',
    'add_new_item'          => 'Add New Personal Update',
    'new_item'              => 'New Personal Update',
    'edit_item'             => 'Edit Personal Update',
    'view_item'             => 'View Personal Update',
    'all_items'             => 'All Personal Updates',
    'search_items'          => 'Search Personal Updates',
    'not_found'             => 'No updates found.',
    'not_found_in_trash'    => 'No updates found in Trash.',
  );

  $args = array(
    'labels'                => $labels,
    'public'                => true,
    'publicly_queryable'    => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_icon'             => 'dashicons-megaphone', // 🎤 Icon for visibility
    'query_var'             => true,
    'rewrite'               => array('slug' => 'personal-update'),
    'capability_type'       => 'post',
    'has_archive'           => true,
    'hierarchical'          => false,
    'menu_position'         => 6,
    'supports'              => array('title', 'editor', 'thumbnail'),
  );

  register_post_type('personal_update', $args);
}
add_action('init', 'wedo_register_personal_update_cpt');



/**
 * Add Meta Box: External Link
 * ---------------------------
 * Allows the admin to optionally attach an external link to a personal update.
 */
function wedo_add_personal_update_meta_box() {
  add_meta_box(
    'personal_update_link',
    'External Link',
    'wedo_render_personal_update_link_meta_box',
    'personal_update',
    'normal',
    'high'
  );
}
add_action('add_meta_boxes', 'wedo_add_personal_update_meta_box');



/**
 * Render Meta Box Field (HTML)
 */
function wedo_render_personal_update_link_meta_box($post) {
  $external_link = get_post_meta($post->ID, '_external_link', true);
  ?>
  <label for="personal_update_external_link" style="display:block;margin-bottom:8px;font-weight:600;">
    External Link (optional)
  </label>
  <input
    type="url"
    id="personal_update_external_link"
    name="personal_update_external_link"
    value="<?php echo esc_attr($external_link); ?>"
    style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;"
    placeholder="https://example.com"
  />
  <p style="color:#666;margin-top:6px;font-size:12px;">Enter a full URL if this update links to an external source.</p>
  <?php
}



/**
 * Save Meta Box Field
 * -------------------
 * Persists the external link value when saving the post.
 */
function wedo_save_personal_update_meta_box($post_id) {
  if (array_key_exists('personal_update_external_link', $_POST)) {
    update_post_meta(
      $post_id,
      '_external_link',
      esc_url_raw($_POST['personal_update_external_link'])
    );
  }
}
add_action('save_post_personal_update', 'wedo_save_personal_update_meta_box');



/**
 * Example Helper Function
 * -----------------------
 * Easily retrieve the external link from templates or shortcodes.
 *
 * @param int $post_id
 * @return string|null
 */
function wedo_get_personal_update_link($post_id) {
  $link = get_post_meta($post_id, '_external_link', true);
  return $link ? esc_url($link) : null;
}
