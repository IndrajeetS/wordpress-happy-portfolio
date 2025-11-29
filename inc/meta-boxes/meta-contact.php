<?php
if (!defined('ABSPATH')) exit;

/**
 * ==========================================================
 *  CALLBACK FUNCTIONS — CONTACT PAGE FIELDS
 * ==========================================================
 */

/** Email Field */
function happy_contact_email_callback($post) {
    wp_nonce_field('happy_contact_save_meta', 'happy_contact_meta_nonce');

    $value = get_post_meta($post->ID, '_happy_contact_email', true);
    ?>
    <input
        type="email"
        name="happy_contact_email"
        value="<?php echo esc_attr($value); ?>"
        style="width:100%; padding:8px;"
    />
    <p style="color:#666;margin-top:5px;">Email shown on Contact modal/page.</p>
    <?php
}

/** Calendar Link Field */
function happy_contact_calendar_callback($post) {
    wp_nonce_field('happy_contact_save_meta', 'happy_contact_meta_nonce');

    $value = get_post_meta($post->ID, '_happy_contact_calendar', true);
    ?>
    <input
        type="url"
        name="happy_contact_calendar"
        value="<?php echo esc_attr($value); ?>"
        style="width:100%; padding:8px;"
    />
    <p style="color:#666;margin-top:5px;">Calendar link (Google Calendar, Cal.com, Calendly, etc.)</p>
    <?php
}

/** Twitter — Social Link */
function happy_contact_twitter_callback($post) {
    wp_nonce_field('happy_contact_save_meta', 'happy_contact_meta_nonce');

    $value = get_post_meta($post->ID, '_happy_contact_twitter', true);
    ?>
    <input
        type="url"
        name="happy_contact_twitter"
        value="<?php echo esc_attr($value); ?>"
        style="width:100%; padding:8px;"
    />
    <p style="color:#666;margin-top:5px;">Twitter profile URL.</p>
    <?php
}

/** LinkedIn — Social Link */
function happy_contact_linkedin_callback($post) {
    wp_nonce_field('happy_contact_save_meta', 'happy_contact_meta_nonce');

    $value = get_post_meta($post->ID, '_happy_contact_linkedin', true);
    ?>
    <input
        type="url"
        name="happy_contact_linkedin"
        value="<?php echo esc_attr($value); ?>"
        style="width:100%; padding:8px;"
    />
    <p style="color:#666;margin-top:5px;">LinkedIn profile URL.</p>
    <?php
}

/** Reddit — Social Link */
function happy_contact_reddit_callback($post) {
    wp_nonce_field('happy_contact_save_meta', 'happy_contact_meta_nonce');

    $value = get_post_meta($post->ID, '_happy_contact_reddit', true);
    ?>
    <input
        type="url"
        name="happy_contact_reddit"
        value="<?php echo esc_attr($value); ?>"
        style="width:100%; padding:8px;"
    />
    <p style="color:#666;margin-top:5px;">Reddit profile or subreddit URL.</p>
    <?php
}


/**
 * ==========================================================
 *  ADD META BOXES — ONLY ON CONTACT PAGE
 * ==========================================================
 */
function happy_contact_meta_boxes() {

    $screen = get_current_screen();
    if ($screen->id !== 'page') return;

    $contact_page = get_page_by_path('contact');
    if (!$contact_page) return;

    // Only show if editing the contact page
    if (!isset($_GET['post']) || intval($_GET['post']) !== intval($contact_page->ID)) {
        return;
    }

    add_meta_box(
        'happy_contact_email_field',
        'Contact Email',
        'happy_contact_email_callback',
        'page',
        'normal',
        'default'
    );

    add_meta_box(
        'happy_contact_calendar_field',
        'Calendar Link',
        'happy_contact_calendar_callback',
        'page',
        'normal',
        'default'
    );

    add_meta_box(
        'happy_contact_twitter_field',
        'Twitter Link',
        'happy_contact_twitter_callback',
        'page',
        'normal',
        'default'
    );

    add_meta_box(
        'happy_contact_linkedin_field',
        'LinkedIn Link',
        'happy_contact_linkedin_callback',
        'page',
        'normal',
        'default'
    );

    add_meta_box(
        'happy_contact_reddit_field',
        'Reddit Link',
        'happy_contact_reddit_callback',
        'page',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'happy_contact_meta_boxes');


/**
 * Save Contact Meta Fields for About & Modal
 *
 * Handles:
 * - Email       (sanitized using sanitize_email)
 * - Calendar    (URL)
 * - Twitter     (URL)
 * - Linkedin    (URL)
 * - Reddit      (URL)
 */

function happy_contact_save_meta($post_id) {

    // Security / permission checks
    if (!isset($_POST['happy_contact_meta_nonce']) ||
        !wp_verify_nonce($_POST['happy_contact_meta_nonce'], 'happy_contact_save_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!current_user_can('edit_post', $post_id))
        return;

    // Fields your UI sends through POST
    $fields = [
        'happy_contact_email'    => '_happy_contact_email',
        'happy_contact_calendar' => '_happy_contact_calendar',
        'happy_contact_twitter'  => '_happy_contact_twitter',
        'happy_contact_linkedin' => '_happy_contact_linkedin',
        'happy_contact_reddit'   => '_happy_contact_reddit',
    ];

    foreach ($fields as $form_key => $meta_key) {

        if (!isset($_POST[$form_key])) {
            continue;
        }

        $value = $_POST[$form_key];

        // EMAIL MUST BE SANITIZED AS EMAIL — NOT URL
        if ($form_key === 'happy_contact_email') {
            $sanitized = sanitize_email($value);
        }
        // ALL OTHER FIELDS ARE URLS
        else {
            $sanitized = esc_url_raw($value);
        }

        update_post_meta($post_id, $meta_key, $sanitized);
    }
}

add_action('save_post_page', 'happy_contact_save_meta');
