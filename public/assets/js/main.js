document.addEventListener("DOMContentLoaded", function () {
    const wrapper = document.querySelector(".dashboard-wrapper");
    const toggleBtn = document.getElementById("menu-toggle-btn");

    if (!wrapper || !toggleBtn) return;

    // restore last state
    const saved = localStorage.getItem("sidebar-collapsed");
    if (saved === "1") wrapper.classList.add("sidebar-collapsed");

    toggleBtn.addEventListener("click", function () {
        wrapper.classList.toggle("sidebar-collapsed");

        // save state
        localStorage.setItem(
            "sidebar-collapsed",
            wrapper.classList.contains("sidebar-collapsed") ? "1" : "0"
        );
    });
});
//

document.addEventListener("click", async (e) => {
    const btn = e.target.closest(".wishlist-btn");
    if (!btn) return;

    const businessId = btn.dataset.businessId;
    const url = document
        .querySelector('meta[name="wishlist-toggle-url"]')
        .getAttribute("content");

    const res = await fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            Accept: "application/json",
        },
        body: JSON.stringify({
            business_id: businessId,
        }),
    });

    if (res.status === 401) {
        window.location.href = "/login";
        return;
    }

    const data = await res.json();

    if (data.saved) btn.classList.add("is-saved");
    else btn.classList.remove("is-saved");
});
