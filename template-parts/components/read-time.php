<?php
if (!defined('ABSPATH'))
    exit;

function wedo_get_read_time($post_id = null, $wpm = 200)
{
    $post_id = $post_id ?: get_the_ID();
    $content = get_post_field('post_content', $post_id);

    $word_count = str_word_count(strip_tags($content));
    $minutes = ceil($word_count / $wpm);

    return $minutes . ' min read';
}
?>

<div class="inline-flex min-w-min text-xs items-cente r">
    <span><?php echo wedo_get_read_time(); ?></span>
</div>