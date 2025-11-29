<?php
  get_template_part('page-multipage', null, [
      'post_type' => 'resource_tools',       // correct
      'taxonomy'  => 'resource_category',    // correct
      'item_part' => 'list-tool-item',       // tools list layout
  ]);
?>
