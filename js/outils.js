function onSubmit(token) {
  document.getElementById("contact-form").submit();
}

function fcookie() {
  // don't show if server already marked cookie accepted or cookie present in JS
  if (
    window.cookieAccepted === true ||
    (document.cookie && document.cookie.includes("__Secure-cookieDef"))
  )
    return;

  const cookieEl = document.querySelector("#cookie");
  if (!cookieEl) return;
  cookieEl.classList.add("show");

  // attach listener to the actual accept link/button (id cookie-button)
  const acceptBtn = document.querySelector("#cookie-button");
  if (acceptBtn) {
    acceptBtn.addEventListener("click", (e) => {
      // hide banner immediately for UX
      cookieEl.classList.remove("show");
      // if it's an anchor (<a>), let it navigate; if it's a button, redirect to the server endpoint
      const href =
        acceptBtn.getAttribute("href") || acceptBtn.dataset.acceptUrl;
      if (href) {
        // navigate to set the HttpOnly cookie server-side
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
