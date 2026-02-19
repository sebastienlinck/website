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

/**
 * Affiche graphiquement le retour du serveur (Succès ou Erreur)
 */
function showStatusMessage(message, type, container) {
  if (!container) return;

  // Utilisation des variables de couleurs définies dans votre thème
  const bgColor = type === "success" ? "var(--pine)" : "var(--berry)";
  const textColor = "var(--crystal)";

  container.innerHTML = `
    <div style="padding: 1rem; border-radius: var(--radius); margin-bottom: 1rem; 
                text-align: center; font-weight: bold; background: ${bgColor}; color: ${textColor};">
      ${message}
    </div>
  `;
}

/* --- GESTION DU CAPTCHA AVANCÉ --- */
function generateCaptcha() {
  const questionElement = document.getElementById("captcha-question");
  const refreshButton = document.getElementById("refresh-captcha");

  if (!questionElement || !refreshButton) return;

  const num1 = Math.floor(Math.random() * 10);
  const num2 = Math.floor(Math.random() * 10);
  const operators = ["plus", "moins", "fois"];
  const operator = operators[Math.floor(Math.random() * operators.length)];

  let solution;
  let questionText;

  switch (operator) {
    case "plus":
      solution = num1 + num2;
      questionText = `${convertNumberToWords(num1)} plus ${convertNumberToWords(num2)}`;
      break;
    case "moins":
      solution = num1 - num2;
      questionText = `${convertNumberToWords(num1)} moins ${convertNumberToWords(num2)}`;
      break;
    case "fois":
      solution = num1 * num2;
      questionText = `${convertNumberToWords(num1)} fois ${convertNumberToWords(num2)}`;
      break;
    default:
      solution = 0;
      questionText = "";
  }

  questionElement.textContent = questionText;
  questionElement.dataset.solution = solution;

  refreshButton.addEventListener("click", generateCaptcha, { once: true });
}

function checkCaptchaAndUpdateButton() {
  const captchaInput = document.getElementById("captcha");
  const captchaQuestion = document.querySelector("#captcha-question");
  const submitButton = document.getElementById("envoyer-contact");

  if (captchaInput && captchaQuestion && submitButton) {
    const userAnswer = parseInt(captchaInput.value);
    const correctAnswer = parseInt(captchaQuestion.dataset.solution);

    if (isNaN(userAnswer) || userAnswer !== correctAnswer) {
      submitButton.disabled = true;
      return false;
    } else {
      submitButton.disabled = false;
      return true;
    }
  }
  return false;
}

/* --- INITIALISATION FORMULAIRE --- */
document.addEventListener("DOMContentLoaded", () => {
  generateCaptcha();

  const contactForm = document.getElementById("contact-form");
  const submitButton = document.getElementById("envoyer-contact");

  if (contactForm && submitButton) {
    submitButton.disabled = true;

    const captchaInput = document.getElementById("captcha");
    if (captchaInput) {
      captchaInput.addEventListener("input", checkCaptchaAndUpdateButton);
    }

    contactForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      if (checkCaptchaAndUpdateButton()) {
        const form = e.target;
        const formData = new FormData(form);

        // IMPORTANT : On ajoute manuellement 'envoyer' pour que le PHP le détecte
        formData.append("envoyer", "1");

        const statusMessageElement = document.getElementById("status-message");
        if (statusMessageElement) statusMessageElement.innerHTML = "";

        try {
          // On cible bien sendmail.php
          const response = await fetch("sendmail.php", {
            method: "POST",
            body: formData,
          });

          if (!response.ok) throw new Error("Erreur réseau");

          const result = await response.json();

          if (result.success) {
            showStatusMessage(result.message, "success", statusMessageElement);
            form.reset();
            generateCaptcha();
            submitButton.disabled = true;
          } else {
            showStatusMessage(result.message, "error", statusMessageElement);
          }
        } catch (error) {
          showStatusMessage(
            "Erreur technique lors de l'envoi du message.",
            "error",
            statusMessageElement,
          );
        }
      }
    });
  }
});
