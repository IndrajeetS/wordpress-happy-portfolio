<?php
/**
 * Utility and Helper Functions
 *
 * @package HappyPortfolio
 * @subpackage Includes
 */

/**
 * Get greeting based on local WordPress timezone.
 *
 * @return string Time-based greeting (e.g., "Good morning").
 */
function wedo_get_time_based_greeting() {
    // Define the specific timezone ID for IST (UTC+5:30)
    $timezone_id = 'Asia/Kolkata';

    try {
        // Create the DateTimeZone object
        $timezone = new DateTimeZone($timezone_id);

        // Create datetime object using the specific, correct timezone
        $date = new DateTime('now', $timezone);

        $hour = (int) $date->format('G'); // 24-hour format (0–23)

    } catch (Exception $e) {
        // Fallback if there's any issue creating the DateTime objects
        return "Hello, time error!";
    }

    // Determine greeting
    // Current time 16:48 (Hour = 16) should fall into the Good afternoon range
    if ($hour >= 5 && $hour < 12) {
        $greeting = "Good morning";
    } elseif ($hour >= 12 && $hour < 17) {
        $greeting = "Good afternoon"; // THIS IS THE RANGE FOR HOUR 16 (4 PM)
    } elseif ($hour >= 17 && $hour < 21) {
        $greeting = "Good evening";
    } else {
        $greeting = "Good night";
    }

    // You can temporarily uncomment the line below to check what hour it calculated
    // $greeting .= " (Hour: " . $hour . ")";

    return $greeting;
}


/**
 * Generate Table of Contents from post content
 */
function happy_portfolio_generate_toc($content) {
    // Match <h2> and <h3> headings
    preg_match_all('/<h([2-3])[^>]*>(.*?)<\/h[2-3]>/', $content, $matches, PREG_SET_ORDER);

    if (empty($matches)) {
        return ''; // No headings - no TOC
    }

    $toc = '<div class="mb-10 p-4 border border-gray-200 rounded-lg bg-gray-50">';
    $toc .= '<h3 class="text-lg font-semibold mb-2! mt-0!">Table of Contents</h3>';
    $toc .= '<ul class="space-y-1 list-disc pl-4">';

    foreach ($matches as $match) {
        $level = intval($match[1]);
        $title = wp_strip_all_tags($match[2]);

        // Generate ID slug
        $id = sanitize_title($title);

        // Add anchor to the real heading (handled later)
        $toc .= '<li class="mb-0!">';
        $toc .= '<a href="#'.esc_attr($id).'" class="text-sm! text-gray11! hover:text-gray12!">'.$title.'</a>';
        $toc .= '</li>';
    }

    $toc .= '</ul></div>';

    return $toc;
}

/**
 * Add IDs to headings in content so TOC links work
 */
function happy_portfolio_add_heading_ids($content) {
    return preg_replace_callback(
        '/<h([2-3])([^>]*)>(.*?)<\/h[2-3]>/',
        function ($matches) {
            $level = $matches[1];
            $attrs = $matches[2];
            $text  = wp_strip_all_tags($matches[3]);
            $id    = sanitize_title($text);

            return '<h'.$level.' id="'.esc_attr($id).'"'.$attrs.'>'.$matches[3].'</h'.$level.'>';
        },
        $content
    );
}
