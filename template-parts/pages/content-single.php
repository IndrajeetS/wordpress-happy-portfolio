<?php
/**
 * Single Blog Post Template Part
 *
 * Displays the post content, title, author information, and breadcrumbs.
 *
 * @package HappyPortfolio
 */

if (!defined('ABSPATH')) {
  exit;
}
?>

<?php while (have_posts()):
  the_post(); ?>

  <?php
  $author_id = get_the_author_meta('ID');
  $image_id = get_user_meta($author_id, 'profile_image_id', true);

  // Get full name if available, otherwise fallback to display name
  $first_name = get_the_author_meta('first_name', $author_id);
  $last_name = get_the_author_meta('last_name', $author_id);
  $full_name = trim($first_name . ' ' . $last_name);
  if (empty($full_name)) {
    $full_name = get_the_author_meta('display_name', $author_id);
  }

  $raw_content = get_the_content();
  $toc_html = happy_portfolio_generate_toc($raw_content);
  ?>

  <div class="w-full max-w-340 mx-auto flex flex-col xl:flex-row gap-8 xl:gap-16 relative items-start">

    <!-- Main Content Column -->
    <div class="py-8 w-full max-w-200 xl:w-[calc(100%-20rem)] shrink-0 mx-auto xl:mx-0">

      <div id="post-header">
        <h1 class="font-heading text-2xl md:text-3xl font-medium mb-8 text-gray12 w-full">
          <?php the_title(); ?>
        </h1>

        <div id="post-author" class="flex gap-2 justify-start items-start mb-8">
          <div class="flex flex-row justify-between items-center gap-1">
            <?php
            if ($image_id) {
              echo wp_get_attachment_image(
                $image_id,
                'thumbnail',
                false,
                [
                  'class' => 'w-10 h-10 object-cover rounded-full',
                  'loading' => 'lazy',
                  'alt' => esc_attr($full_name) . ' profile image',
                ]
              );
            } else {
              // Fallback to WordPress Default User Image (Gravatar)
              echo get_avatar($author_id, 40, '', '', [
                'class' => 'w-10 h-10 object-cover rounded-full',
                'alt' => esc_attr($full_name) . ' profile image',
              ]);
            }
            ?>
            <div class="flex flex-col">
              <span class="capitalize text-sm! text-gray12! font-medium">
                <?php echo esc_html($full_name); ?>
              </span>
              <div class="flex flex-row gap-2">
                <span class="text-gray11 text-xs! font-medium">
                  <?php the_time('F j, Y'); ?>
                </span>
                <?php get_template_part('template-parts/components/read-time'); ?>
              </div>
            </div>
          </div>
        </div>


      </div>

      <div id="blog-content" class="content prose prose-gray max-w-none 
        prose-p:text-lg prose-p:text-gray12 prose-p:mb-8 
        prose-ul:mb-8 prose-ol:mb-8 
        prose-li:text-base prose-li:text-gray12 prose-li:leading-relaxed prose-li:ml-4 prose-li:list-disc prose-li:list-inside 
        prose-a:no-underline prose-a:text-gray12 prose-a:bg-[linear-gradient(currentColor,currentColor)] prose-a:bg-[size:0%_1px] prose-a:bg-[position:0_100%] prose-a:bg-no-repeat prose-a:transition-[background-size] prose-a:duration-200 prose-a:ease-in-out hover:prose-a:bg-[size:100%_1px]
        prose-pre:bg-gray3 dark:prose-pre:bg-[#111] prose-pre:border prose-pre:border-border prose-pre:rounded-lg prose-pre:p-4 prose-pre:my-6 prose-pre:overflow-x-auto prose-pre:text-sm prose-pre:leading-relaxed prose-pre:shadow-sm prose-pre:[scrollbar-width:thin]
        prose-pre:prose-code:bg-transparent prose-pre:prose-code:p-0 prose-pre:prose-code:border-none prose-pre:prose-code:shadow-none prose-pre:prose-code:text-inherit prose-pre:prose-code:text-sm prose-pre:prose-code:block prose-pre:prose-code:white-space-pre
        prose-pre:prose-code:[&_span]:bg-transparent!
        [&_:not(pre)>code]:bg-gray4 dark:[&_:not(pre)>code]:bg-gray5 [&_:not(pre)>code]:text-gray12 [&_:not(pre)>code]:p-[3px_6px] [&_:not(pre)>code]:rounded [&_:not(pre)>code]:text-[0.85rem]
        [&_q]:italic [&_q]:text-gray11 dark:[&_q]:text-gray10 [&_q]:quotes-['“'_'”'_'‘'_'’'] [&_q]:before:content-[open-quote] [&_q]:after:content-[close-quote]
        [&_table]:w-full [&_table]:border-collapse [&_table]:border-spacing-0 [&_table]:overflow-hidden [&_table]:rounded-lg [&_table]:bg-contentBg [&_table]:border [&_table]:border-border [&_table]:mb-8 [&_table]:shadow-sm dark:[&_table]:shadow-none
        [&_table]:border-none [&_th]:border-none [&_td]:border-none [&_thead]:border-none
        [&_table:not(.comparison-table)_thead_tr]:bg-gray3 dark:[&_table:not(.comparison-table)_thead_tr]:bg-gray4
        [&_table:not(.comparison-table)_th]:text-gray12 [&_table:not(.comparison-table)_th]:text-[0.95rem] [&_table:not(.comparison-table)_th]:font-semibold [&_table:not(.comparison-table)_th]:p-[1rem_1.25rem] [&_table:not(.comparison-table)_th]:text-left [&_table:not(.comparison-table)_th]:border-b [&_table:not(.comparison-table)_th]:border-border
        [&_table:not(.comparison-table)_td]:p-[0.9rem_1.25rem] [&_table:not(.comparison-table)_td]:text-gray11 [&_table:not(.comparison-table)_td]:text-left
        [&_table:not(.comparison-table)_tbody_tr]:border-b [&_table:not(.comparison-table)_tbody_tr]:border-border
        [&_table:not(.comparison-table)_tbody_tr:nth-child(even)]:bg-gray2 dark:[&_table:not(.comparison-table)_tbody_tr:nth-child(even)]:bg-gray3
        [&_table:not(.comparison-table)_tbody_tr]:transition-colors [&_table:not(.comparison-table)_tbody_tr]:duration-200 hover:[&_table:not(.comparison-table)_tbody_tr]:bg-hoverBg dark:hover:[&_table:not(.comparison-table)_tbody_tr]:bg-gray4
        [&_figure]:mt-2 [&_figure]:mb-8 [&_figure_img]:rounded-lg [&_figure_img]:shadow-sm
        [&_blockquote]:relative [&_blockquote]:my-10 [&_blockquote]:p-[1.25rem_1.5rem_1.25rem_1.75rem] [&_blockquote]:bg-gray2 dark:[&_blockquote]:bg-gray3 [&_blockquote]:border-l-3 [&_blockquote]:border-toc-heightlight [&_blockquote]:rounded-lg [&_blockquote]:text-gray12 [&_blockquote]:text-[1.05rem] [&_blockquote]:leading-relaxed [&_blockquote]:transition-all [&_blockquote]:duration-250 [&_blockquote]:before:content-['“'] [&_blockquote]:before:absolute [&_blockquote]:before:left-3 [&_blockquote]:before:top-[0.2rem] [&_blockquote]:before:text-[2.5rem] [&_blockquote]:before:leading-none [&_blockquote]:before:text-gray8 [&_blockquote]:before:opacity-60 dark:[&_blockquote]:before:text-inherit [&_blockquote_p]:m-0 [&_blockquote_p]:text-gray12 [&_blockquote_cite]:block [&_blockquote_cite]:mt-3 [&_blockquote_cite]:text-[0.85rem] [&_blockquote_cite]:text-gray11
        [&_hr]:border-none [&_hr]:border-t [&_hr]:border-border">
        <div id="post-content" class="mb-10">
          <?php
          // Step 1: Get raw content
          $content = get_the_content();

          // Step 2: Fix broken code blocks BEFORE WP filters
          $content = preg_replace_callback(
            '/<p>\s*<code>(.*?)<\/code>\s*<\/p>/is',
            function ($matches) {
              $code = $matches[1];

              // Convert <br> to real line breaks
              $code = str_replace(['<br>', '<br/>', '<br />'], "\n", $code);

              return '<pre><code>' . $code . '</code></pre>';
            },
            $content
          );

          // Step 3: Now apply WordPress filters
          $content = apply_filters('the_content', $content);

          echo $content;
          ?>
        </div>
      </div>

      <?php
      $post_faqs = get_post_meta(get_the_ID(), '_happy_post_faqs', true);
      if (!empty($post_faqs) && is_array($post_faqs)):
        ?>
        <div id="faq-section" class="mt-16 border-t border-border pt-10">
          <h2 class="text-xl font-medium mb-8 text-gray12 w-full">
            Frequent Questions
          </h2>
          <div class="faq-container flex flex-col gap-4">
            <?php foreach ($post_faqs as $index => $faq): ?>
              <div
                class="faq-item border border-gray-200 cursor-pointer rounded-xl overflow-hidden transition-all duration-300 dark:border-gray5">
                <button
                  class="faq-button cursor-pointer w-full flex items-center justify-between p-5 text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded-xl"
                  aria-expanded="false" aria-controls="faq-content-<?php echo $index; ?>">
                  <span
                    class="text-[1.05rem] font-medium text-gray12 dark:text-gray12"><?php echo esc_html($faq['title']); ?></span>
                  <span class="faq-icon text-gray11 dark:text-gray10 flex-shrink-0 ml-4 transition-transform duration-300">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round" class="faq-icon-vertical">
                      <line x1="12" y1="5" x2="12" y2="19"></line>
                      <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                  </span>
                </button>
                <div id="faq-content-<?php echo $index; ?>" class="faq-content" aria-hidden="true">
                  <div class="faq-content-inner text-gray11 dark:text-gray11 text-base leading-relaxed">
                    <?php echo wpautop(wp_kses_post($faq['content'])); ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <?php
      $related_posts = happy_portfolio_get_related_posts(get_the_ID(), 4);
      ?>

      <?php if (!empty($related_posts)): ?>
        <div class="mt-16 border-t border-border pt-10">
          <h2 class="font-heading text-lg font-medium mb-6 text-gray12">
            Related Articles
          </h2>

          <div class="grid gap-4 w-full sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3">
            <?php foreach ($related_posts as $post):
              setup_postdata($post); ?>

              <?php get_template_part('template-parts/content', 'post-item'); ?>

            <?php endforeach;
            wp_reset_postdata(); ?>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <!-- Table of Contents Sidebar -->
    <?php if ($toc_html): ?>
      <aside id="post-toc-sidebar"
        class="hidden xl:block w-[18rem] shrink-0 sticky top-10 self-start max-h-[calc(100vh-6rem)] overflow-y-auto [scrollbar-width:thin]">
        <?php echo $toc_html; ?>
      </aside>
    <?php endif; ?>

  </div>

<?php endwhile; ?>

<!-- Fullscreen Image Lightbox -->
<div id="image-lightbox"
  class="fixed inset-0 z-[10000] flex flex-col items-center justify-center bg-[#0b0c10]/95 backdrop-blur-xl opacity-0 invisible pointer-events-none transition-all duration-250 ease-in-out group [&.is-active]:opacity-100 [&.is-active]:visible [&.is-active]:pointer-events-auto">
  <!-- Top Bar -->
  <div
    class="top-bar absolute top-0 left-0 right-0 p-6 flex justify-between items-center text-gray-300 z-50 opacity-0 -translate-y-2.5 transition-all duration-250 ease-in-out group-[.is-active]:opacity-100 group-[.is-active]:translate-y-0">
    <div id="lightbox-counter" class="text-sm font-medium tracking-widest text-gray-400">1 / 10</div>
    <button id="lightbox-close"
      class="p-2.5 rounded-md bg-white/5 hover:bg-white/10 hover:text-white transition-all cursor-pointer border border-white/5 bg-gray-900!">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
      <span class="sr-only">Close</span>
    </button>
  </div>

  <!-- Navigation Arrows -->
  <button id="lightbox-prev"
    class="lightbox-nav-btn absolute left-2 md:left-6 top-1/2 -translate-y-1/2 p-3 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white rounded-md z-50 cursor-pointer border border-white/5 bg-gray-900! opacity-0 pointer-events-none scale-90 transition-all duration-200 ease-in-out [&.is-visible]:opacity-100 [&.is-visible]:pointer-events-auto [&.is-visible]:scale-100">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
      stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="15 18 9 12 15 6"></polyline>
    </svg>
    <span class="sr-only">Previous</span>
  </button>
  <button id="lightbox-next"
    class="lightbox-nav-btn absolute right-2 md:right-6 top-1/2 -translate-y-1/2 p-3 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white rounded-md z-50 cursor-pointer border border-white/5 bg-gray-900! opacity-0 pointer-events-none scale-90 transition-all duration-200 ease-in-out [&.is-visible]:opacity-100 [&.is-visible]:pointer-events-auto [&.is-visible]:scale-100">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
      stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="9 18 15 12 9 6"></polyline>
    </svg>
    <span class="sr-only">Next</span>
  </button>

  <!-- Image Container -->
  <div id="lightbox-image-container"
    class="relative w-full h-[100dvh] flex items-center justify-center p-4 sm:p-12 md:p-20 cursor-zoom-out">
    <img id="lightbox-image" src="" alt="Enlarged"
      class="opacity-0 scale-96 transition-all duration-300 ease-in-out max-w-full max-h-[calc(100dvh-8rem)] max-sm:max-h-[calc(100dvh-5rem)] object-contain drop-shadow-2xl cursor-default [&.is-loaded]:opacity-100 [&.is-loaded]:scale-100">
  </div>
</div>


<!-- <a class="text-xs! text-gray12!" target="_blank" rel="noopener noreferrer"
  href="https://www.linkedin.com/in/happydas93/">@happydas</a> -->