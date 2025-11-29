<?php
/**
 * Theme Setup Functions
 *
 * @package HappyPortfolio
 * @subpackage Includes
 */

/**
 * Sets up theme defaults and registers support for WordPress features.
 *
 * @since 1.0.0
 */
function happy_portfolio_theme_setup() {
     // Add support for <title> tag management.
    add_theme_support('title-tag');

    // Enable post thumbnails (featured images).
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'happy_portfolio_theme_setup');

/**
 * Registers navigation menus for the theme.
 *
 * @since 1.0.0
 */
function happy_portfolio_register_menus() {
    register_nav_menus([
        'primary_menu'   => __('Primary Menu', 'happy-portfolio'),
        'resources_menu' => __('Resources Menu', 'happy-portfolio'),
        'connect_menu'   => __('Connect Menu', 'happy-portfolio'),
    ]);
}
add_action('after_setup_theme', 'happy_portfolio_register_menus');
