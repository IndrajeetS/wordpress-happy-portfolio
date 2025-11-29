<?php
if (!defined('ABSPATH')) exit;

/**
 * Add Meta Boxes for Working Experience fields
 */
function happy_we_add_meta_boxes() {

    add_meta_box(
        'happy_we_details',
        'Experience Details',
        'happy_we_details_callback',
        'working_experience',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'happy_we_add_meta_boxes');


/**
 * Meta Box Callback
 */
function happy_we_details_callback($post) {

    wp_nonce_field('happy_we_save_meta', 'happy_we_meta_nonce');

    $timeframe = get_post_meta($post->ID, '_happy_we_timeframe', true);
    $link      = get_post_meta($post->ID, '_happy_we_link', true);
    ?>

    <p><label><strong>Timeframe</strong></label></p>
    <input
        type="text"
        name="happy_we_timeframe"
        value="<?php echo esc_attr($timeframe); ?>"
        class="widefat"
        placeholder="e.g. 2020 – 2024"
    />

    <p style="margin-top:20px;"><label><strong>Title Link (optional)</strong></label></p>
    <input
        type="url"
        name="happy_we_link"
        value="<?php echo esc_attr($link); ?>"
        class="widefat"
        placeholder="https://example.com"
    />

    <p style="margin-top:20px; color:#666;">
        Description is handled by the main editor below.
    </p>

<?php
}


/**
 * Save Meta Fields
 */
function happy_we_save_meta($post_id) {

    // Validate
    if (!isset($_POST['happy_we_meta_nonce']) ||
        !wp_verify_nonce($_POST['happy_we_meta_nonce'], 'happy_we_save_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (get_post_type($post_id) !== 'working_experience') return;

    // Save Timeframe
    if (isset($_POST['happy_we_timeframe'])) {
        update_post_meta(
            $post_id,
            '_happy_we_timeframe',
            sanitize_text_field($_POST['happy_we_timeframe'])
        );
    }

    // Save Link
    if (isset($_POST['happy_we_link'])) {
        update_post_meta(
            $post_id,
            '_happy_we_link',
            esc_url_raw($_POST['happy_we_link'])
        );
    }
}
add_action('save_post', 'happy_we_save_meta');
