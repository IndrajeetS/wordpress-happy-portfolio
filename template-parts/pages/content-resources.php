<?php
/**
 * Template part for displaying Resources content in page-multipage.php
 */
if (!defined('ABSPATH'))
    exit;

get_template_part('page-multipage', null, [
    'post_type' => 'resource_tools',
    'taxonomy' => 'resource_category',
    'item_part' => 'list-tool-item',
]);
