<?php
// Ensure this runs only if it's called within the WordPress environment
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<?php
  // Call the function for the home page: 4 posts, show header
  echo render_recent_writing_grid(array(
      'posts_per_page' => 4,
      'show_header'    => true,
  ));
?>
