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

  // Sets parallax effect only on root page
  if (window.location.pathname == '/') {
    // Parallax events
    (() => {
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
        let depth = `${50 - (mouseX - width) * 0.01}% ${50 - (mouseY - height) * 0.01}%`;
  
        chess1.style.backgroundPosition = depth;
        chess2.style.backgroundPosition = depth;
        chess3.style.backgroundPosition = depth;
      }
    })();
  }
  

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

  // Auto expand textarea as user input grows
  document.addEventListener(
    'input',
    e => {
      if (e.target.tagName.toLowerCase() !== 'textarea') return;
      autoExpand(e.target);
    },
    false
  );

  const autoExpand = field => {
    // Reset field height
    field.style.height = 'inherit';

    // Get the computed styles for the element
    const computed = window.getComputedStyle(field);

    // Calculate the height
    const height =
      parseInt(computed.getPropertyValue('border-top-width'), 10) +
      parseInt(computed.getPropertyValue('padding-top'), 10) +
      field.scrollHeight +
      parseInt(computed.getPropertyValue('padding-bottom'), 10) +
      parseInt(computed.getPropertyValue('border-bottom-width'), 10);

    field.style.height = `${height}px`;
  };
});