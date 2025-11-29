<?php
/**
 * Custom Nav Menu Icon Picker Functionality
 *
 * @package HappyPortfolio
 * @subpackage Includes
 */

/**
 * Add an icon field to each nav menu item in the admin.
 */
function happy_add_menu_item_icon_field($item_id, $item) {
      $icon = get_post_meta($item_id, '_menu_item_icon', true);
    ?>
    <p class="field-icon description description-wide">
        <label for="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>">
            <strong>Menu Icon</strong><br>
            <input type="text"
                   id="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>"
                   class="widefat menu-item-icon-input"
                   name="menu-item-icon[<?php echo esc_attr($item_id); ?>]"
                   value="<?php echo esc_attr($icon); ?>"
                   placeholder="e.g. mdi:home" />
            <button type="button" class="button select-icon-button" data-item-id="<?php echo esc_attr($item_id); ?>">Select Icon</button>
            <span class="iconify iconify-preview" data-icon="<?php echo esc_attr($icon); ?>" style="margin-left:8px;"></span>
        </label>
    </p>
    <?php
}
add_action('wp_nav_menu_item_custom_fields', 'happy_add_menu_item_icon_field', 10, 2);

/**
 * Save the selected icon meta when the menu item is saved.
 */
function happy_save_menu_item_icon($menu_id, $menu_item_db_id) {
    if (isset($_POST['menu-item-icon'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_menu_item_icon', sanitize_text_field($_POST['menu-item-icon'][$menu_item_db_id]));
    } else {
        delete_post_meta($menu_item_db_id, '_menu_item_icon');
    }
}
add_action('wp_update_nav_menu_item', 'happy_save_menu_item_icon', 10, 2);

/**
 * Output the selected icon on the front-end.
 */
function happy_display_menu_icons($title, $item, $args, $depth) {
    $icon = get_post_meta($item->ID, '_menu_item_icon', true);

    if (!empty($icon)) {
        $title = '<span class="iconify mr-2" data-icon="' . esc_attr($icon) . '"></span>' . $title;
    }
    return $title;
    return $title;
}
add_filter('nav_menu_item_title', 'happy_display_menu_icons', 10, 4);
