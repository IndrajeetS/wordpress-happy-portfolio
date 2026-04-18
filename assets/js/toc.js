document.addEventListener("DOMContentLoaded", function () {
  const tocItems = document.querySelectorAll(".toc-item");
  const indicator = document.getElementById("toc-indicator");
  const tocList = document.querySelector(".toc-list");

  if (tocItems.length === 0 || !indicator || !tocList) return;

  // A flag to prevent the Observer from overriding the manual click
  let isScrollingByClick = false;
  let activeTargetId = null;

  const sections = Array.from(tocItems)
    .map((item) => {
      const id = item.getAttribute("data-target");
      return document.getElementById(id);
    })
    .filter(Boolean);

  function setActive(activeLink) {
    if (!activeLink) return;

    tocItems.forEach((item) => item.classList.remove("is-active"));
    activeLink.classList.add("is-active");

    const linkItem = activeLink.closest("li");
    if (linkItem) {
      const linkRect = linkItem.getBoundingClientRect();
      const listRect = tocList.getBoundingClientRect();
      const topPos = linkRect.top - listRect.top;

      indicator.style.transform = `translateY(${topPos}px)`;
      indicator.style.height = `${linkRect.height}px`;
      indicator.style.display = "block";
    }
  }

  // 1. Handle Clicks
  tocItems.forEach((item) => {
    item.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();
      e.stopImmediatePropagation();

      const targetId = item.getAttribute("data-target");
      const targetEl = document.getElementById(targetId);

      if (!targetEl) return;

      activeTargetId = targetId;
      setActive(item);

      // Smooth scroll manually
      targetEl.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    });
  });
  // tocItems.forEach((item) => {
  //   item.addEventListener("click", () => {
  //     isScrollingByClick = true; // LOCK: Stop Observer logic
  //     setActive(item);

  //     // UNLOCK: Re-enable Observer after the scroll animation usually finishes
  //     setTimeout(() => {
  //       isScrollingByClick = false;
  //     }, 800);
  //   });
  // });

  // 2. Intersection Observer
  // Adjusted rootMargin to be more generous for the "top" of the page
  const observerOptions = {
    rootMargin: "-5% 0px -75% 0px",
    threshold: 0,
  };

  // const observer = new IntersectionObserver((entries) => {
  //   // Only run if we aren't currently jumping via a link click
  //   if (isScrollingByClick) return;

  //   entries.forEach((entry) => {
  //     if (entry.isIntersecting) {
  //       const id = entry.target.getAttribute("id");
  //       const activeLink = document.querySelector(
  //         `.toc-item[data-target="${id}"]`,
  //       );
  //       setActive(activeLink);
  //     }
  //   });
  // }, observerOptions);

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;

      const id = entry.target.getAttribute("id");

      // If user clicked, only unlock when we reach that section
      if (activeTargetId) {
        if (id === activeTargetId) {
          activeTargetId = null; // unlock
        } else {
          return; // ignore other sections
        }
      }

      const activeLink = document.querySelector(
        `.toc-item[data-target="${id}"]`,
      );

      setActive(activeLink);
    });
  }, observerOptions);

  sections.forEach((section) => observer.observe(section));

  // 3. Initial state
  setTimeout(() => {
    const hash = window.location.hash.replace("#", "");
    const initialLink =
      document.querySelector(`.toc-item[data-target="${hash}"]`) || tocItems[0];
    setActive(initialLink);
  }, 200);
});
