<div id="home-page" class="px-4 pb-12 max-w-7xl w-full flex flex-col items-stretch">
  <h1
      id="time-based-greeting"
      class="my-4
            text-[clamp(56px,calc((100vw-350px)*.1),135px)]!
            ml-[clamp(-12px,calc((100vw-350px)*-.009),0px)]!
            font-[390]!
          text-gray12
            transition-colors duration-250 ease-in
            leading-snug
            "></h1>

  <div class="text-gray12 mb-12">
    <?php the_content(); ?>
  </div>

  <?php get_template_part('template-parts/content','home-updates'); ?>
  <?php get_template_part('template-parts/content','home-writings'); ?>
  <?php get_template_part('template-parts/content','home-readings'); ?>
  <?php get_template_part('template-parts/content','home-tools'); ?>
</div>
