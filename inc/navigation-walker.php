
<?php
/**
 * Button_Walker_Nav_Menu
 * AdamDurrant-style flat tabs with NO layout shift
 * Applies your Tailwind active/inactive rules EXACTLY
 */

if (!class_exists('Button_Walker_Nav_Menu')) {

  class Button_Walker_Nav_Menu extends Walker_Nav_Menu {

    private static $counter = 1;

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {

      $page = sanitize_title($item->title);
      $icon = get_post_meta($item->ID, '_menu_item_icon', true);
      $url  = $item->url;

      // Cycle 1–9,0
      $num = self::$counter % 10;
      self::$counter++;

      $output .= '<li class="mt-0.5">';

      $output .= '<button
          data-page="' . esc_attr($page) . '"
          data-href="' . esc_url($url) . '"
          class="nav-item cursor-pointer w-full text-left touch-manipulation
                 transition-colors duration-100 ease-linear"
        >';

      $output .= $args->link_before;

      // ⚡ FINAL STABLE ADAMDURRANT STYLE
      // Fully matches your Tailwind equivalents for inactive and active states
      $output .= '
      <span class="
          menu-wrapper flex flex-row justify-between items-center p-3
          md:px-[0.575rem] md:pl-[0.85rem] md:py-2
          w-full h-9
          box-border rounded-lg mt-0.5
          border-[0.5px] border-transparent
          group-[.active]:bg-(--highlight)
          group-[.active]:border-[0.5px] group-[.active]:border-(--highlightActiveBorder)
          group-[.active]:shadow-[0_3px_3px_rgba(0,0,0,0.05)]

          transition-colors duration-100
        ">
      ';

      // LEFT ITEM
      $output .= '<span class="flex items-center gap-2">';
      if (!empty($icon)) {
        $output .= '<span class="iconify text-lg text-gray-700"
                      data-icon="' . esc_attr($icon) . '"></span>';
      }
      $output .= '<span class="hidden md:inline text-sm">' . esc_html($item->title) . '</span>';
      $output .= '</span>';

      // RIGHT NUMBER
      $output .= '
        <span class="hidden md:flex text-xs font-mono text-gray-500 bg-gray-100
                     h-4 w-4 items-center justify-center rounded-sm">
            ' . $num . '
        </span>
      ';

      $output .= '</span>';
      $output .= $args->link_after;
      $output .= '</button></li>';
    }
  }
}

$shared_walker = new Button_Walker_Nav_Menu();
?>
