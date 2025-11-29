<?php
/**
 * Theme Functions for Happy WordPress Portfolio
 *
 * This file acts as a central hub for including all theme functionality,
 * which is split into separate, organized files within the 'inc' directory.
 *
 * @package HappyPortfolio
 */

// Define the path to the includes directory.
define( 'HAPPY_PORTFOLIO_INC_DIR', get_template_directory() . '/inc/' );

// ==========================================================
//  INCLUDE THEME FUNCTIONALITY FILES
// ==========================================================

/**
 * Theme Setup (title-tag, post-thumbnails, etc.) and Nav Menu Registration.
 */
require_once HAPPY_PORTFOLIO_INC_DIR . 'theme-setup.php';

/**
 * Frontend and Admin Asset Enqueuing.
 */
require_once HAPPY_PORTFOLIO_INC_DIR . 'assets.php';

/**
 * Functionality for the Admin Menu Icon Picker.
 */
require_once HAPPY_PORTFOLIO_INC_DIR . 'admin-icon-picker.php';

/**
 * Functionality for the User profile picker
 */
require_once HAPPY_PORTFOLIO_INC_DIR . 'profile-image.php';

/**
 * Functionality for the add new field in about page
 */
require_once HAPPY_PORTFOLIO_INC_DIR . '/meta-boxes/meta-about.php';


/**
 * Custom Post Types and Taxonomies, plus Meta Boxes and AJAX.
 */
require_once HAPPY_PORTFOLIO_INC_DIR . '/custom-post-types/custom-post-types-tools.php'; // Tools
require_once HAPPY_PORTFOLIO_INC_DIR . '/custom-post-types/custom-post-types-reading.php'; // Reading list
require_once HAPPY_PORTFOLIO_INC_DIR . '/custom-post-types/custom-post-types-updates.php'; // Personal Updates
require_once get_template_directory() . '/inc/cpt-techtools.php'; // About -> tech tools
require_once get_template_directory() . '/inc/tax-techtool-category.php'; // About -> tech tools
require_once get_template_directory() . '/inc/custom-post-types/cpt-working-experience.php'; // About -> work experience
require_once get_template_directory() . '/inc/meta-boxes/meta-working-experience.php'; // About -> work experience
require_once get_template_directory() . '/inc/meta-boxes/meta-contact.php'; // Contact -> Contact Info

/**
 * Resgiter Navigation Walker
 */
require_once HAPPY_PORTFOLIO_INC_DIR . 'navigation-walker.php';
// require_once get_theme_file_path('/inc/navigation-walker.php');


/**
 * General utility/helper functions.
 */
require_once HAPPY_PORTFOLIO_INC_DIR . 'helpers.php';

/**
 * Time-based greeting functionality.
 */
require_once HAPPY_PORTFOLIO_INC_DIR . 'greeting.php';

/**
 * Rendering functions.
 */
require_once HAPPY_PORTFOLIO_INC_DIR . 'render-writing-grid.php';

// That's it! All functionality is now loaded from the 'inc' directory.
// In functions.php or inc/helpers.php
function happy_portfolio_dequeue_ajax_scripts() {
    if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
        // Replace 'js', 'dom', and 'iconify' with the actual handles if possible
        // If they are not WordPress handles, this won't work.
        wp_dequeue_script('js');
        wp_dequeue_script('dom');

        // A more aggressive approach is to dequeue all but essential scripts.
        // However, if the scripts are hardcoded in the HTML, you need to fix the source.
    }
}
add_action('wp_enqueue_scripts', 'happy_portfolio_dequeue_ajax_scripts', 100);


add_filter('the_content', 'happy_portfolio_add_heading_ids', 9);

