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
// require_once HAPPY_PORTFOLIO_INC_DIR . 'greeting.php';


require_once HAPPY_PORTFOLIO_INC_DIR . 'ajax-optimization.php';

/**
 * Rendering functions.
 */
require_once HAPPY_PORTFOLIO_INC_DIR . 'render-writing-grid.php';


add_filter('the_content', 'happy_portfolio_add_heading_ids', 9);

