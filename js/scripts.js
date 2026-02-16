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

/* --- GESTION DU FORMULAIRE DE CONTACT AJAX --- */
document.addEventListener("DOMContentLoaded", () => {
  const contactForm = document.getElementById("contact-form");
  if (contactForm) {
    contactForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      const form = e.target;
      const formData = new FormData(form);
      const statusMessageElement = document.getElementById("status-message");

      // Effacer les messages précédents
      if (statusMessageElement) {
        statusMessageElement.innerHTML = "";
      }

      try {
        const response = await fetch("contact.php", {
          method: "POST",
          body: formData,
        });

        const result = await response.json();

        if (result.success) {
          // Afficher un message de succès
          showStatusMessage(result.message, "success", statusMessageElement);
          // Réinitialiser le formulaire
          form.reset();
        } else {
          // Afficher un message d'erreur
          showStatusMessage(result.message, "error", statusMessageElement);
        }
      } catch (error) {
        showStatusMessage(
          "Erreur technique lors de l'envoi du message.",
          "error",
          statusMessageElement,
        );
      }
    });
  }
});

/* --- FONCTION D'AFFICHAGE DES MESSAGES DE STATUT --- */
function showStatusMessage(message, type, element) {
  if (!element) return;

  const statusDiv = document.createElement("div");
  statusDiv.style.padding = "1rem";
  statusDiv.style.borderRadius = "var(--radius)";
  statusDiv.style.marginBottom = "1rem";
  statusDiv.style.textAlign = "center";
  statusDiv.style.fontWeight = "bold";
  statusDiv.style.background =
    type === "success" ? "var(--pine)" : "var(--berry)";
  statusDiv.style.color = "var(--crystal)";
  statusDiv.textContent = message;

  element.appendChild(statusDiv);

  // Supprimer le message après 5 secondes
  setTimeout(() => {
    if (statusDiv && statusDiv.parentNode) {
      statusDiv.parentNode.removeChild(statusDiv);
    }
  }, 5000);
}
