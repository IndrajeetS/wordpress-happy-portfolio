<?php
  get_template_part('page-multipage', null, [
      'post_type' => 'techtools',            // Updated to Tech Tools
      'taxonomy'  => 'techtool_category',    // Updated to Tech Tools Category
      'item_part' => 'list-tool-item',       // tools list layout
  ]);
?>
