<?php
/**
 * AJAX Optimization Functions
 *
 * Contains logic to prevent redundant script and style enqueuing
 * when serving partial content via AJAX requests (SPA routing).
 * This significantly reduces duplicate scripts running in the console.
 */

// Global flag to track if we are in an AJAX content fetch
function happy_portfolio_is_ajax_content_request() {
    // Check if the custom 'ajax=1' query parameter is present
    return isset($_GET['ajax']) && $_GET['ajax'] === '1';
}

/**
 * Stop all non-essential script and style enqueuing during AJAX requests.
 */
function happy_portfolio_dequeue_ajax_assets() {
    if (happy_portfolio_is_ajax_content_request()) {
        // Get all enqueued scripts and styles
        global $wp_scripts, $wp_styles;

        // --- Dequeue Scripts ---
        // Only keep jQuery, as it's often a core dependency for plugins.
        $script_handles_to_keep = array('jquery', 'jquery-core', 'jquery-migrate');

        // Iterate through all registered scripts and dequeue those not in the safe list.
        $scripts_to_dequeue = array_diff(array_keys($wp_scripts->registered), $script_handles_to_keep);

        foreach ($scripts_to_dequeue as $handle) {
            wp_dequeue_script($handle);
        }

        // --- Dequeue Styles ---
        // We dequeue all styles, as the main page already has the CSS loaded (a crucial part of SPA).
        foreach (array_keys($wp_styles->registered) as $handle) {
            wp_dequeue_style($handle);
        }
    }
}
// Run this late (priority 9999) to ensure we dequeue scripts added by other functions or plugins.
add_action('wp_enqueue_scripts', 'happy_portfolio_dequeue_ajax_assets', 9999);
