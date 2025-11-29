<?php
$experiences = get_posts([
    'post_type' => 'working_experience',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
]);

foreach ($experiences as $exp) :

    $timeframe = get_post_meta($exp->ID, '_happy_we_timeframe', true);
    $link      = get_post_meta($exp->ID, '_happy_we_link', true);
?>

<div class="experience-item m-0 flex flex-row py-6">

    <?php if ($timeframe): ?>
      <p class="text-sm text-gray10! mb-0! w-32"><?php echo esc_html($timeframe); ?></p>
    <?php endif; ?>

    <div class="company-info">
      <h3 class="">
          <?php if ($link): ?>
              <a href="<?php echo esc_url($link); ?>" target="_blank">
                  <?php echo esc_html($exp->post_title); ?>
              </a>
          <?php else: ?>
              <?php echo esc_html($exp->post_title); ?>
          <?php endif; ?>
      </h3>
      <?php
        $content = trim($exp->post_content);
        if ($content) :
        ?>
        <div class="text-gray11!"><?php echo wp_kses_post(wpautop($exp->post_content)); ?></div>
      <?php endif; ?>
    </div>
</div>

<?php endforeach; ?>
