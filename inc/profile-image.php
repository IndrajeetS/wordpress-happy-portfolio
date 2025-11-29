<?php
/**
 * User Profile Image Picker
 *
 * Allows users to pick a profile image from the WordPress Media Library.
 *
 * @package HappyPortfolio
 */

if (!defined('ABSPATH')) {
    exit;
}


/**
 * Ensure wp.media() is loaded on profile screens
 */
function happy_portfolio_enqueue_media_on_profile($hook) {
    if ($hook === 'profile.php' || $hook === 'user-edit.php') {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'happy_portfolio_enqueue_media_on_profile');

/**
 * Add custom profile image field.
 */
function happy_portfolio_user_profile_image_field($user) {
    $image_id  = get_user_meta($user->ID, 'profile_image_id', true);

    // Fallback URL for the preview image if no image is set (empty string)
    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';

    // Define Tailwind classes for the image preview (replacing inline styles)
    $img_class = 'w-20 h-20 rounded-lg object-cover mb-2'; // w-20 = 80px, h-20 = 80px

    // Placeholder image attributes for when no image is selected
    $placeholder_style = 'border:1px dashed #ccc; background:#f0f0f0;';

    ?>

    <h3><?php _e('Profile Image', 'happy-portfolio'); ?></h3>

    <table class="form-table">
        <tr>
            <th><label for="profile_image_id"><?php _e('Upload Profile Image', 'happy-portfolio'); ?></label></th>
            <td>
                <?php
                if ($image_id) {
                    // 🚀 Improvement: Use wp_get_attachment_image() for responsive output
                    echo wp_get_attachment_image(
                        $image_id,
                        'thumbnail', // Use a small, generated size
                        false,
                        [
                            'id'      => 'profile-image-preview',
                            'class'   => esc_attr($img_class),
                            'loading' => 'lazy',
                            'style'   => 'display:block;', // Ensure it's not inline-flex, etc.
                            'alt'     => esc_attr(sprintf(__('Profile image for %s', 'happy-portfolio'), $user->display_name)),
                        ]
                    );
                } else {
                    // Fallback to a simple <img> with placeholder styling if no ID is set
                    // This ensures the JS target element exists.
                    echo '<img loading="lazy" id="profile-image-preview" '
                       . 'src="' . esc_url($image_url) . '" '
                       . 'class="' . esc_attr($img_class) . '" '
                       . 'style="' . esc_attr($placeholder_style) . ' display:block;" ' // Add placeholder style
                       . 'alt="' . esc_attr(sprintf(__('No profile image set for %s', 'happy-portfolio'), $user->display_name)) . '" />';
                }
                ?>

                <input type="hidden"
                       name="profile_image_id"
                       id="profile_image_id"
                       value="<?php echo esc_attr($image_id); ?>" />

                <button type="button"
                        class="button"
                        id="upload-profile-image">
                    <?php _e('Select Image', 'happy-portfolio'); ?>
                </button>

                <button type="button"
                        class="button"
                        id="remove-profile-image"
                        <?php echo $image_id ? '' : 'style="display:none;"'; ?>>
                    <?php _e('Remove', 'happy-portfolio'); ?>
                </button>

                <p class="description"><?php _e('Select a square image (e.g., 150x150) for best results.', 'happy-portfolio'); ?></p>
            </td>
        </tr>
    </table>

    <script>
        jQuery(document).ready(function ($) {
            var frame;
            var $imageIdInput = $('#profile_image_id');
            var $imagePreview = $('#profile-image-preview');
            var $removeButton = $('#remove-profile-image');

            // Define the placeholder style in JS for removal
            var placeholderStyle = '<?php echo esc_js($placeholder_style); ?>';

            // Function to update the image preview and button state
            function updatePreview(id, url) {
                $imageIdInput.val(id);
                $imagePreview.attr('src', url);
                $imagePreview.removeAttr('srcset').removeAttr('sizes'); // Clean up any old srcset if using manual <img>

                if (id) {
                    // Set classes/styles for a loaded image
                    $imagePreview.attr('style', 'display:block;').removeClass('hidden');
                    $removeButton.show();
                } else {
                    // Set placeholder styles for no image
                    $imagePreview.attr('style', placeholderStyle + ' display:block;').removeClass('hidden');
                    $removeButton.hide();
                }
            }

            $('#upload-profile-image').on('click', function (e) {
                e.preventDefault();

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: 'Select Profile Image',
                    button: { text: 'Use This Image' },
                    multiple: false
                });

                frame.on('select', function () {
                    var attachment = frame.state().get('selection').first().toJSON();
                    // We only update the hidden ID and URL in the preview.
                    // On save, WordPress will generate the full responsive tag.
                    updatePreview(attachment.id, attachment.url);
                });

                frame.open();
            });

            $('#remove-profile-image').on('click', function () {
                updatePreview('', ''); // Clear image ID and URL
            });

            // Initial state check for the remove button
            if (!$imageIdInput.val()) {
                $removeButton.hide();
            }
        });
    </script>

    <?php
}
add_action('show_user_profile', 'happy_portfolio_user_profile_image_field');
add_action('edit_user_profile', 'happy_portfolio_user_profile_image_field');

/**
 * Save profile image.
 */
function happy_portfolio_save_user_profile_image($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return;
    }

    if (isset($_POST['profile_image_id'])) {
        update_user_meta($user_id, 'profile_image_id', sanitize_text_field($_POST['profile_image_id']));
    }
}
add_action('personal_options_update', 'happy_portfolio_save_user_profile_image');
add_action('edit_user_profile_update', 'happy_portfolio_save_user_profile_image');
