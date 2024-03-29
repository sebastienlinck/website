function onSubmit(token) {
  document.getElementById("contact-form").submit();
}

function fcookie() {
  if (document.cookie.includes("slcook")) return;
  document.querySelector("#cookie").classList.add("show");
  document.querySelector("#cookie button").addEventListener("click", () => {
    document.querySelector("#cookie").classList.remove("show");
    document.cookie =
      "cookieDef=slcook; max-age=" +
      60 * 60 * 24 * 30 +
      "; samesite=strict; Secure";
  });
}

window.addEventListener("load", fcookie);

window.addEventListener("scroll", (event) => {
  if (document.documentElement.scrollTop > 300) {
    document.querySelector(".enhaut").classList.add("visible");
  } else {
    document.querySelector(".enhaut").classList.remove("visible");
  }
});

document.querySelectorAll(".hamburger").forEach((item) => {
  item.addEventListener("click", (event) => {
    item.nextElementSibling.classList.toggle("visible");
  });
});

document.querySelectorAll("nav a").forEach((link) => {
  if (link.href === window.location.href) {
    link.classList.add("active");
    link.setAttribute("aria-current", "page");
  }
});
