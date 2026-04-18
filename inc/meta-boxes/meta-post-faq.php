<?php
if (!defined('ABSPATH')) exit;

/**
 * Add Meta Box for Post FAQs (Accordions)
 */
function happy_add_post_faq_meta_box() {
    add_meta_box(
        'happy_post_faqs',
        'Frequent Questions (Accordions)',
        'happy_post_faq_meta_box_callback',
        'post', // Target only regular posts
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'happy_add_post_faq_meta_box');

/**
 * Render the Meta Box
 */
function happy_post_faq_meta_box_callback($post) {
    wp_nonce_field('happy_post_faq_save_meta', 'happy_post_faq_meta_nonce');

    // Retrieve saved FAQs (should be an array of ['title' => '', 'content' => ''])
    $faqs = get_post_meta($post->ID, '_happy_post_faqs', true);
    if (!is_array($faqs)) {
        $faqs = [];
    }
    ?>

    <div id="happy-faq-repeater">
        <div id="happy-faq-rows">
            <?php 
            if (!empty($faqs)) {
                foreach ($faqs as $index => $faq) {
                    happy_render_faq_row($index, $faq['title'], $faq['content']);
                }
            } else {
                // Render one empty row by default
                happy_render_faq_row(0, '', '');
            }
            ?>
        </div>
        <p>
            <button type="button" class="button button-primary" id="happy-add-faq-row">Add New Question</button>
        </p>
    </div>

    <style>
        .happy-faq-row {
            background: #f9f9f9;
            border: 1px solid #ccd0d4;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
        }
        .happy-faq-row input[type="text"], 
        .happy-faq-row textarea {
            width: 100%;
            margin-bottom: 10px;
        }
        .happy-remove-faq-row {
            position: absolute;
            top: 15px;
            right: 15px;
            color: #d63638;
            cursor: pointer;
            font-weight: bold;
        }
        .happy-remove-faq-row:hover {
            color: #a00;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var repeater = document.getElementById('happy-faq-rows');
            var addButton = document.getElementById('happy-add-faq-row');

            // Template for new row
            var template = `
                <div class="happy-faq-row">
                    <span class="happy-remove-faq-row">Remove</span>
                    <label><strong>Question Title</strong></label>
                    <input type="text" name="happy_faq_title[]" value="" placeholder="e.g. Do all scraping MCP servers handle anti-bot protection?" />
                    <label><strong>Answer Content</strong></label>
                    <textarea name="happy_faq_content[]" rows="4" placeholder="Enter the answer here..."></textarea>
                </div>
            `;

            addButton.addEventListener('click', function(e) {
                e.preventDefault();
                repeater.insertAdjacentHTML('beforeend', template);
            });

            repeater.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('happy-remove-faq-row')) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to remove this question?')) {
                        e.target.closest('.happy-faq-row').remove();
                    }
                }
            });
        });
    </script>
    <?php
}

/**
 * Helper to render a single FAQ row
 */
function happy_render_faq_row($index, $title, $content) {
    ?>
    <div class="happy-faq-row">
        <span class="happy-remove-faq-row">Remove</span>
        <label><strong>Question Title</strong></label>
        <input type="text" name="happy_faq_title[]" value="<?php echo esc_attr($title); ?>" placeholder="e.g. Do all scraping MCP servers handle anti-bot protection?" />
        
        <label><strong>Answer Content</strong></label>
        <textarea name="happy_faq_content[]" rows="4" placeholder="Enter the answer here..."><?php echo esc_textarea($content); ?></textarea>
    </div>
    <?php
}

/**
 * Save Meta Box Data
 */
function happy_post_faq_save_meta($post_id) {
    // Check Nonce
    if (!isset($_POST['happy_post_faq_meta_nonce']) || !wp_verify_nonce($_POST['happy_post_faq_meta_nonce'], 'happy_post_faq_save_meta')) {
        return;
    }
    
    // Prevent save on autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Check post type
    if (get_post_type($post_id) !== 'post') return;

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) return;

    $faqs = [];

    if (isset($_POST['happy_faq_title']) && is_array($_POST['happy_faq_title'])) {
        $titles = $_POST['happy_faq_title'];
        $contents = isset($_POST['happy_faq_content']) ? $_POST['happy_faq_content'] : [];

        for ($i = 0; $i < count($titles); $i++) {
            $title = sanitize_text_field($titles[$i]);
            // Allow basic HTML in content like paragraphs, bold, links.
            $content = wp_kses_post($contents[$i]);

            if (!empty($title) || !empty($content)) {
                $faqs[] = [
                    'title' => $title,
                    'content' => $content
                ];
            }
        }
    }

    if (!empty($faqs)) {
        update_post_meta($post_id, '_happy_post_faqs', $faqs);
    } else {
        delete_post_meta($post_id, '_happy_post_faqs');
    }
}
add_action('save_post', 'happy_post_faq_save_meta');
