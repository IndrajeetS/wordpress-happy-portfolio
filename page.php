<?php
$slug = get_post_field('post_name', get_post());
$content_template = $slug;

include locate_template('page-wrapper.php');
