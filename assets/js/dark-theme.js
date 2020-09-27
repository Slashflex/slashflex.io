// Dark/Light Theme switch
const checkbox = document.querySelector("input[name=theme]");

checkbox.addEventListener("change", function () {
  if (this.checked) {
    transition();
    document.documentElement.setAttribute("data-theme", "dark");
  } else {
    transition();
    document.documentElement.setAttribute("data-theme", "light");
  }
});

let transition = () => {
  document.documentElement.classList.add("transition");
  window.setTimeout(() => {
    document.documentElement.classList.remove("transition");
  }, 1000);
};

const themeSwitch = document.getElementById("switch");
if (themeSwitch) {
  initTheme(); // on page load, if user has already selected a specific theme -> apply it

  themeSwitch.addEventListener("change", function (event) {
    resetTheme(); // update color theme
  });

  function initTheme() {
    var darkThemeSelected =
      localStorage.getItem("switch") !== null &&
      localStorage.getItem("switch") === "dark";
    // update checkbox
    themeSwitch.checked = darkThemeSelected;
    // update body data-theme attribute
    darkThemeSelected
      ?
      document.querySelector("html").setAttribute("data-theme", "dark") :
      document.querySelector("html").removeAttribute("data-theme");
  }

  function resetTheme() {
    if (themeSwitch.checked) {
      // dark theme has been selected
      document.querySelector("html").setAttribute("data-theme", "dark");
      localStorage.setItem("switch", "dark"); // save theme selection
    } else {
      document.querySelector("html").removeAttribute("data-theme");
      localStorage.removeItem("switch"); // reset theme selection
    }
  }
}