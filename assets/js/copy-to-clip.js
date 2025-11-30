// ----------------------------------------------------
// GLOBAL TOOLTIP FUNCTION (Top-Center Toast)
// ----------------------------------------------------
function showCopyToast(message = "Copied!") {
    let toast = document.createElement("div");

    toast.textContent = message;
    toast.style.position = "fixed";
    toast.style.top = "20px";
    toast.style.left = "50%";
    toast.style.transform = "translateX(-50%)";
    toast.style.background = "#000";
    toast.style.color = "#fff";
    toast.style.padding = "8px 14px";
    toast.style.fontSize = "14px";
    toast.style.borderRadius = "6px";
    toast.style.zIndex = 9;
    toast.style.opacity = "0";
    toast.style.transition = "opacity 0.3s ease";
    toast.style.boxShadow = "0 8px 20px rgba(0,0,0,0.08)";

    document.body.appendChild(toast);

    // fade-in
    requestAnimationFrame(() => {
        toast.style.opacity = "1";
    });

    // fade-out + remove
    setTimeout(() => {
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 300);
    }, 1500);
}


// ----------------------------------------------------
// COPY EMAIL HANDLER
// ----------------------------------------------------
document.addEventListener("click", async (e) => {
    const btn = e.target.closest("[data-email]");
    if (!btn) return;

    e.preventDefault();

    const text = btn.getAttribute("data-email")?.trim() || "";
    if (!text) return;

    // Modern API
    if (navigator.clipboard && navigator.clipboard.writeText) {
        try {
            await navigator.clipboard.writeText(text);
            showCopyToast("Copied to clipboard!");
            return;
        } catch (err) {
            // console.warn("Modern API failed, using fallback.");
        }
    }

    // Fallback (textarea method)
    const temp = document.createElement("textarea");
    temp.value = text;
    temp.style.position = "fixed";
    temp.style.opacity = "0";
    temp.style.top = "-999px";

    document.body.appendChild(temp);
    temp.select();

    // @ts-ignore → fallback needed for Safari / older browsers
    document.execCommand("copy");

    temp.remove();

    showCopyToast("Copied to clipboard!");
});
