<?php
if (!defined('ABSPATH')) exit;

/**
 * ==========================================================
 *  CALLBACK FUNCTIONS (must be defined BEFORE add_meta_box)
 * ==========================================================
 */

/** FIELD 1 — Portfolio History */
function happy_portfolio_about_history_callback($post) {
    wp_nonce_field('happy_about_save_meta', 'happy_about_meta_nonce');
    $value = get_post_meta($post->ID, '_happy_about_extra_text', true);

    wp_editor(
        $value,
        'happy_about_extra_text',
        [
            'textarea_name' => 'happy_about_extra_text',
            'media_buttons' => true,
            'textarea_rows' => 8,
            'teeny'         => false,
            'quicktags'     => true,
        ]
    );

    echo '<p style="color:#666;margin-top:5px;">Displayed on the About page (Portfolio History section).</p>';
}

/** FIELD 2 — Tech Information */
function happy_portfolio_about_tech_callback($post) {
    wp_nonce_field('happy_about_save_meta', 'happy_about_meta_nonce');
    $value = get_post_meta($post->ID, '_happy_about_tech_text', true);

    wp_editor(
        $value,
        'happy_about_tech_text',
        [
            'textarea_name' => 'happy_about_tech_text',
            'media_buttons' => true,
            'textarea_rows' => 8,
            'teeny'         => false,
            'quicktags'     => true,
        ]
    );

    echo '<p style="color:#666;margin-top:5px;">Displayed above the Tech Tools component.</p>';
}

/** FIELD 3 — Career Info */
function happy_portfolio_about_career_callback($post) {
    wp_nonce_field('happy_about_save_meta', 'happy_about_meta_nonce');
    $value = get_post_meta($post->ID, '_happy_about_career_text', true);

    wp_editor(
        $value,
        'happy_about_career_text',
        [
            'textarea_name' => 'happy_about_career_text',
            'media_buttons' => true,
            'textarea_rows' => 8,
            'teeny'         => false,
            'quicktags'     => true,
        ]
    );

    echo '<p style="color:#666;margin-top:5px;">Displayed on the About page (Career section).</p>';
}



/**
 * ==========================================================
 *  ADD META BOXES ONLY ON THE ABOUT PAGE
 * ==========================================================
 */
function happy_portfolio_about_meta_boxes() {

    $screen = get_current_screen();
    if ($screen->id !== 'page') return;

    $about_page = get_page_by_path('about');
    if (!$about_page) return;

    if (!isset($_GET['post']) || intval($_GET['post']) !== intval($about_page->ID)) {
        return;
    }

    add_meta_box(
        'happy_about_extra_field',
        'Portfolio History',
        'happy_portfolio_about_history_callback',
        'page',
        'normal',
        'default'
    );

    add_meta_box(
        'happy_about_tech_field',
        'Tech Information',
        'happy_portfolio_about_tech_callback',
        'page',
        'normal',
        'default'
    );

    add_meta_box(
        'happy_about_career_field',
        'Career Info',
        'happy_portfolio_about_career_callback',
        'page',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'happy_portfolio_about_meta_boxes');



/**
 * ==========================================================
 *  SAVE HANDLER
 * ==========================================================
 */
function happy_portfolio_about_save_meta($post_id) {

    if (!isset($_POST['happy_about_meta_nonce']) ||
        !wp_verify_nonce($_POST['happy_about_meta_nonce'], 'happy_about_save_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!current_user_can('edit_post', $post_id))
        return;

    if (isset($_POST['happy_about_extra_text'])) {
        update_post_meta($post_id, '_happy_about_extra_text', wp_kses_post($_POST['happy_about_extra_text']));
    }

    if (isset($_POST['happy_about_tech_text'])) {
        update_post_meta($post_id, '_happy_about_tech_text', wp_kses_post($_POST['happy_about_tech_text']));
    }

    if (isset($_POST['happy_about_career_text'])) {
        update_post_meta($post_id, '_happy_about_career_text', wp_kses_post($_POST['happy_about_career_text']));
    }
}
add_action('save_post_page', 'happy_portfolio_about_save_meta');
