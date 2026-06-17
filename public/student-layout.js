function initLayout() {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleBtn");
    const toggleIcon = document.getElementById("toggleIcon");

    function isMobile() {
        return window.innerWidth <= 768;
    }

    toggleBtn.addEventListener("click", () => {
        if (isMobile()) {
            sidebar.classList.toggle("mobile-open");
        } else {
            const collapsed = sidebar.classList.toggle("collapsed");
            document.body.classList.toggle("collapsed", collapsed);
            toggleIcon.className = collapsed
                ? "ti ti-chevron-right"
                : "ti ti-chevron-left";
        }
    });

    document.addEventListener("click", (e) => {
        if (
            isMobile() &&
            sidebar.classList.contains("mobile-open") &&
            !sidebar.contains(e.target) &&
            e.target !== toggleBtn
        ) {
            sidebar.classList.remove("mobile-open");
        }
    });

    const userBtn = document.getElementById("userBtn");
    const userDropdown = document.getElementById("userDropdown");
    if (userBtn) {
        userBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle("open");
        });
        document.addEventListener("click", () =>
            userDropdown.classList.remove("open"),
        );
    }

    const toast = document.getElementById("toast");
    if (toast) {
        setTimeout(() => {
            toast.style.opacity = "0";
            toast.style.transform = "translateX(-50%) translateY(-12px)";
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
}
document.addEventListener("DOMContentLoaded", initLayout);
