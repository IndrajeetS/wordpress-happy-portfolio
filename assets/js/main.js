document.addEventListener('DOMContentLoaded', function () {

  // ===================================
  // Configuration and Constants
  // ===================================

  const contentArea = document.querySelector('main#content');
  const NAV_BUTTON_SELECTOR = '.nav-item';
  const navButtons = document.querySelectorAll(NAV_BUTTON_SELECTOR);

  // Define all primary routing slugs used in data-page attributes and URLs
  const SLUGS = {
    DEFAULT: 'home',
    BLOG_PATH: 'blog',
    BLOG_NAV: 'writing', // data-page="writing"
    TOOLS: 'tools',
    READING: 'reading',
    ABOUT: 'about',
    TWITTER: 'twitter',
    CONTACT: 'contact',
  };

  // List of all known root-level URL segments (used to prevent bad rewriting)
  const KNOWN_URL_SEGMENTS = [
    SLUGS.DEFAULT, SLUGS.BLOG_PATH, SLUGS.TOOLS, SLUGS.READING, SLUGS.ABOUT,
    SLUGS.TWITTER, SLUGS.CONTACT
  ];

  // ===================================
  // Helper Functions
  // ===================================

  function showContactPopup() {
    const modal = document.getElementById('contact-modal');
    if (modal) {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }
  }

  function hideContactPopup() {
    const modal = document.getElementById('contact-modal');
    if (modal) {
      modal.classList.remove('flex');
      modal.classList.add('hidden');
    }
  }

  /**
   * Maps the current URL path to the corresponding data-page slug for navigation highlighting.
   * e.g., /blog/post-name/ -> 'writing', /about/ -> 'about', / -> 'home'
   * @returns {string} The active slug (e.g., 'writing', 'about', 'home').
   */
  function getCurrentPageSlug() {
    const path = window.location.pathname.replace(/^\/|\/$/g, '');
    const segments = path.split('/');
    const firstSegment = segments.length > 0 && segments[0] ? segments[0] : SLUGS.DEFAULT;

    switch (firstSegment) {
      case SLUGS.BLOG_PATH:
        return SLUGS.BLOG_NAV;
      case SLUGS.TOOLS:
      case SLUGS.READING:
      case SLUGS.ABOUT:
        return firstSegment;
      default:
        return firstSegment === '' ? SLUGS.DEFAULT : firstSegment;
    }
  }

  /**
   * Gets the full path including search parameters and hash.
   * @returns {string} The full path string.
   */
  function getCurrentFullPath() {
    // FIX: Include the hash when getting the full path
    return window.location.pathname + window.location.search + window.location.hash;
  }

  /**
   * Sets the active visual state for the corresponding navigation button.
   * @param {string} activeSlug The data-page slug to highlight.
   */
  function setActiveButton(activeSlug) {
    navButtons.forEach(btn => {
      const btnSlug = btn.getAttribute('data-page');

      // Skip utility buttons
      if (btnSlug === SLUGS.TWITTER || btnSlug === SLUGS.CONTACT) {
        return;
      }

      const innerSpan = btn.querySelector('span.flex');
      if (!innerSpan) return;

      if (btnSlug === activeSlug) {
        innerSpan.classList.add("bg-menuHighlight", "text-menuLabel", "shadow-sm", "border", "border-menuActiveBorder");
        innerSpan.classList.remove("hover:bg-gray-200", "border-transparent");
      } else {
        innerSpan.classList.remove("bg-menuHighlight", "text-menuLabel", "shadow-sm", "border", "border-menuActiveBorder");
        innerSpan.classList.add("hover:bg-gray-200", "border-transparent");
      }
    });
  }

  // ===================================
  // Core Routing Logic
  // ===================================

  /**
   * Loads content via AJAX and updates the DOM and History API.
   * @param {string} pageSlug The slug used for navigation highlighting (e.g., 'writing').
   * @param {string} fullPath The full URL path to fetch (e.g., '/blog/post-name#anchor').
   * @param {boolean} updateHistory Whether to push a new state to the history stack.
   */
  async function loadPage(pageSlug, fullPath, updateHistory = true) {
    const isAjaxCall = updateHistory;

    // Skip utility pages
    if (pageSlug === SLUGS.TWITTER || pageSlug === SLUGS.CONTACT) {
      return;
    }
    pageSlug = pageSlug === '' ? SLUGS.DEFAULT : pageSlug;

    // Fade out current content
    let contentContainer = document.querySelector('#content > div.overflow-y-scroll');
    if (contentContainer) {
      contentContainer.classList.remove('opacity-100');
      contentContainer.classList.add('opacity-0');
    }

    // Determine the path to fetch (strip hash for AJAX request but keep search params)
    const fetchPath = fullPath.split('#')[0];
    const fetchUrl = `${fetchPath}${fetchPath.includes('?') ? '&' : '?'}ajax=1`;

    try {
      const response = await fetch(fetchUrl, { credentials: 'same-origin' });
      if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

      const fullHTML = await response.text();
      const parser = new DOMParser();
      const doc = parser.parseFromString(fullHTML, 'text/html');
      const fetchedMain = doc.querySelector('main#content');
      const newContentHTML = fetchedMain ? fetchedMain.innerHTML : fullHTML;

      // UX Delay for transition
      setTimeout(() => {
        document.getElementById('loader')?.remove();

        if (!contentArea) {
          console.error('❌ contentArea (main#content) not found in current document.');
          return;
        }

        // Inject new content
        contentArea.innerHTML = newContentHTML;

        // Re-query dynamic containers
        contentContainer = document.querySelector('#content > div.overflow-y-scroll');

        // Re-run initializers (e.g., greeting)
        if (typeof window.getLocalTimeBasedGreeting === 'function') {
          try {
            window.getLocalTimeBasedGreeting();
          } catch (e) {
            console.warn("getLocalTimeBasedGreeting threw:", e);
          }
        }

        // Fade content back in
        if (contentContainer) {
          contentContainer.classList.remove('opacity-0');
          contentContainer.classList.add('opacity-100');
        }

        // Initialize tabs if present
        const multiRoot = document.querySelector('#mutipage-content');
        if (multiRoot && multiRoot.querySelector('.wedo-tab-btn')) {
          if (typeof window.wedoInitTabs === 'function') {
            setTimeout(() => {
              try {
                window.wedoInitTabs(isAjaxCall);
              } catch (e) {
                console.error("Error while calling wedoInitTabs:", e);
              }
            }, 40);
          } else {
            console.warn("❌ window.wedoInitTabs not found");
          }
        }

        // Update history & scroll
        if (updateHistory) {
          try {
            // FIX: Use fullPath (which includes hash) for history.pushState
            history.pushState({ page: pageSlug, path: fullPath }, '', fullPath);

            // Handle scrolling to the anchor if a hash is present
            const hash = fullPath.includes('#') ? fullPath.substring(fullPath.indexOf('#') + 1) : null;
            if (hash) {
              document.getElementById(hash)?.scrollIntoView({ behavior: 'smooth' });
            } else {
              // Scroll to top of the content area for a new page load
              contentArea.scrollTo(0, 0);
            }

          } catch (e) {
            console.warn("History pushState failed:", e);
          }
        }

        // FIX: Ensure active button is set after successful load/state update
        setActiveButton(pageSlug);

      }, 240); // UX delay

    } catch (err) {
      console.error("Error loading page:", err);
      document.getElementById('loader')?.remove();
      if (contentArea) {
        contentArea.innerHTML = '<p class="text-red-500 p-6">Error loading page content.</p>';
      }
      setActiveButton(pageSlug);
    }
  }

  // ===================================
  // Event Listeners
  // ===================================

  // ----------------------------
  // 1. Navigation click handlers (Sidebar)
  // ----------------------------
  navButtons.forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      const pageSlug = this.getAttribute('data-page');
      const href = this.getAttribute('data-href');

      if (pageSlug === SLUGS.TWITTER && href) {
        window.open(href, '_blank');
        return;
      } else if (pageSlug === SLUGS.CONTACT) {
        showContactPopup();
        return;
      }

      let newPath;
      if (pageSlug === SLUGS.BLOG_NAV) {
        // Map 'writing' nav slug back to '/blog/' URL path
        newPath = `/${SLUGS.BLOG_PATH}/`;
      } else {
        // Use other slugs directly for path (e.g., /tools/, /about/, /home/)
        newPath = (pageSlug === SLUGS.DEFAULT) ? '/' : `/${pageSlug}/`;
      }

      loadPage(pageSlug, newPath);
    });
  });

  // ----------------------------
  // 2. Initial load (on page open)
  // ----------------------------
  const initialPageSlug = getCurrentPageSlug();
  const initialFullPath = getCurrentFullPath();
  if (initialPageSlug !== SLUGS.TWITTER && initialPageSlug !== SLUGS.CONTACT) {
    // Initial load: load content but do not push new state
    loadPage(initialPageSlug, initialFullPath, false);
  }

  // ----------------------------
  // 3. popstate (back/forward)
  // ----------------------------
  window.addEventListener('popstate', function () {
    const slugFromURL = getCurrentPageSlug();
    const fullPathFromURL = getCurrentFullPath();
    if (slugFromURL !== SLUGS.TWITTER && slugFromURL !== SLUGS.CONTACT) {
      // Re-load content based on new history state, do not push state
      loadPage(slugFromURL, fullPathFromURL, false);
    }
  });

  // ----------------------------
  // 4. Universal Link Interception (Internal Links)
  // ----------------------------
  document.addEventListener('click', function (e) {
    const target = e.target.closest('a');

    if (target && target.href) {
      const url = new URL(target.href);

      // Check if it's an internal link
      if (url.origin === window.location.origin && !target.hasAttribute('download') && target.target !== '_blank') {

        e.preventDefault();

        // New path includes pathname, search, and hash
        let newPath = url.pathname + url.search + url.hash;
        let pageSlug = getCurrentPageSlug(); // Slug based on current URL structure

        const pathSegments = url.pathname.replace(/^\/|\/$/g, '').split('/');
        const firstSegment = pathSegments[0];

        // 🚨 CRITICAL REWRITE LOGIC 🚨
        // Only rewrite if the segment is NOT a known root-level URL segment.
        if (firstSegment && !KNOWN_URL_SEGMENTS.includes(firstSegment) && firstSegment.length > 0) {
          // Assume single post or orphaned page that should be routed under /blog/

          // Rewrite path to use the blog prefix
          newPath = `/${SLUGS.BLOG_PATH}${url.pathname}${url.search}${url.hash}`;

          // Set active navigation to 'writing'
          pageSlug = SLUGS.BLOG_NAV;
        }

        // After potential rewrite, update pageSlug based on the final path if it's an archive page.
        if (firstSegment === SLUGS.BLOG_PATH) {
          pageSlug = SLUGS.BLOG_NAV;
        } else if (firstSegment === SLUGS.TOOLS) {
          pageSlug = SLUGS.TOOLS;
        } else if (firstSegment === SLUGS.READING) {
          pageSlug = SLUGS.READING;
        } else if (firstSegment === SLUGS.ABOUT) {
          pageSlug = SLUGS.ABOUT;
        }


        const currentPath = getCurrentFullPath();

        if (currentPath !== newPath) {
          // console.info(`🔗 Internal link click intercepted. New path: ${newPath}, Active Slug: ${pageSlug}`);
          loadPage(pageSlug, newPath);
        } else if (url.hash && window.location.hash !== url.hash) {
          // Handle case where path is the same, but only the hash changed (e.g., clicking View All #anchor on the same page)
          history.pushState({ page: pageSlug, path: newPath }, '', newPath);
          document.getElementById(url.hash.substring(1))?.scrollIntoView({ behavior: 'smooth' });
          setActiveButton(pageSlug);
        }
      }
    }
  });


  // ----------------------------
  // 5. Modal initialization (contact modal)
  // ----------------------------
  const contactModal = document.getElementById('contact-modal');
  if (contactModal) {
    contactModal.classList.remove('flex');
    contactModal.classList.add('hidden');

    // Use jQuery for simplified event handling if available
    if (typeof jQuery !== 'undefined') {
      jQuery(document).on('click', '#close-contact-modal, #contact-modal', function (e) {
        if (e.target.id === 'contact-modal' || jQuery(e.target).closest('#close-contact-modal').length) {
          e.preventDefault();
          e.stopPropagation();
          hideContactPopup();
        }
      });
    } else {
      // Fallback native JS
      const closeModalButton = document.getElementById('close-contact-modal');
      if (closeModalButton) closeModalButton.addEventListener('click', hideContactPopup);
      contactModal.addEventListener('click', function (e) {
        if (e.target === contactModal) hideContactPopup();
      });
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && contactModal && !contactModal.classList.contains('hidden')) {
        hideContactPopup();
      }
    });

  } else {
    console.warn('⚠️ Contact Modal element #contact-modal not found in DOM.');
  }

}); // end DOMContentLoaded
