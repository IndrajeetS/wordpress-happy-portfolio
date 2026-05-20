<?php
  get_template_part('template-parts/tabbed-listing', null, [
      'post_type' => 'reading_list',         // correct
      'taxonomy'  => 'reading_list_category',// correct
      'item_part' => 'list-reading-item',    // correct item layout for reading page
  ]);
?>
