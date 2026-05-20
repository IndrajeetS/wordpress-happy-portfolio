<?php
if (!defined('ABSPATH'))
  exit;
?>

<div id="blog-breadcrumb" class="mb-3 flex items-center gap-1">
  <a class="text-xs text-gray11 hover:text-gray12" href="/blog">Blog</a>
  <span class="text-xs text-gray11">&gt;</span>
  <p class="mb-0 w-[200px] overflow-hidden text-ellipsis whitespace-nowrap text-xs text-gray11">
    <?php the_title(); ?>
  </p>
</div>