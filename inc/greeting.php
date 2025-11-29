<?php
/**
 * Time-based Greeting Functionality (Client-side)
 *
 * Contains the function to output the greeting placeholder
 * and registers the necessary JavaScript file.
 *
 * @package HappyPortfolio
 */

/**
 * 1. Outputs the HTML placeholder for the greeting.
 * JavaScript will target the 'time-based-greeting' ID.
 *
 * @return string The HTML span element.
 */
function happy_portfolio_get_js_greeting_placeholder() {
    return '<span id="time-based-greeting"></span>';
}

