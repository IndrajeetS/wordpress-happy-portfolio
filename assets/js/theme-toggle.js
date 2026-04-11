/**
 * Theme Toggle Functionality
 * Handles Light, Dark, and Auto (system) themes.
 */
document.addEventListener("DOMContentLoaded", () => {
  const themeToggleItems = document.querySelectorAll(".theme-toggle-item");
  const html = document.documentElement;

  /**
   * Applies the selected theme and updates the UI
   * @param {string} theme - 'light', 'dark', or 'auto'
   */
  const applyTheme = (theme) => {
    // 1. Determine actual class to apply
    let shouldBeDark = false;
    if (theme === "dark") {
      shouldBeDark = true;
    } else if (theme === "auto") {
      shouldBeDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
    }

    // 2. Apply to HTML element
    if (shouldBeDark) {
      html.classList.add("dark");
    } else {
      html.classList.remove("dark");
    }

    // 3. Update UI switcher state
    themeToggleItems.forEach((item) => {
      const isActive = item.dataset.theme === theme;
      if (isActive) {
        item.classList.add(
          "bg-activeTabBg",
          "dark:bg-activeTabBg",
          "text-tabText",
          "shadow-sm",
        );
        item.classList.remove("text-tabText", "hover:text-tabText");
      } else {
        item.classList.remove(
          "bg-activeTabBg",
          "dark:bg-activeTabBg",
          "text-tabText",
          "shadow-sm",
        );
        item.classList.add("text-tabText", "hover:text-tabText");
      }
    });

    // 4. Persist setting
    localStorage.setItem("theme", theme);
  };

  // --- INITIALIZATION ---
  const savedTheme = localStorage.getItem("theme") || "auto";
  applyTheme(savedTheme);

  // --- EVENT LISTENERS ---
  themeToggleItems.forEach((item) => {
    item.addEventListener("click", (e) => {
      e.preventDefault();
      applyTheme(item.dataset.theme);
    });
  });

  // --- SYSTEM PREFERENCE CHANGE LISTENER ---
  window
    .matchMedia("(prefers-color-scheme: dark)")
    .addEventListener("change", (e) => {
      if (localStorage.getItem("theme") === "auto") {
        if (e.matches) {
          html.classList.add("dark");
        } else {
          html.classList.remove("dark");
        }
      }
    });
});
