/**
 * main.js - Optimized SPA router for Happy Portfolio
 * - Preserves server-rendered initial page (no double fetch on load)
 * - Handles nav button clicks, internal links, tab initialization, popstate
 * - Re-initializes lightweight modules after AJAX replacement
 * - Contact modal support included
 */

document.addEventListener('DOMContentLoaded', () => {
  SPA.init();
});

/* ===========================
   SPA MODULE (IIFE-style)
   =========================== */
const SPA = (function () {
  // ---- Constants & cached selectors ----
  const CONTENT_SELECTOR = 'main#content';
  const CONTENT_SCROLL_WRAPPER = '#content > div.overflow-y-scroll';
  const MUTIPAGE_ROOT = '#mutipage-content';
  const LOADER_ID = 'loader';
  const CONTACT_MODAL_ID = 'contact-modal';

  const SLUGS = {
    DEFAULT: 'home',
    BLOG_PATH: 'blog',
    BLOG_NAV: 'writing',
    TOOLS: 'tools',
    READING: 'reading',
    ABOUT: 'about',
    TWITTER: 'twitter',
    CONTACT: 'contact',
  };

  const KNOWN_URL_SEGMENTS = [
    SLUGS.DEFAULT, SLUGS.BLOG_PATH, SLUGS.TOOLS, SLUGS.READING, SLUGS.ABOUT,
    SLUGS.TWITTER, SLUGS.CONTACT
  ];

  // Cached DOM refs (populated on init and after content swap)
  let contentArea = null;
  let contentContainer = null;
  let navRoot = null; // container for nav items (if needed)
  let parser = new DOMParser();
  let lastPathLoaded = null;

  // ---- Utilities ----
  function query(sel) { return document.querySelector(sel); }
  function queryAll(sel) { return Array.from(document.querySelectorAll(sel)); }
  function byId(id) { return document.getElementById(id); }
  function updateContentRefs() {
    contentArea = query(CONTENT_SELECTOR);
    contentContainer = query(CONTENT_SCROLL_WRAPPER);
    navRoot = query('nav') || null;
  }

  function fadeOutContainer() {
    if (contentContainer) contentContainer.classList.remove('opacity-100'), contentContainer.classList.add('opacity-0');
  }
  function fadeInContainer() {
    // two rAFs for reliable paint before adding opacity class
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        if (contentContainer) contentContainer.classList.remove('opacity-0'), contentContainer.classList.add('opacity-100');
      });
    });
  }

  function getCurrentPageSlugFromPath(pathname) {
    const clean = pathname.replace(/^\/|\/$/g, '');
    const seg = clean.split('/')[0] || SLUGS.DEFAULT;
    switch (seg) {
      case SLUGS.BLOG_PATH: return SLUGS.BLOG_NAV;
      case SLUGS.TOOLS: return SLUGS.TOOLS;
      case SLUGS.READING: return SLUGS.READING;
      case SLUGS.ABOUT: return SLUGS.ABOUT;
      default: return seg === '' ? SLUGS.DEFAULT : seg;
    }
  }

  function getCurrentFullPath() {
    return window.location.pathname + window.location.search + window.location.hash;
  }

  function setActiveButton(activeSlug) {
    queryAll('.nav-item[data-page]').forEach(btn => {
      const btnSlug = btn.getAttribute('data-page');
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

  function safeCall(fn) {
    if (typeof fn === 'function') {
      try { fn(); } catch (e) {
        // console.warn('Module init threw:', e);
      }
    }
  }

  function initializePageContent(isAjax = false) {
    safeCall(window.getLocalTimeBasedGreeting);

    const multiRoot = query(MUTIPAGE_ROOT);
    if (multiRoot && multiRoot.querySelector('.wedo-tab-btn') && typeof window.wedoInitTabs === 'function') {
      setTimeout(() => safeCall(() => window.wedoInitTabs(isAjax)), 40);
    }

    safeCall(window.wedoAfterAjaxInit);
  }

  // ---- Contact modal helpers ----
  function showContactPopup() {
    const modal = byId(CONTACT_MODAL_ID);
    if (modal) modal.classList.remove('hidden'), modal.classList.add('flex');
  }
  function hideContactPopup() {
    const modal = byId(CONTACT_MODAL_ID);
    if (modal) modal.classList.remove('flex'), modal.classList.add('hidden');
  }
  function initContactModal() {
    const contactModal = byId(CONTACT_MODAL_ID);
    if (!contactModal) return;

    contactModal.classList.remove('flex'); contactModal.classList.add('hidden');

    if (typeof jQuery !== 'undefined') {
      jQuery(document).on('click', '#close-contact-modal, #contact-modal', function (e) {
        if (e.target.id === 'contact-modal' || jQuery(e.target).closest('#close-contact-modal').length) {
          e.preventDefault(); e.stopPropagation(); hideContactPopup();
        }
      });
    } else {
      const closeModalButton = byId('close-contact-modal');
      if (closeModalButton) closeModalButton.addEventListener('click', hideContactPopup);
      contactModal.addEventListener('click', function (e) {
        if (e.target === contactModal) hideContactPopup();
      });
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !contactModal.classList.contains('hidden')) {
        hideContactPopup();
      }
    });
  }

  // ---- Core AJAX loader ----
  async function loadPage(pageSlug, fullPath, updateHistory = true) {
    if (pageSlug === SLUGS.TWITTER || pageSlug === SLUGS.CONTACT) return;

    pageSlug = pageSlug === '' ? SLUGS.DEFAULT : pageSlug;
    if (lastPathLoaded === fullPath) {
      setActiveButton(pageSlug);
      return;
    }
    lastPathLoaded = fullPath;

    fadeOutContainer();

    const fetchPath = fullPath.split('#')[0];
    const fetchUrl = `${fetchPath}${fetchPath.includes('?') ? '&' : '?'}ajax=1`;

    const loader = byId(LOADER_ID);
    if (loader) loader.classList.remove('hidden');

    try {
      const res = await fetch(fetchUrl, { credentials: 'same-origin' });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const html = await res.text();

      const doc = parser.parseFromString(html, 'text/html');
      const fetchedMain = doc.querySelector(CONTENT_SELECTOR);
      const newContentHTML = fetchedMain ? fetchedMain.innerHTML : html;

      if (!contentArea) updateContentRefs();
      contentArea.innerHTML = newContentHTML;

      loader?.remove?.();

      updateContentRefs();
      initializePageContent(true);
      fadeInContainer();

      if (updateHistory) {
        history.pushState({ page: pageSlug, path: fullPath }, '', fullPath);
      }

      const hash = fullPath.includes('#') ? fullPath.split('#')[1] : null;
      if (hash) {
        requestAnimationFrame(() => {
          document.getElementById(hash)?.scrollIntoView({ behavior: 'smooth' });
        });
      } else contentArea.scrollTo(0, 0);

      setActiveButton(pageSlug);

    } catch (err) {
      loader?.remove?.();
      if (contentArea) contentArea.innerHTML = '<p class="text-red-500 p-6">Error loading page.</p>';
      setActiveButton(pageSlug);
    }
  }

  /* ============================================================
     🔥 UPDATED CLICK HANDLER — NOW SUPPORTS BUTTON.nav-item
     ============================================================ */
  function onDocumentClick(e) {
    /* ---- 1) BUTTON NAV ITEM CLICK (PRIMARY FIX) ---- */
    const buttonNav = e.target.closest('button.nav-item');
    if (buttonNav) {
      e.preventDefault();

      const pageSlug = buttonNav.getAttribute('data-page');
      const href = buttonNav.getAttribute('data-href');

      if (pageSlug === SLUGS.TWITTER) {
        window.open(href, "_blank");
        return;
      }
      if (pageSlug === SLUGS.CONTACT) {
        showContactPopup();
        return;
      }

      const fullPath = href.startsWith('/') ? href : new URL(href, window.location.origin).pathname;

      loadPage(pageSlug, fullPath);
      return;
    }

    /* ---- 2) NORMAL ANCHOR HANDLING ---- */
    const anchor = e.target.closest('a');
    if (!anchor || !anchor.href) return;

    let url;
    try { url = new URL(anchor.href); } catch (e) { return; }

    if (url.origin !== window.location.origin || anchor.target === '_blank' || anchor.hasAttribute('download')) {
      return;
    }

    e.preventDefault();

    let newPath = url.pathname + url.search + url.hash;
    const firstSegment = url.pathname.replace(/^\/|\/$/g, '').split('/')[0];

    let pageSlug;
    if (firstSegment === SLUGS.BLOG_PATH) pageSlug = SLUGS.BLOG_NAV;
    else if (firstSegment === SLUGS.TOOLS) pageSlug = SLUGS.TOOLS;
    else if (firstSegment === SLUGS.READING) pageSlug = SLUGS.READING;
    else if (firstSegment === SLUGS.ABOUT) pageSlug = SLUGS.ABOUT;
    else if (firstSegment && !KNOWN_URL_SEGMENTS.includes(firstSegment)) {
      newPath = `/${SLUGS.BLOG_PATH}${url.pathname}${url.search}${url.hash}`;
      pageSlug = SLUGS.BLOG_NAV;
    } else pageSlug = firstSegment || SLUGS.DEFAULT;

    loadPage(pageSlug, newPath);
  }

  function onPopState(e) {
    const slug = getCurrentPageSlugFromPath(window.location.pathname);
    const fullPath = getCurrentFullPath();
    loadPage(slug, fullPath, false);
  }

  // ---- Initial setup ----
  function init() {
    updateContentRefs();

    const initialSlug = getCurrentPageSlugFromPath(window.location.pathname);
    setActiveButton(initialSlug);

    initializePageContent(false);
    document.addEventListener('click', onDocumentClick, { passive: false });
    window.addEventListener('popstate', onPopState);
    initContactModal();
  }

  // Expose public API
  return {
    init,
    loadPage,
    setActiveButton
  };
})();
