/**
 * wedo-custom.js (production-ready SPA-friendly)
 *
 * Exports:
 * - window.wedoInitTabs()  -> idempotent initializer you can call from main.js
 * - window.wedoLoadTools() -> load items programmatically
 */

(function () {
  // --- safety: ensure wedoAjax exists later when used, but don't crash now
  if (typeof wedoAjax === 'undefined' || !wedoAjax.ajaxurl) {
    console.warn('⚠️ wedo-custom: wedoAjax.ajaxurl not found. Ensure wp_localize_script provides it.');
  }

  // internal state
  let lastLoadTs = 0;
  const DEBOUNCE_MS = 200;

  // track whether the module finished initial setup (DOM hooks bound)
  let moduleReady = false;

  // --- Helpers ---
  function qs(selector, ctx) {
    return (ctx || document).querySelector(selector);
  }
  function qsa(selector, ctx) {
    return Array.from((ctx || document).querySelectorAll(selector));
  }

  function safeTrimPath(path) {
    // remove trailing slash only (keep root '/')
    return path.replace(/\/$/, '') || '/';
  }

  // --- SVG Manipulation for Favourite Dot (NEW FUNCTION) ---
  function toggleFavouriteDot(btn, show) {
    const svg = btn ? btn.querySelector('svg') : null;
    if (!svg) return;

    const dotId = 'favourite-active-dot';
    let existingDot = svg.querySelector(`#${dotId}`);

    if (show) {
      if (!existingDot) {
        // Create the new circle element
        const dot = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        dot.setAttribute('id', dotId);
        dot.setAttribute('cx', '20');
        dot.setAttribute('cy', '6');
        dot.setAttribute('r', '3');
        // Use a distinct color for the active dot (e.g., black or red)
        dot.setAttribute('fill', '#000000'); // Black for high visibility

        // Append it to the SVG
        svg.appendChild(dot);
      }
    } else {
      if (existingDot) {
        existingDot.remove();
      }
    }
  }
  // --------------------------------------------------------

  // --- AJAX loader ---
  async function loadTools(term = 'all') {
    const root = qs('#mutipage-content');
    const container = qs('#tools-list');

    if (!root || !container) {
      console.warn('wedo-custom: loadTools aborted — #mutipage-content or #tools-list missing');
      return;
    }

    const taxonomy = root.dataset.taxonomy || 'reading_list_category';
    const post_type = root.dataset.posttype || 'reading_list';
    const item_part = root.dataset.itempart || '';

    // debounce
    const now = Date.now();
    if (now - lastLoadTs < DEBOUNCE_MS) {
      // console.log('wedo-custom: loadTools debounced');
      return;
    }
    lastLoadTs = now;

    try {
      const params = new URLSearchParams({
        action: 'filter_resources',
        term: term,
        taxonomy: taxonomy,
        post_type: post_type // This should be 'reading_list' or 'resource_tools'
      });
      if (item_part) params.set('item_part', item_part);

      // DEBUG LOG: Verify parameters being sent
      console.log(`✅ AJAX Load: Post Type='${post_type}', Item Part='${item_part}', Term='${term}'`);

      const url = `${(wedoAjax && wedoAjax.ajaxurl) ? wedoAjax.ajaxurl : '/wp-admin/admin-ajax.php'}?${params.toString()}`;

      const res = await fetch(url, { credentials: 'same-origin' });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const html = await res.text();

      // Inject
      container.innerHTML = html;
    } catch (err) {
      console.error('wedo-custom: loadTools error', err);
      const containerFallback = container || document.body;
      containerFallback.innerHTML = `<p class="text-red-500">Error loading items.</p>`;
    }
  }

  // expose loadTools to window (safe)
  window.wedoLoadTools = loadTools;

  // --- visual state for tabs ---
  function clearTabActiveState(root) {
    // FIX: Use .wedo-tab-btn
    qsa('.wedo-tab-btn', root).forEach(t => {
      t.classList.remove('active', 'text-gray12');
      t.classList.add('text-gray10');
      t.style.borderBottomColor = 'transparent';

      // NEW: Clear the dot on all tabs
      if (t.dataset.term === 'favourite') {
        toggleFavouriteDot(t, false);
      }
    });
  }
  function setActiveTabElement(btn) {
    if (!btn) return;
    const root = btn.closest('#mutipage-content') || document;
    clearTabActiveState(root);
    btn.classList.remove('text-gray10');
    btn.classList.add('active', 'text-gray12');
    btn.style.borderBottomColor = '#171717';

    // NEW: Add the dot if the active tab is 'favourite'
    if (btn.dataset.term === 'favourite') {
      toggleFavouriteDot(btn, true);
    }
  }

  // --- delegated click handler ---
  function onDelegatedClick(e) {
    // FIX: Use .wedo-tab-btn
    const btn = e.target.closest && e.target.closest('.wedo-tab-btn');
    if (!btn) return;

    e.preventDefault();
    e.stopPropagation();

    const term = btn.dataset.term || 'all';

    // if already active, skip (but still ensure styles)
    if (btn.classList.contains('active')) {
      setActiveTabElement(btn);
      return;
    }

    setActiveTabElement(btn);
    loadTools(term);

    // Update URL without breaking path formatting
    const base = safeTrimPath(window.location.pathname);
    const newUrl = `${base}?filter=${encodeURIComponent(term)}`;
    try {
      history.replaceState({}, '', newUrl);
    } catch (e) {
      // ignore (older browsers)
    }
  }

  // --- initializer (idempotent) ---
  function initWedoTabsActual(isAjaxLoad = false) {
    const root = qs('#mutipage-content');
    const container = qs('#tools-list');

    if (!root || !container) {
      moduleReady = true;
      return;
    }

    // remove prior listener (safe)
    root.removeEventListener('click', onDelegatedClick, true);
    // add delegated listener in capture phase to beat other handlers if needed
    root.addEventListener('click', onDelegatedClick, true);

    // Restore initial state from URL or pick first tab
    const params = new URLSearchParams(window.location.search);
    const initial = params.get('filter') || 'all';

    // FIX: Use .wedo-tab-btn
    const initialBtn = root.querySelector(`.wedo-tab-btn[data-term="${initial}"]`) || root.querySelector('.wedo-tab-btn');
    if (initialBtn) {
      // set visual state
      setActiveTabElement(initialBtn);

      // CRITICAL: Since PHP server-side query is removed, container is always empty on AJAX/initial load.
      // We must load tools every time the page loads/reloads.
      console.log(`🔄 wedo-custom: Loading initial items for term: ${initial}`);
      loadTools(initialBtn.dataset.term || 'all');
    }

    moduleReady = true;
  }

  // expose init on window early
  if (typeof window.wedoInitTabs !== 'function') {
    window.wedoInitTabs = function (isAjaxCall = false) {
      // If module is ready, call actual init immediately.
      if (moduleReady) {
        try { initWedoTabsActual(isAjaxCall); } catch (e) { console.error('wedo-custom.init error', e); }
      } else {
        // If not ready yet, schedule a short retry.
        setTimeout(() => {
          try { initWedoTabsActual(isAjaxCall); } catch (e) { console.error('wedo-custom.init error', e); }
        }, 40);
      }
    };
  } else {
    // if already defined by another script, override to our safe wrapper
    const prev = window.wedoInitTabs;
    window.wedoInitTabs = function (isAjaxCall = false) {
      setTimeout(() => {
        try { initWedoTabsActual(isAjaxCall); } catch (e) { console.error('wedo-custom.init error', e); }
      }, 0);
      try { if (typeof prev === 'function') prev(isAjaxCall); } catch (e) { /* noop */ }
    };
  }

  // also expose loadTools (again, safe)
  window.wedoLoadTools = loadTools;

  // Auto-run shortly after load to bind listeners if content exists.
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      try { initWedoTabsActual(); } catch (e) { console.error('wedo-custom DOM init error', e); }
    });
  } else {
    try { initWedoTabsActual(); } catch (e) { console.error('wedo-custom init error', e); }
  }

  // final log
  console.log('wedo-custom: module loaded and ready (SPA-friendly)');
})();
