function updateLocalTimeEverywhere() {
  const nodes = document.querySelectorAll("[data-local-time]");
  if (!nodes.length) return;

  const date = new Date();
  let hour = date.getHours();
  const mins = date.getMinutes().toString().padStart(2, "0");
  const ampm = hour >= 12 ? "PM" : "AM";
  hour = hour % 12 || 12;

  const formatted = `${hour}:${mins} ${ampm}`;

  nodes.forEach((el) => {
    el.textContent = formatted;
    el.style.opacity = "1";
  });
}

/* --- 1️⃣ INITIAL LOAD --- */
document.addEventListener("DOMContentLoaded", updateLocalTimeEverywhere);

/* --- 2️⃣ UPDATE EVERY 30s --- */
setInterval(updateLocalTimeEverywhere, 30000);

/* --- 3️⃣ MODAL OPEN FIX --- */
document.addEventListener("click", (e) => {
  if (
    e.target.matches("[data-open-contact-modal]") ||
    e.target.closest("[data-open-contact-modal]")
  ) {
    setTimeout(updateLocalTimeEverywhere, 10);
  }
});

/* --- 4️⃣ NEW FIX — update as soon as time element appears --- */
const timeObserver = new MutationObserver(() => {
  const nodes = document.querySelectorAll("[data-local-time]");
  nodes.forEach((el) => {
    if (!el.dataset.timeRendered) {
      updateLocalTimeEverywhere();
      el.dataset.timeRendered = "1"; // run once for this element
    }
  });
});

timeObserver.observe(document.body, {
  childList: true,
  subtree: true,
});
