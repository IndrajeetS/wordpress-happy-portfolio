// --- Lightbox / Image Carousel Logic ---
document.addEventListener("DOMContentLoaded", function () {
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

    // ✅ COUNTER (you removed this)
    lightboxCounter.textContent = `${currentIndex + 1} / ${images.length}`;

    // ✅ NAV VISIBILITY (this is why buttons disappeared)
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

  btnClose.addEventListener("click", closeLightbox);
  btnNext.addEventListener("click", nextImage);
  btnPrev.addEventListener("click", prevImage);

  // Close when clicking outside image
  container.addEventListener("click", (e) => {
    if (e.target === container) {
      closeLightbox();
    }
  });

  document.addEventListener("keydown", (e) => {
    if (!lightbox.classList.contains("is-active")) return;
    if (e.key === "Escape") closeLightbox();
    if (e.key === "ArrowRight") nextImage();
    if (e.key === "ArrowLeft") prevImage();
  });
});
