<?php
$experiences = get_posts([
    'post_type' => 'working_experience',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
]);

foreach ($experiences as $exp):

    $timeframe = get_post_meta($exp->ID, '_happy_we_timeframe', true);
    $link = get_post_meta($exp->ID, '_happy_we_link', true);

    $clean_content = $exp->post_content;
    ?>


    <?php if ($link): ?>
        <a href="<?php echo esc_url($link); ?>" target="_blank" class="no-underline! hover:no-underline!">
            <div class="experience-item m-0 flex flex-row items-baseline py-4">
                <?php if ($timeframe): ?>
                    <p class="text-[10px]! font-bold text-gray8 uppercase tracking-[0.15em] w-24 shrink-0 pt-1">
                        <?php echo esc_html($timeframe); ?>
                    </p>
                <?php endif; ?>

                <div class="company-info flex-1">
                    <h3 class="text-lg! font-medium text-gray12 w-full leading-tight mb-2">
                        <?php echo esc_html($exp->post_title); ?>
                    </h3>
                    <?php if (!empty($clean_content)): ?>
                        <div class="text-gray11 text-xs! leading-relaxed font-[390]">
                            <?php echo wp_kses_post(trim($clean_content)); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </a>
    <?php else: ?>
        <?php echo esc_html($exp->post_title); ?>
    <?php endif; ?>

<?php endforeach; ?>