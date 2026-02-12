/* --- GESTION DES COOKIES --- */
function fcookie() {
  // Ne pas afficher si déjà accepté (variable PHP ou cookie présent)
  if (
    window.cookieAccepted === true ||
    (document.cookie && document.cookie.includes("__Secure-cookieDef"))
  )
    return;

  const cookieEl = document.querySelector("#cookie");
  if (!cookieEl) return;

  setTimeout(() => {
    cookieEl.classList.add("show");
  }, 1000);

  const acceptBtn = document.querySelector("#cookie-button");
  if (acceptBtn) {
    acceptBtn.addEventListener("click", (e) => {
      cookieEl.classList.remove("show");
      const href =
        acceptBtn.getAttribute("href") || acceptBtn.dataset.acceptUrl;
      if (href) {
        window.location.href = href;
      }
    });
  }
}

window.addEventListener("load", fcookie);

/* --- GESTION DU DARK MODE --- */
document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("theme-toggle");
  const html = document.documentElement;

  // Gestion du clic uniquement
  if (btn) {
    btn.addEventListener("click", () => {
      const currentTheme = html.getAttribute("data-theme");
      const newTheme = currentTheme === "dark" ? "light" : "dark";

      html.setAttribute("data-theme", newTheme);
      localStorage.setItem("theme", newTheme);
    });
  }
});
