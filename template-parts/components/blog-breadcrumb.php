<?php
if (!defined('ABSPATH')) exit;

?>

<div id="blog-breadcrumb" class="flex items-center gap-1 mb-3">
  <a class="text-xs! text-gray11! hover:text-gray12!" href="/blog">Blog</a>
  <span class="text-xs! text-gray11!">></span>
  <p class="text-xs! text-gray11! mb-0! w-[200px] overflow-hidden text-ellipsis whitespace-nowrap">
      <?php the_title(); ?>
  </p>
</div>
