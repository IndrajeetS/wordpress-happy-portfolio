<?php
$shared_walker = new Button_Walker_Nav_Menu();
?>

<!-- ============================= -->
<!-- DESKTOP SIDEBAR -->
<!-- ============================= -->
<aside class="absolute hidden md:flex flex-col justify-between m-2 w-58 opacity-0 md:opacity-100 animate-fadeIn h-[calc(100vh-1rem)] px-[0.15rem] items-stretch ">
  <nav class="space-y-4 p-1.5 -m-1.5">

    <div class="flex items-center mb-3 justify-center p-3">
      <div class="text-2xl font-bold text-gray12!">Happy</div>
    </div>

    <?php if (has_nav_menu('primary_menu')) : ?>
      <ul class="space-y-1 m-0!">
        <?php wp_nav_menu([
          'theme_location' => 'primary_menu',
          'container'      => false,
          'items_wrap'     => '%3$s',
          'walker'         => $shared_walker,
        ]); ?>
      </ul>
    <?php endif; ?>

    <?php if (has_nav_menu('resources_menu')) : ?>
      <div class="m-0!">
        <p class="pt-5 pb-2 pl-3.5 text-[11.6px] font-[480] text-gray11! transition duration-150 ease-in-out">Resources</p>
        <ul class="space-y-1">
          <?php wp_nav_menu([
            'theme_location' => 'resources_menu',
            'container'      => false,
            'items_wrap'     => '%3$s',
            'walker'         => $shared_walker,
          ]); ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if (has_nav_menu('connect_menu')) : ?>
      <div class="m-0!">
        <p class="pt-5 pb-2 pl-3.5 text-[11.6px] font-[480] text-gray11! transition duration-150 ease-in-out">Connect</p>
        <ul class="space-y-1">
          <?php wp_nav_menu([
            'theme_location' => 'connect_menu',
            'container'      => false,
            'items_wrap'     => '%3$s',
            'walker'         => $shared_walker,
          ]); ?>
        </ul>
      </div>
    <?php endif; ?>

  </nav>

  <div class="text-xs text-gray-400 flex justify-center py-2">
    © <?php echo date('Y'); ?> Happy
  </div>
</aside>

<!-- ============================= -->
<!-- MOBILE BOTTOM NAV -->
<!-- ============================= -->
<nav
  class="md:hidden p-2 w-full flex flex-row justify-between items-center shrink-0
         transition-all duration-500 ease-in-out transform
         overflow-x-scroll overflow-y-hidden fixed bg-gray2 bottom-0 h-[60px] top-auto
         border-t border-linkContainer bg-sidebarBg z-10 animate-slideUp">

  <ul class="flex flex-row space-x-1 items-center">
    <?php
    $menus = ['primary_menu', 'resources_menu', 'connect_menu'];
    foreach ($menus as $menu) {
      if (has_nav_menu($menu)) {
        wp_nav_menu([
          'theme_location' => $menu,
          'container'      => false,
          'items_wrap'     => '%3$s',
          'walker'         => $shared_walker,
        ]);
      }
    }
    ?>
  </ul>

  <div class="text-xs text-gray12 flex justify-center ml-3 whitespace-nowrap">
    © <?php echo date('Y'); ?> Happy
  </div>
</nav>

<!-- ============================= -->
<!-- TAILWIND ANIMATIONS -->
<!-- ============================= -->
<style>
@keyframes fadeIn {
  0% { opacity: 0; transform: translateX(-5px); }
  100% { opacity: 1; transform: translateX(0); }
}

@keyframes slideUp {
  0% { opacity: 0; transform: translateY(5px); }
  100% { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn { animation: fadeIn 0.15s ease-out forwards; }
.animate-slideUp { animation: slideUp 0.15s ease-out forwards; }
</style>
