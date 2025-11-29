<?php get_header() ?>

<h2><?php the_archive_title() ?></h2>

<?php
  while(have_posts()) {
    the_post(); ?>
    <div>
      <h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
    </div>
  <?php }
?>

<?php get_footer() ?>
