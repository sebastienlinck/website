/* --- SCRIPT JS PRINCIPAL --- */

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

  // Vérifier le thème préféré de l'utilisateur
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme) {
    html.setAttribute("data-theme", savedTheme);
  } else if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
    html.setAttribute("data-theme", "dark");
  }

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

/* --- UTILITAIRES --- */
function convertNumberToWords(number) {
  const units = [
    "zéro",
    "un",
    "deux",
    "trois",
    "quatre",
    "cinq",
    "six",
    "sept",
    "huit",
    "neuf",
  ];
  return units[number];
}

/* --- GESTION DU CAPTCHA AVANCÉ --- */
function generateCaptcha() {
  const questionElement = document.getElementById("captcha-question");
  const refreshButton = document.getElementById("refresh-captcha");

  if (!questionElement || !refreshButton) return;

  // Définir les nombres et l'opérateur
  const num1 = Math.floor(Math.random() * 10);
  const num2 = Math.floor(Math.random() * 10);
  const operators = ["plus", "moins", "fois"];
  const operator = operators[Math.floor(Math.random() * operators.length)];

  // Calculer la réponse
  let solution;
  switch (operator) {
    case "plus":
      solution = num1 + num2;
      break;
    case "moins":
      solution = num1 - num2;
      break;
    case "fois":
      solution = num1 * num2;
      break;
    default:
      solution = 0;
  }

  // Créer la question en lettres
  let questionText;
  switch (operator) {
    case "plus":
      questionText = `${convertNumberToWords(num1)} plus ${convertNumberToWords(num2)}`;
      break;
    case "moins":
      questionText = `${convertNumberToWords(num1)} moins ${convertNumberToWords(num2)}`;
      break;
    case "fois":
      questionText = `${convertNumberToWords(num1)} fois ${convertNumberToWords(num2)}`;
      break;
    default:
      questionText = "";
  }

  // Mettre à jour l'élément de question
  questionElement.textContent = questionText;
  questionElement.dataset.solution = solution;

  // Ajouter un événement pour rafraîchir le CAPTCHA
  refreshButton.addEventListener("click", generateCaptcha, { once: true });
}

/* --- GESTION DU FORMULAIRE DE CONTACT AJAX --- */
document.addEventListener("DOMContentLoaded", () => {
  // Générer le CAPTCHA au chargement
  generateCaptcha();

  const contactForm = document.getElementById("contact-form");
  if (contactForm) {
    contactForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      const form = e.target;
      const formData = new FormData(form);
      const statusMessageElement = document.getElementById("status-message");
      const captchaInput = document.getElementById("captcha");
      const captchaQuestion = document.getElementById("captcha-question");

      // Vérification du CAPTCHA
      if (captchaInput && captchaQuestion) {
        const userAnswer = parseInt(captchaInput.value);
        const correctAnswer = parseInt(captchaQuestion.dataset.solution);

        if (isNaN(userAnswer) || userAnswer !== correctAnswer) {
          showStatusMessage(
            "Veuillez résoudre correctement le calcul CAPTCHA.",
            "error",
            statusMessageElement,
          );
          generateCaptcha(); // Régénérer le CAPTCHA
          return;
        }
      }

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
          // Régénérer le CAPTCHA
          generateCaptcha();
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

  // Créer le message de statut
  const statusDiv = document.createElement("div");
  statusDiv.className = `status-message ${type}`;
  statusDiv.textContent = message;

  // Ajouter le message à l'élément parent
  element.appendChild(statusDiv);

  // Supprimer le message après 5 secondes
  setTimeout(() => {
    if (statusDiv && statusDiv.parentNode) {
      statusDiv.parentNode.removeChild(statusDiv);
    }
  }, 5000);
}

/* --- GESTION DU RAZ DU FORMULAIRE --- */
document.addEventListener("DOMContentLoaded", () => {
  const resetButtons = document.querySelectorAll("[data-reset-form]");
  resetButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const formId = this.getAttribute("data-reset-form");
      const form = document.getElementById(formId);
      if (form) {
        form.reset();
        // Régénérer le CAPTCHA si présent
        generateCaptcha();
      }
    });
  });
});
