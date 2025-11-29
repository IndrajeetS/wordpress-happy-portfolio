<?php
if (!defined('ABSPATH')) exit;

function wedo_get_read_time($post_id = null, $wpm = 200) {
    $post_id = $post_id ?: get_the_ID();
    $content = get_post_field('post_content', $post_id);

    $word_count = str_word_count(strip_tags($content));
    $minutes    = ceil($word_count / $wpm);

    return $minutes . ' min read';
}
?>

<div class="inline-flex mb-4 min-w-min text-xs px-2 py-1 rounded-xl text-gray12 bg-gray4 items-center gap-1">
    <span class="iconify text-sm text-gray10!" data-icon="mingcute:time-line" data-height="15" data-width="15"></span>
    <span><?php echo wedo_get_read_time(); ?></span>
</div>
