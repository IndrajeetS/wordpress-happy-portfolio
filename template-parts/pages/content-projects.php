<?php
get_template_part('template-parts/tabbed-listing', null, [
  'post_type' => 'projects',
  'taxonomy' => 'project_category',
  'item_part' => 'list-tool-item',
]);
?>
