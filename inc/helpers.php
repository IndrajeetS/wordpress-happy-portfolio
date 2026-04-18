<?php
/**
 * Utility and Helper Functions
 *
 * @package HappyPortfolio
 * @subpackage Includes
 */


/**
 * Generate Table of Contents from post content
 */
function happy_portfolio_generate_toc($content)
{
  // Match <h2> and <h3> headings, case-insensitive, dot-matches-all
  preg_match_all('/<h([23])[^>]*>(.*?)<\/h\1>/is', $content, $matches, PREG_SET_ORDER);

  if (empty($matches)) {
    return ''; // No headings - no TOC
  }

  $toc = '<nav class="toc-container relative font-inter">';
  $toc .= '<div class="text-base font-semibold mb-4 text-gray1! dark:text-gray1 mb-0">Table of contents</div>';

  // Container for the border and indicator
  $toc .= '<div class="relative border-l border-gray5 dark:border-gray8">';
  // The indicator line (blue)
  $toc .= '<div id="toc-indicator" class="absolute left-[-1px] w-[2px] bg-toc-heightlight dark:bg-toc-heightlight transition-all duration-300 ease-out z-10 hidden" style="height: 20px; top: 0px;"></div>';

  $toc .= '<ul class="space-y-4 list-none p-0 m-0 toc-list relative">';

  foreach ($matches as $match) {
    $level = intval($match[1]);
    $title = wp_strip_all_tags($match[2]);

    // Generate ID slug
    $id = sanitize_title($title);

    $indent_class = ($level === 3) ? 'ml-8' : 'ml-3';
    $text_class = ($level === 3) ? 'text-small font-light' : 'text-base font-medium';

    $toc .= '<li class="relative leading-tight mb-2">';
    $toc .= '<a href="#' . esc_attr($id) . '" data-target="' . esc_attr($id) . '" class="toc-item block transition-colors duration-200 ' . $indent_class . ' ' . $text_class . '">' . $title . '</a>';
    $toc .= '</li>';
  }

  $toc .= '</ul></div></nav>';

  return $toc;
}

/**
 * Add IDs to headings in content so TOC links work
 */
function happy_portfolio_add_heading_ids($content)
{
  return preg_replace_callback(
    '/<h([2-3])([^>]*)>(.*?)<\/h[2-3]>/',
    function ($matches) {
      $level = $matches[1];
      $attrs = $matches[2];
      $text = wp_strip_all_tags($matches[3]);
      $id = sanitize_title($text);

      return '<h' . $level . ' id="' . esc_attr($id) . '"' . $attrs . '>' . $matches[3] . '</h' . $level . '>';
    },
    $content
  );
}

/**
 * Get related posts for a given post.
 */
function happy_portfolio_get_related_posts($post_id, $limit = 3)
{
  $post = get_post($post_id);

  if (!$post)
    return [];

  // 1. Categories
  $categories = wp_get_post_categories($post_id);

  // 2. Tags
  $tags = wp_get_post_tags($post_id, ['fields' => 'ids']);

  // 3. Extract keywords from title
  $title = strtolower($post->post_title);
  $keywords = array_filter(explode(' ', $title), function ($word) {
    return strlen($word) > 4; // ignore small words
  });

  // 4. Build query
  $args = [
    'post_type' => 'post',
    'posts_per_page' => $limit,
    'post__not_in' => [$post_id],
    'ignore_sticky_posts' => 1,
    'orderby' => 'date',
    'order' => 'DESC',
  ];

  $tax_query = ['relation' => 'OR'];

  if (!empty($categories)) {
    $tax_query[] = [
      'taxonomy' => 'category',
      'field' => 'term_id',
      'terms' => $categories,
    ];
  }

  if (!empty($tags)) {
    $tax_query[] = [
      'taxonomy' => 'post_tag',
      'field' => 'term_id',
      'terms' => $tags,
    ];
  }

  if (count($tax_query) > 1) {
    // If we have categories or tags, use them!
    $args['tax_query'] = $tax_query;
  } else {
    // Fallback ONLY if no categories and no tags exist: Semantic search based on title keywords
    if (!empty($keywords)) {
      $args['s'] = implode(' ', array_slice($keywords, 0, 5));
    }
  }

  $query = new WP_Query($args);


  return $query->posts;
}

/**
 * Get a consistent color index for tags based on term slug
 */
function wedo_get_term_color($term_slug, $colors)
{
  $hash = crc32($term_slug); // stable hash
  $index = abs($hash) % count($colors);
  return $colors[$index];
}
