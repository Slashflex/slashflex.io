// Reduce .hero max-height based on .hero__text width
$(document).ready(() => {
  // Off menu
  $(".open-nav").click(function () {
    $(".bottomNav").slideDown(850);
  });

  $(".close").click(function () {
    $(".bottomNav").slideUp(850);
  });

  // Burger
  $(".first-button").on("click", function (e) {
    $(".animated-icon").toggleClass("open");
    setInterval(() => {
      if ($(".animated-icon").toggleClass("open")) {
        $(".animated-icon").removeClass("open");
      }
    }, 500);
    e.preventDefault();
  });

  // Parallax events
  (function () {
    // Add event listener
    document.addEventListener("mousemove", parallax);
    const chess1 = document.querySelector(".hero__chess1");
    const chess2 = document.querySelector(".hero__chess2");
    const chess3 = document.querySelector(".hero__chess3");

    // Magic happens here
    function parallax(e) {
      let width = window.innerWidth / 2;
      let height = window.innerHeight / 2;
      let mouseX = e.clientX;
      let mouseY = e.clientY;
      let depth = `${50 - (mouseX - width) * 0.01}% ${
        50 - (mouseY - height) * 0.01
      }%`;

      chess1.style.backgroundPosition = depth;
      chess2.style.backgroundPosition = depth;
      chess3.style.backgroundPosition = depth;
    }
  })();

  // Swiper
  var swiper = new Swiper(".swiper-container", {
    loop: true,
    pagination: {
      el: ".swiper-pagination",
      type: "progressbar",
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });

  // Disable right click on everything
  (() => {
    $(document).on("contextmenu", "*", () => {
      return false;
    });
  })();
});
