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

// ----------------------------------------------------
// COPY CODE BLOCK HANDLER
// ----------------------------------------------------
window.initCodeCopyButtons = function() {
    const codeBlocks = document.querySelectorAll("pre");

    codeBlocks.forEach((pre) => {
        // Create wrapper if it doesn't already have one
        if (!pre.parentNode.classList.contains("code-block-wrapper")) {
            const wrapper = document.createElement("div");
            wrapper.className = "code-block-wrapper relative group";
            pre.parentNode.insertBefore(wrapper, pre);
            wrapper.appendChild(pre);

            // Create copy button
            const copyBtn = document.createElement("button");
            // Match the styling requested
            copyBtn.className = "copy-code-btn absolute top-2 right-2 p-2 bg-gray-800 text-gray-300 hover:text-white hover:bg-gray-700 rounded-md opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer border border-gray-700/50 backdrop-blur-sm z-10 hidden md:flex";
            copyBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
            `;
            copyBtn.setAttribute('aria-label', 'Copy code to clipboard');
            copyBtn.title = 'Copy code';
            
            wrapper.appendChild(copyBtn);

            copyBtn.addEventListener("click", async () => {
                const codeNode = pre.querySelector("code");
                const code = codeNode ? codeNode.innerText : pre.innerText;
                
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    try {
                        await navigator.clipboard.writeText(code);
                        showCopyToast("Code copied!");
                        toggleIcon(copyBtn);
                        return;
                    } catch (err) { }
                }

                // Fallback
                const temp = document.createElement("textarea");
                temp.value = code;
                temp.style.position = "fixed";
                temp.style.opacity = "0";
                temp.style.top = "-999px";

                document.body.appendChild(temp);
                temp.select();
                document.execCommand("copy");
                temp.remove();

                showCopyToast("Code copied!");
                toggleIcon(copyBtn);
            });
        }
    });

    function toggleIcon(btn) {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        `;
        setTimeout(() => {
            btn.innerHTML = originalHtml;
        }, 2000);
    }
};

// Initialize on first load
document.addEventListener("DOMContentLoaded", () => {
    if (typeof window.initCodeCopyButtons === 'function') {
        window.initCodeCopyButtons();
    }
});
