function onSubmit(token) {
  document.getElementById("contact-form").submit();
}

function fcookie() {
  if (
    window.cookieAccepted === true ||
    (document.cookie && document.cookie.includes("__Secure-cookieDef"))
  )
    return;

  const cookieEl = document.querySelector("#cookie");
  if (!cookieEl) return;
  cookieEl.classList.add("show");

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

document.querySelectorAll(".hamburger").forEach((item) => {
  item.addEventListener("click", (event) => {
    const targetMenu = item.nextElementSibling;
    if (targetMenu) {
      targetMenu.classList.toggle("visible");
    } else {
      console.warn(
        "Hamburger menu clicked, but no next sibling found to toggle visibility.",
        item,
      );
    }
  });
});

document.querySelectorAll("nav a").forEach((link) => {
  if (link.href === window.location.href) {
    link.classList.add("active");
    link.setAttribute("aria-current", "page");
  }
});
