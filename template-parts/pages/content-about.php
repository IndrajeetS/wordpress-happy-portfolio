<div id="about-page" class="py-8 max-w-xl w-full mx-auto">
  <h1 class="font-heading text-2xl font-medium mb-8 text-gray12">
    <?php the_title(); ?>
  </h1>

  <div id="about-page-content" class="text-gray12 mb-12">
    <?php the_content(); ?>
  </div>

  <!-- Personal Updates -> About Page -->
  <?php
  $modal_args = array(
    'header_a' => "hidden",
    'update_section' => "w-full mb-14! flex flex-col space-y-5!",
    'updates_item' => "flex flex-row justify-start items-stretch space-x-2",
    'item_content' => "flex-3 m-0! flex flex-row justify-between items-stretch space-x-2",
    'item_title' => "hover:rounded-[4px] hover:bg-gray4 p-[2px_5px] m-[-2px_-5px] mb-0!",
  );
  get_template_part('template-parts/content', 'home-updates', $modal_args);
  ?>

  <!-- How this site was made -> About Page -->
  <?php
  $tech_info = get_post_meta(get_the_ID(), '_happy_about_tech_text', true);

  if (!empty($tech_info)): ?>
    <div class="border-t-[1.5px] border-gray3 dark:border-gray4 mt-16 mb-8 transition-colors duration-250 ease-in-out">
    </div>

    <div
      class="about-tech-info text-gray12 leading-relaxed max-w-none 
      [&>h2]:text-[1.125rem] [&>h2]:font-bold [&>h2]:text-gray12 [&>h2]:mt-14 [&>h2]:mb-[0.875rem] [&>h2]:pt-4 
      [&>h3]:text-base [&>h3]:text-gray12 [&>h3]:m-0 [&>h3]:font-normal 
      [&>p]:mb-4 [&>p]:text-gray12 
      [&>ul]:text-gray12 [&>ul]:pl-4 [&>ul]:list-disc 
      [&>ol]:text-gray12 [&>ol]:pl-4 [&>ol]:list-decimal
      [&_a]:inline-flex [&_a]:items-stretch [&_a]:text-gray12 [&_a]:no-underline [&_a]:bg-[linear-gradient(currentColor,currentColor)] [&_a]:bg-[size:0%_1px] [&_a]:bg-[position:0_100%] [&_a]:bg-no-repeat [&_a]:transition-[background-size] [&_a]:duration-200 [&_a]:ease-in-out hover:[&_a]:bg-[size:100%_1px] [&_a]:after:content-['↗'] [&_a]:after:text-[15px] [&_a]:after:ml-[2.5px] [&_a]:after:font-light [&_a]:after:no-underline [&_a]:after:hidden hover:[&_a]:after:inline-block">
      <?php echo wp_kses_post(wpautop($tech_info)); ?>

      <!-- Load Tech Tools Component -->
      <?php get_template_part('template-parts/components/about-tech-tools'); ?>
    </div>
  <?php endif; ?>

  <!-- Career info -> About Page -->
  <?php
  $career = get_post_meta(get_the_ID(), '_happy_about_career_text', true);

  if (!empty($career)): ?>
    <div class="border-t-[1.5px] border-gray3 dark:border-gray4 mt-16 mb-8 transition-colors duration-250 ease-in-out">
    </div>

    <div class="about-career-info text-gray12 leading-relaxed max-w-none">
      <?php echo wp_kses_post(wpautop($career)); ?>

      <?php get_template_part('template-parts/components/about-work-experience'); ?>
    </div>
  <?php endif; ?>

  <!-- How this site was made -> About Page -->
  <?php
  $extra_about_text = get_post_meta(get_the_ID(), '_happy_about_extra_text', true);

  if (!empty($extra_about_text)): ?>
    <div class="border-t-[1.5px] border-gray3 dark:border-gray4 mt-16 mb-8 transition-colors duration-250 ease-in-out">
    </div>

    <div
      class="about-site-info text-gray12 leading-relaxed max-w-none 
      [&>h2]:text-[1.125rem] [&>h2]:font-bold [&>h2]:text-gray12 [&>h2]:mt-14 [&>h2]:mb-[0.875rem] [&>h2]:pt-4 
      [&>h3]:text-base [&>h3]:text-gray12 [&>h3]:m-0 [&>h3]:font-normal 
      [&>p]:mb-4 [&>p]:text-gray12 
      [&>ul]:text-gray12 [&>ul]:pl-4 [&>ul]:list-disc 
      [&>ol]:text-gray12 [&>ol]:pl-4 [&>ol]:list-decimal
      [&_a]:inline-flex [&_a]:items-stretch [&_a]:text-gray12 [&_a]:no-underline [&_a]:bg-[linear-gradient(currentColor,currentColor)] [&_a]:bg-[size:0%_1px] [&_a]:bg-[position:0_100%] [&_a]:bg-no-repeat [&_a]:transition-[background-size] [&_a]:duration-200 [&_a]:ease-in-out hover:[&_a]:bg-[size:100%_1px] [&_a]:after:content-['↗'] [&_a]:after:text-[15px] [&_a]:after:ml-[2.5px] [&_a]:after:font-light [&_a]:after:no-underline [&_a]:after:hidden hover:[&_a]:after:inline-block">
      <?php echo wp_kses_post(wpautop($extra_about_text)); ?>
    </div>
  <?php endif; ?>

  <div class="border-t-[1.5px] border-gray3 dark:border-gray4 mt-16 mb-8 transition-colors duration-250 ease-in-out">
  </div>

  <!-- Personal Contact Info -> About Page -->
  <?php
  $modal_args = array(
    'contact_modal_classes' => "py-6! sm:py-6"
  );
  // Pass the arguments array to the template part
  get_template_part('template-parts/content', 'contact-model-content', $modal_args);
  ?>
</div>