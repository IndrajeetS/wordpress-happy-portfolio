<div id="writing-page" class="py-8 max-w-xl w-full mx-auto">
    <h1 class="mb-4 font-medium!"><?php the_title(); ?></h1>

  <div class="text-gray-600 mb-12">
    <?php the_content(); ?>
  </div>

  <?php
    echo render_recent_writing_grid(array(
        'posts_per_page' => 10,
        'show_header'    => false,
        'grid_col'       => 'sm:grid-cols-1 md:grid-cols-2'
    ));
  ?>
</div>
