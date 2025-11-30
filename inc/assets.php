<?php
/**
 * Asset Enqueue Functions for Frontend and Admin
 *
 * This file handles the registration and enqueuing of CSS and JavaScript
 * assets for the HappyPortfolio theme, with a focus on performance.
 *
 * @package HappyPortfolio
 * @subpackage Includes
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define asset version constants for easy updates
if ( ! defined( 'HAPPY_PORTFOLIO_VERSION' ) ) {
    define( 'HAPPY_PORTFOLIO_VERSION', '1.0.0' );
}

// ==========================================================
//  HELPER FUNCTION DEFINITION
// ==========================================================

/**
 * Helper function to safely get a file's modification time (version).
 *
 * @param string $file_path Absolute path to the asset file.
 * @return string|bool File modification time (Unix timestamp) or false if file doesn't exist.
 */
function happy_portfolio_asset_version( $file_path ) {
    if ( file_exists( $file_path ) ) {
        return filemtime( $file_path );
    }
    return HAPPY_PORTFOLIO_VERSION;
}


// ==========================================================
// 🎨 GOOGLE FONTS ASYNCHRONOUS LOAD (Optimized for FOIT/FOUT)
// ==========================================================

/**
 * Preload and asynchronously load Google Fonts to prevent render blocking.
 *
 * CRITICAL OPTIMIZATION: Uses the 'media="print"' trick for async loading
 * and 'display=swap' to ensure text is visible immediately (Flash of Unstyled Text - FOUT)
 * instead of invisible (Flash of Invisible Text - FOIT).
 */
function happy_portfolio_preload_google_fonts() {
    // 1. Preload the domain
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";

    // 2. Asynchronously load the font stylesheet using media="print" trick.
    // The 'display=swap' parameter ensures the text is visible (using a fallback)
    // before the custom font (Space Grotesk) has loaded.
    $font_url = 'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap';

    echo '<link href="' . esc_url($font_url) . '" rel="stylesheet" media="print" onload="this.media=\'all\'">' . "\n";
}
add_action('wp_head', 'happy_portfolio_preload_google_fonts', 1);


// --- Frontend Asset Enqueues ---

// ==========================================================
// 📝 THEME-SPECIFIC META TAGS
// ==========================================================

/**
 * Adds theme-specific meta tags (description, author) to the document head.
 * This is executed early using 'wp_head' action.
 */
function happy_portfolio_add_meta_tags() {
    // Get the site description or default to a portfolio phrase
    $site_description = get_bloginfo('description', 'display');
    if (empty($site_description)) {
        $site_description = 'A modern, high-performance digital portfolio showcasing work and projects.';
    }

    // 1. Standard SEO Description Tag
    echo '<meta name="description" content="' . esc_attr($site_description) . '">' . "\n";

    // 2. Author Tag (using blog name as a default author reference)
    echo '<meta name="author" content="' . esc_attr(get_bloginfo('name', 'display')) . '">' . "\n";

    // NOTE: If you need to include structured data like the one you provided
    // (e.g., 'Author: A.N. Author, Category: Books, etc.'), it is better
    // to use Schema.org JSON-LD or custom fields specific to the content type.
}
add_action('wp_head', 'happy_portfolio_add_meta_tags', 2);

/**
 * Enqueue all theme-specific CSS and JS assets for the frontend.
 *
 * @since 1.0.0
 * @action wp_enqueue_scripts
 */
