// --- Lightbox / Image Carousel Logic ---
window.initLightbox = function () {
  const images = Array.from(document.querySelectorAll("#post-content img"));
  if (images.length === 0) return;

  const lightbox = document.getElementById("image-lightbox");
  const lightboxImg = document.getElementById("lightbox-image");
  const lightboxCounter = document.getElementById("lightbox-counter");
  const btnClose = document.getElementById("lightbox-close");
  const btnPrev = document.getElementById("lightbox-prev");
  const btnNext = document.getElementById("lightbox-next");
  const container = document.getElementById("lightbox-image-container");

  if (!lightbox) return;

  let currentIndex = 0;

  images.forEach((img, index) => {
    // Reset any existing listeners if possible, but adding again is usually okay if we check for init
    if (img.dataset.lightboxInit) return;
    img.dataset.lightboxInit = "true";

    img.style.cursor = "pointer";
    const parentLink = img.closest("a");
    if (parentLink) {
      parentLink.addEventListener("click", (e) => {
        // If the image is wrapped in a link natively by wp
        const href = parentLink.getAttribute("href");
        if (
          href &&
          (href.match(/\.(jpeg|jpg|gif|png|webp|svg)$/i) || href === "#")
        ) {
          e.preventDefault();
        }
      });
    }

    img.addEventListener("click", (e) => {
      e.stopPropagation();
      e.preventDefault(); // In case it's in a link block
      openLightbox(index);
    });
  });

  const updateLightbox = () => {
    lightboxImg.classList.remove("is-loaded");

    const currentImg = images[currentIndex];

    lightboxImg.onload = null;
    lightboxImg.onload = () => {
      lightboxImg.classList.add("is-loaded");
    };

    // Set image
    lightboxImg.src = currentImg.src;

    // Optional but good
    if (currentImg.alt) {
      lightboxImg.alt = currentImg.alt;
    } else {
      lightboxImg.removeAttribute("alt");
    }

    if (currentImg.srcset) {
      lightboxImg.srcset = currentImg.srcset;
    } else {
      lightboxImg.removeAttribute("srcset");
    }

    // ✅ COUNTER
    if (lightboxCounter) {
      lightboxCounter.textContent = `${currentIndex + 1} / ${images.length}`;
    }

    // ✅ NAV VISIBILITY
    if (btnPrev && btnNext) {
      if (currentIndex > 0) {
        btnPrev.classList.add("is-visible");
      } else {
        btnPrev.classList.remove("is-visible");
      }

      if (currentIndex < images.length - 1) {
        btnNext.classList.add("is-visible");
      } else {
        btnNext.classList.remove("is-visible");
      }
    }
  };

  const openLightbox = (index) => {
    currentIndex = index;
    lightbox.classList.add("is-active");
    updateLightbox();
    document.body.style.overflow = "hidden";
  };

  const closeLightbox = () => {
    lightbox.classList.remove("is-active");
    setTimeout(() => {
      lightboxImg.src = "";
    }, 300);
    document.body.style.overflow = "";
  };

  const nextImage = (e) => {
    if (e) e.stopPropagation();
    if (currentIndex < images.length - 1) {
      currentIndex++;
      updateLightbox();
    }
  };

  const prevImage = (e) => {
    if (e) e.stopPropagation();
    if (currentIndex > 0) {
      currentIndex--;
      updateLightbox();
    }
  };

  // Use once or check for existing listeners if this is called multiple times on the same elements
  if (!btnClose.dataset.listenerInit) {
    btnClose.addEventListener("click", closeLightbox);
    btnNext.addEventListener("click", nextImage);
    btnPrev.addEventListener("click", prevImage);
    btnClose.dataset.listenerInit = "true";
  }

  // Close when clicking outside image
  if (container && !container.dataset.listenerInit) {
    container.addEventListener("click", (e) => {
      if (e.target === container) {
        closeLightbox();
      }
    });
    container.dataset.listenerInit = "true";
  }

  if (!window.lightboxKeydownInit) {
    document.addEventListener("keydown", (e) => {
      if (!lightbox.classList.contains("is-active")) return;
      if (e.key === "Escape") closeLightbox();
      if (e.key === "ArrowRight") nextImage();
      if (e.key === "ArrowLeft") prevImage();
    });
    window.lightboxKeydownInit = true;
  }
};

document.addEventListener("DOMContentLoaded", window.initLightbox);
