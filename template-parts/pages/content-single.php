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

<div style="max-width:50rem" class="py-8 max-w-xl w-full">
  <?php while (have_posts()) : the_post(); ?>

      <?php
        $author_id = get_the_author_meta('ID');
        $image_id = get_user_meta($author_id, 'profile_image_id', true);
        // Note: $image_url and $avatar_url are now redundant if $image_id is used below
        // $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
        // $avatar_url = get_avatar_url($author_id, array('size' => 150));

        $raw_content = get_the_content();
        $toc_html = happy_portfolio_generate_toc($raw_content);
      ?>

      <?php get_template_part('template-parts/components/blog-breadcrumb'); ?>

      <div class="grid grid-cols-1 gap-y-4 md:gap-y-0 md:grid-cols-3 justify-between items-start border-b-[0.5px] border-solid border-gray8 pb-10 mb-10">

          <div id="post-header" class="col-span-2">
            <?php get_template_part('template-parts/components/read-time'); ?>
            <h1 class="font-3xl! font-medium! mb-2 text-gray12"><?php the_title(); ?></h1>
            <p><?php the_excerpt(); ?></p>
          </div>

          <div id="post-author" class="flex gap-2 justify-end items-start flex-col-reverse md:items-end md:flex-col">
            <div class="flex flex-row justify-between items-center gap-1">
              <?php
                  if ($image_id) {
                    // 💡 PERFORMANCE IMPROVEMENT: Using wp_get_attachment_image()
                    // Use a small size appropriate for a 40x40px display.
                    echo wp_get_attachment_image(
                        $image_id,
                        'thumbnail', // A small image size
                        false,
                        [
                            'class'   => 'w-10 h-10 object-cover rounded-full',
                            'loading' => 'lazy',
                            'alt'     => get_the_author_meta('display_name') . ' profile image',
                        ]
                    );
                  }
              ?>
              <div class="flex flex-col">
                <span class="capitalize text-sm! text-gray12! font-medium">By <?php the_author(); ?></span>
                <a class="text-xs! text-gray12!" target="_blank" rel="noopener noreferrer" href="https://www.linkedin.com/in/happydas93/">@happydas</a>
              </div>
            </div>
            <span class="text-gray11 text-xs! font-medium">Published: <?php the_time('F j, Y'); ?></span>
          </div>

      </div>

      <div id="blog-content" class="content prose prose-gray max-w-none px-[2%] md:px-[10%]">
          <div id="post-content" class="mb-10">
              <?php the_content(); ?>
          </div>
      </div>

      <div class="flex justify-center">
        <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>" class="text-xs! duration-500 transition-colors ease-in-out px-3.5 py-2 rounded-2xl bg-gray2 text-gray11! hover:text-gray12 hover:bg-gray4"> ←  Back to blog</a>
      </div>

  <?php endwhile; ?>
</div>
