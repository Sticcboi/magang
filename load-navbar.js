// Function to load navbar from navbar.html
function loadNavbar() {
  // Prevent double loading - check if navbar already exists
  const existingNav = document.querySelector("nav.navbar");
  if (existingNav) {
    // Navbar already loaded, just set active state
    setActiveNavItem();
    return;
  }

  fetch("navbar.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error(`Navbar fetch failed: ${response.status} ${response.statusText}`);
      }
      return response.text();
    })
    .then((html) => {
      const parser = new DOMParser();
      const navDoc = parser.parseFromString(html, "text/html");

      // Extract only the <nav> and <offcanvas> elements
      const navElement = navDoc.querySelector("nav");
      const offcanvasElement = navDoc.querySelector(".offcanvas");
      const styleElement = navDoc.querySelector("style");

      if (!navElement || !offcanvasElement) {
        console.error("Navbar or offcanvas element not found", {
          htmlPreview: html.slice(0, 600),
          hasNav: Boolean(navDoc.querySelector("nav")),
          hasOffcanvas: Boolean(navDoc.querySelector(".offcanvas")),
        });
        return;
      }

      // Get or create navbar container
      let navContainer = document.getElementById("navbar-container");
      if (!navContainer) {
        navContainer = document.createElement("div");
        navContainer.id = "navbar-container";
        document.body.insertBefore(navContainer, document.body.firstChild);
      }

      // Inject nav and offcanvas into container
      navContainer.innerHTML = "";
      navContainer.appendChild(navElement.cloneNode(true));
      navContainer.appendChild(offcanvasElement.cloneNode(true));

      // Inject style into head (only once)
      if (styleElement && !document.getElementById("navbar-styles")) {
        const styleClone = styleElement.cloneNode(true);
        styleClone.id = "navbar-styles";
        document.head.appendChild(styleClone);
      }

      // Set active nav item and initialize Bootstrap
      setActiveNavItem();
      initializeBootstrap();
    })
    .catch((error) => console.error("Error loading navbar:", error));
}

// Set active nav item based on current page
function setActiveNavItem() {
  const currentPage = window.location.pathname.split("/").pop() || "index.php";

  // Set active class for main nav-link items
  document.querySelectorAll(".nav-link").forEach((link) => {
    const href = link.getAttribute("href");
    if (href === currentPage || (currentPage === "" && href === "index.php")) {
      link.classList.add("active");
    } else {
      link.classList.remove("active");
    }
  });

  // Set active class for submenu-item links
  document.querySelectorAll(".submenu-item").forEach((link) => {
    const href = link.getAttribute("href");
    if (href === currentPage) {
      link.classList.add("active");
    } else {
      link.classList.remove("active");
    }
  });
}

// Reinitialize Bootstrap components
function initializeBootstrap() {
  if (typeof bootstrap !== "undefined") {
    const offcanvasElements = document.querySelectorAll(".offcanvas");
    offcanvasElements.forEach((el) => {
      new bootstrap.Offcanvas(el);
    });

    // Handle submenu toggle (open/close) properly
    document.querySelectorAll('.nav-link[data-bs-toggle="collapse"]').forEach((toggle) => {
      toggle.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();

        const targetSelector = this.getAttribute("href") || this.getAttribute("data-bs-target");
        const targetEl = document.querySelector(targetSelector);
        if (!targetEl) return;

        const bsCollapse = bootstrap.Collapse.getOrCreateInstance(targetEl, { toggle: false });
        const isCurrentlyOpen = targetEl.classList.contains("show");

        // Close all other open submenus (accordion behavior)
        document.querySelectorAll(".submenu.show").forEach((openMenu) => {
          if (openMenu !== targetEl) {
            bootstrap.Collapse.getOrCreateInstance(openMenu, { toggle: false }).hide();
            const otherToggle = document.querySelector('[href="#' + openMenu.id + '"], [data-bs-target="#' + openMenu.id + '"]');
            if (otherToggle) {
              otherToggle.setAttribute("aria-expanded", "false");
              const otherChevron = otherToggle.querySelector(".bi-chevron-down, .bi-chevron-up");
              if (otherChevron) {
                otherChevron.classList.remove("bi-chevron-up");
                otherChevron.classList.add("bi-chevron-down");
              }
            }
          }
        });

        // Toggle current submenu
        if (isCurrentlyOpen) {
          bsCollapse.hide();
          this.setAttribute("aria-expanded", "false");
        } else {
          bsCollapse.show();
          this.setAttribute("aria-expanded", "true");
        }

        // Toggle chevron icon
        const chevron = this.querySelector(".bi-chevron-down, .bi-chevron-up");
        if (chevron) {
          chevron.classList.toggle("bi-chevron-down");
          chevron.classList.toggle("bi-chevron-up");
        }
      });
    });

    // Auto-expand submenu if current page is inside it
    const currentPage = window.location.pathname.split("/").pop() || "index.php";
    document.querySelectorAll(".submenu-item").forEach((item) => {
      const href = item.getAttribute("href");
      if (href === currentPage) {
        item.classList.add("active");
        const parentSubmenu = item.closest(".submenu");
        if (parentSubmenu) {
          parentSubmenu.classList.add("show");
          const parentToggle = document.querySelector('[href="#' + parentSubmenu.id + '"], [data-bs-target="#' + parentSubmenu.id + '"]');
          if (parentToggle) {
            parentToggle.setAttribute("aria-expanded", "true");
            const chevron = parentToggle.querySelector(".bi-chevron-down");
            if (chevron) {
              chevron.classList.remove("bi-chevron-down");
              chevron.classList.add("bi-chevron-up");
            }
          }
        }
      }
    });
  }
}

// Load navbar when DOM is ready
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", loadNavbar);
} else {
  loadNavbar();
}
