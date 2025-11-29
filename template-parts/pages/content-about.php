<div id="about-page" class="py-8 max-w-xl w-full mx-auto">
  <h1 class="mb-4 font-medium!"><?php the_title(); ?></h1>

  <div class="text-gray12 mb-12 blog-content">
    <?php the_content(); ?>
  </div>

  <!-- Personal Updates -> About Page -->
  <?php
      $modal_args = array(
          'header_a' => "hidden",
          'update_section' => "w-full mb-14! flex flex-col space-y-5! group",
          'updates_item' => "flex flex-row justify-start items-stretch space-x-2",
          'item_content' => "flex-3 m-0! flex flex-row justify-between items-stretch space-x-2",
          'item_title' => "hover:rounded-[4px] hover:bg-gray4 p-[2px_5px] m-[-2px_-5px] mb-0!",
      );
    get_template_part('template-parts/content','home-updates', $modal_args);
  ?>

  <!-- How this site was made -> About Page -->
  <?php
  $tech_info = get_post_meta(get_the_ID(), '_happy_about_tech_text', true);

  if (!empty($tech_info)) : ?>
  <div class="divider"></div>

      <div class="about-tech-info blog-content">
        <?php echo wp_kses_post(wpautop($tech_info)); ?>

        <!-- Load Tech Tools Component -->
        <?php get_template_part('template-parts/components/about-tech-tools'); ?>
      </div>
  <?php endif; ?>

  <!-- Career info -> About Page -->
  <?php
  $career = get_post_meta(get_the_ID(), '_happy_about_career_text', true);

  if (!empty($career)) : ?>
  <div class="divider"></div>

      <div class="about-career-info blog-content">
        <?php echo wp_kses_post(wpautop($career)); ?>

        <?php get_template_part('template-parts/components/about-work-experience'); ?>
      </div>
  <?php endif; ?>

  <!-- How this site was made -> About Page -->
  <?php
  $extra_about_text = get_post_meta(get_the_ID(), '_happy_about_extra_text', true);

  if (!empty($extra_about_text)) : ?>
  <div class="divider"></div>

      <div class="about-site-info blog-content">
        <?php echo wp_kses_post(wpautop($extra_about_text)); ?>
      </div>
  <?php endif; ?>

  <div class="divider"></div>

  <!-- Personal Contact Info -> About Page -->
  <?php
      $modal_args = array(
          'contact_modal_classes' => "py-6! sm:py-6"
      );
      // Pass the arguments array to the template part
      get_template_part('template-parts/content', 'contact-model-content', $modal_args);
  ?>
</div>