function happy_portfolio_enqueue_frontend_assets() {
    $template_uri = get_template_directory_uri();
    $template_dir = get_template_directory();

    // --- Stylesheets ---

    // 1. Theme Styles (Tailwind Compiled) - Still render blocking. Advanced fix requires Critical CSS.
    wp_enqueue_style(
        'happy-portfolio-main',
        $template_uri . '/assets/css/output.css',
        [],
        happy_portfolio_asset_version( $template_dir . '/assets/css/output.css' )
    );

    // 2. Google Font: NO LONGER ENQUEUED HERE. Handled by happy_portfolio_preload_google_fonts().

    // 3. Icon Library (Font Awesome) - Switched to deferred JS to eliminate CSS blocking.
    wp_enqueue_script(
        'happy-portfolio-fontawesome-js',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js',
        [],
        '6.4.0',
        true // Load in footer, will be deferred by filter below
    );


    // --- Scripts (All will be DEFERRED by the filter below) ---

    // 1. Main Theme JavaScript
    wp_enqueue_script(
        'happy-portfolio-main-js',
        $template_uri . '/assets/js/main.js',
        ['jquery'],
        happy_portfolio_asset_version( $template_dir . '/assets/js/main.js' ),
        true
    );

    // 2. Iconify Library
    wp_enqueue_script(
        'iconify',
        'https://code.iconify.design/3/3.1.0/iconify.min.js',
        [],
        '3.1.0',
        true
    );

    // 3. Custom Scripts
    wp_enqueue_script(
        'wedo-custom-scripts',
        $template_uri . '/assets/js/wedo-custom.js',
        ['jquery'],
        happy_portfolio_asset_version( $template_dir . '/assets/js/wedo-custom.js' ),
        true
    );

    // Localize AJAX URL for wedo-custom-scripts
    wp_localize_script(
        'wedo-custom-scripts',
        'wedoAjax',
        [ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ]
    );

    // 4. Component-specific Scripts
    wp_enqueue_script(
        'happy-portfolio-greeting',
        $template_uri . '/assets/js/greeting.js',
        [],
        happy_portfolio_asset_version( $template_dir . '/assets/js/greeting.js' ),
        true
    );

    wp_enqueue_script(
        'happy-portfolio-localTime',
        $template_uri . '/assets/js/local-time.js',
        [],
        happy_portfolio_asset_version( $template_dir . '/assets/js/local-time.js' ),
        true
    );

    wp_enqueue_script(
        'happy-portfolio-copy-to-clip',
        $template_uri . '/assets/js/copy-to-clip.js',
        [],
        happy_portfolio_asset_version( $template_dir . '/assets/js/copy-to-clip.js' ),
        true
    );

     wp_enqueue_script(
        'wedo-helper-class',
        $template_uri . '/assets/js/helper.js',
        [],
        happy_portfolio_asset_version( $template_dir . '/assets/js/helper.js' ),
        true
    );

    // Localize AJAX URL for wedo-tools-filter
    wp_localize_script(
        'wedo-tools-filter',
        'wedoAjax',
        [ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ]
    );
}
add_action( 'wp_enqueue_scripts', 'happy_portfolio_enqueue_frontend_assets' );


// --- Admin Asset Enqueues ---
// ... (Admin function remains unchanged for now) ...
function happy_portfolio_enqueue_admin_assets( $hook ) {
    $template_uri = get_template_directory_uri();
    $template_dir = get_template_directory();

    // Load assets ONLY on the Appearance -> Menus screen for the Icon Picker.
    if ( 'nav-menus.php' === $hook ) {

        // --- Stylesheets ---

        // 1. Tailwind CSS (for consistent admin styling)
        wp_enqueue_style(
            'happy-portfolio-tailwind-admin',
            $template_uri . '/assets/css/output.css',
            [],
            happy_portfolio_asset_version( $template_dir . '/assets/css/output.css' )
        );

        // 2. Custom Icon Picker CSS
        wp_enqueue_style(
          'happy-portfolio-admin-icon-picker-style',
          $template_uri . '/assets/css/admin-icon-picker.css',
          [],
          happy_portfolio_asset_version( $template_dir . '/assets/css/admin-icon-picker.css' )
        );

        // --- Scripts ---

        // 1. Iconify Library (Admin-specific version, if needed. Used 3.1.1 here.)
        wp_enqueue_script(
            'iconify-admin',
            'https://code.iconify.design/3/3.1.1/iconify.min.js',
            [],
            '3.1.1',
            true
        );

        // 2. Custom Icon Picker JS
        wp_enqueue_script(
          'happy-portfolio-admin-icon-picker-script',
          $template_uri . '/assets/js/admin-icon-picker.js',
          ['jquery', 'iconify-admin'],
          happy_portfolio_asset_version( $template_dir . '/assets/js/admin-icon-picker.js' ),
          true
        );
    }
}
add_action( 'admin_enqueue_scripts', 'happy_portfolio_enqueue_admin_assets' );


// ==========================================================
// 🚀 PERFORMANCE OPTIMIZATION: DEFER SCRIPTS (CRITICAL FIX)
// ==========================================================

/**
 * Adds the 'defer' attribute to all non-critical frontend scripts, including jQuery.
 *
 * This helps eliminate the Render Blocking JS issue.
 *
 * @param string $tag    The `<script>` tag.
 * @param string $handle The script's registered handle.
 * @return string
 */
function happy_portfolio_add_defer_to_scripts( $tag, $handle ) {
    // List of ALL script handles enqueued in the frontend that should be deferred.
    $scripts_to_defer = [
        'jquery-core',                  // CRITICAL FIX: Defer core jQuery
        'jquery-migrate',               // CRITICAL FIX: Defer migrate
        'happy-portfolio-main-js',
        'iconify',
        'wedo-custom-scripts',
        'happy-portfolio-greeting',
        'happy-portfolio-localTime',
        'happy-portfolio-copy-to-clip',
        'wedo-tools-filter',
        'wedo-helper-class',
        'happy-portfolio-fontawesome-js', // Defer new Font Awesome JS
    ];

    if ( in_array( $handle, $scripts_to_defer, true ) ) {
        // Find the src attribute and insert 'defer' before it.
        return str_replace( ' src=', ' defer src=', $tag );
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'happy_portfolio_add_defer_to_scripts', 10, 2 );
