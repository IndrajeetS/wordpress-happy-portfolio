<?php
$shared_walker = new Button_Walker_Nav_Menu();
?>

<aside
  class="absolute hidden h-[calc(100vh-1rem)] w-58 animate-[fadeIn_0.15s_ease-out_forwards] flex-col justify-between px-[0.15rem] md:flex md:opacity-100 m-2 items-stretch">
  <nav class="-m-1.5 space-y-4 p-1.5">

    <div class="mb-3 flex items-center justify-center p-3">
      <div class="text-2xl font-bold text-gray12"><?php echo esc_html(get_option('happy_nav_title', 'Happy')); ?></div>
    </div>

    <?php if (has_nav_menu('primary_menu')): ?>
      <ul class="m-0 space-y-1">
        <?php wp_nav_menu([
          'theme_location' => 'primary_menu',
          'container' => false,
          'items_wrap' => '%3$s',
          'walker' => $shared_walker,
        ]); ?>
      </ul>
    <?php endif; ?>

    <?php if (has_nav_menu('resources_menu')): ?>
      <div class="m-0">
        <p class="pb-2 pl-3.5 pt-5 text-[11.6px] font-[480] text-gray11 transition duration-150 ease-in-out">Resources</p>
        <ul class="space-y-1">
          <?php wp_nav_menu([
            'theme_location' => 'resources_menu',
            'container' => false,
            'items_wrap' => '%3$s',
            'walker' => $shared_walker,
          ]); ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if (has_nav_menu('connect_menu')): ?>
      <div class="m-0">
        <p class="pb-2 pl-3.5 pt-5 text-[11.6px] font-[480] text-gray11 transition duration-150 ease-in-out">Connect</p>
        <ul class="space-y-1">
          <?php wp_nav_menu([
            'theme_location' => 'connect_menu',
            'container' => false,
            'items_wrap' => '%3$s',
            'walker' => $shared_walker,
          ]); ?>
        </ul>
      </div>
    <?php endif; ?>

  </nav>

  <div class="mb-2 px-3.5">
    <div class="flex items-center justify-between rounded-lg bg-tabBg p-1 dark:bg-tabBg">
      <button
        class="theme-toggle-item flex flex-1 cursor-pointer justify-center rounded-md py-1.5 transition-all duration-200"
        data-theme="light" title="Light Mode">
        <span class="iconify h-4 w-4" data-icon="heroicons:sun"></span>
      </button>
      <button
        class="theme-toggle-item flex flex-1 cursor-pointer justify-center rounded-md py-1.5 transition-all duration-200"
        data-theme="dark" title="Dark Mode">
        <span class="iconify h-4 w-4" data-icon="heroicons:moon"></span>
      </button>
      <button
        class="theme-toggle-item flex flex-1 cursor-pointer justify-center rounded-md py-1.5 transition-all duration-200"
        data-theme="auto" title="System Theme">
        <span class="iconify h-4 w-4" data-icon="heroicons:computer-desktop"></span>
      </button>
    </div>
  </div>
</aside>

<nav
  class="fixed bottom-0 top-auto z-10 flex h-[60px] w-full shrink-0 animate-[slideUp_0.15s_ease-out_forwards] flex-row items-center justify-between overflow-x-scroll overflow-y-hidden border-t border-linkContainer bg-sidebarBg p-2 transition-all duration-500 ease-in-out md:hidden">
  <ul class="flex flex-row items-center space-x-1">
    <?php
    $menus = ['primary_menu', 'resources_menu', 'connect_menu'];
    foreach ($menus as $menu) {
      if (has_nav_menu($menu)) {
        wp_nav_menu([
          'theme_location' => $menu,
          'container' => false,
          'items_wrap' => '%3$s',
          'walker' => $shared_walker,
        ]);
      }
    }
    ?>
  </ul>

  <div class="ml-4 flex shrink-0 items-center rounded-lg bg-tabBg p-1 dark:bg-tabBg">
    <button class="theme-toggle-item rounded-md px-2.5 py-1.5 transition-all duration-200" data-theme="light">
      <span class="iconify h-4 w-4" data-icon="heroicons:sun"></span>
    </button>
    <button class="theme-toggle-item rounded-md px-2.5 py-1.5 transition-all duration-200" data-theme="dark">
      <span class="iconify h-4 w-4" data-icon="heroicons:moon"></span>
    </button>
    <button class="theme-toggle-item rounded-md px-2.5 py-1.5 transition-all duration-200" data-theme="auto">
      <span class="iconify h-4 w-4" data-icon="heroicons:computer-desktop"></span>
    </button>
  </div>
</nav>