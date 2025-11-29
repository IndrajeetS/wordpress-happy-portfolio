jQuery(document).ready(function ($) {
  const ICONIFY_API = "https://api.iconify.design/search?query=";

  // Open modal
  $(document).on("click", ".select-icon-button", function () {
    const input = $(this).siblings(".menu-item-icon-input");
    const preview = $(this).siblings(".iconify-preview");

    const modal = $(`
      <div class="icon-picker-modal">
        <div class="icon-picker-content">
          <div class="icon-picker-header">
            <input type="text" class="icon-picker-search" placeholder="Search icons (e.g. user, home)">
            <button type="button" class="button close-modal">×</button>
          </div>
          <div class="icon-picker-grid"><p style="text-align:center;">Start typing to search icons...</p></div>
        </div>
      </div>
    `);

    const grid = modal.find(".icon-picker-grid");

    async function fetchIcons(query) {
      grid.html("<p style='text-align:center;'>Searching...</p>");
      try {
        const res = await fetch(`${ICONIFY_API}${encodeURIComponent(query)}`);
        const data = await res.json();

        if (!data?.icons?.length) {
          grid.html("<p style='text-align:center;'>No icons found</p>");
          return;
        }

        grid.empty();
        data.icons.slice(0, 100).forEach((icon) => {
          grid.append(`<span class="iconify" data-icon="${icon}" title="${icon}"></span>`);
        });
      } catch (err) {
        grid.html("<p style='text-align:center;color:red;'>Error fetching icons</p>");
      }
    }

    modal.find(".icon-picker-search").on("input", function () {
      const query = $(this).val().trim();
      if (query.length > 1) fetchIcons(query);
    });

    modal.on("click", ".iconify", function () {
      const selected = $(this).attr("data-icon");
      input.val(selected);
      preview.attr("data-icon", selected);
      modal.remove();
    });

    modal.on("click", ".close-modal", () => modal.remove());
    modal.on("click", (e) => {
      if ($(e.target).hasClass("icon-picker-modal")) modal.remove();
    });

    $("body").append(modal);
  });
});
