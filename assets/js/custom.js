$(document).ready(() => {
  // Removes Download link
  if (
    window.location.pathname.endsWith("new") ||
    window.location.pathname.endsWith("edit")
  ) {
    const download = document.querySelectorAll("a[download]");
    download[1].style.display = "none";
  }

  const files = document.querySelectorAll('input[type="file"]');
  files.forEach((e) => {
    e.classList.add("inputfile");
  });

  // Sets parallax effect only on root page
  if (window.location.pathname == "/") {
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
        let depth1 = `${50 - (mouseX - width) * 0.01}% ${
          50 - (mouseY - height) * 0.01
        }%`;
        let depth2 = `${50 + (mouseX - width) * 0.01}% ${
          50 + (mouseY - height) * 0.01
        }%`;
        let depth3 = `${50 + (mouseX - width) * 0.01}% ${
          50 - (mouseY - height) * 0.01
        }%`;

        chess1.style.backgroundPosition = depth1;
        chess2.style.backgroundPosition = depth2;
        chess3.style.backgroundPosition = depth3;
      }
    })();
  }

  $("#wrapper").mousemove((e) => {
    parallaxIt(e, ".keyF", -40);
    parallaxIt(e, ".keyU", 20);
    parallaxIt(e, ".keyK", -45);
    parallaxIt(e, ".keyC", 25);
  });

  const parallaxIt = (e, target, movement) => {
    var wrapper = $("#wrapper");
    var relX = e.pageX - wrapper.offset().left;
    var relY = e.pageY - wrapper.offset().top;

    TweenMax.to(target, 1, {
      x: ((relX - wrapper.width() / 2) / wrapper.width()) * movement,
      y: ((relY - wrapper.height() / 2) / wrapper.height()) * movement,
    });
  };

  // Swiper
  var swiper = new Swiper(".swiper-container", {
    loop: true,
    grabCursor: true,
    pagination: {
      el: ".swiper-pagination",
      type: "progressbar",
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    keyboard: {
      enabled: true,
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
    "input",
    (e) => {
      if (e.target.tagName.toLowerCase() !== "textarea") return;
      autoExpand(e.target);
    },
    false
  );

  const autoExpand = (field) => {
    // Reset field height
    field.style.height = "inherit";

    // Get the computed styles for the element
    const computed = window.getComputedStyle(field);

    // Calculate the height
    const height =
      parseInt(computed.getPropertyValue("border-top-width"), 10) +
      parseInt(computed.getPropertyValue("padding-top"), 10) +
      field.scrollHeight +
      parseInt(computed.getPropertyValue("padding-bottom"), 10) +
      parseInt(computed.getPropertyValue("border-bottom-width"), 10);

    field.style.height = `${height}px`;
  };

  // Full screen navigation toggle
  $("#menu-toggle").click(() => {
    $(this).toggleClass("active");
    if ($(this).hasClass("active")) {
      $(".nav").css("position", "static");
    } else {
      $(".nav").css("position", "fixed");
    }

    if (!$("#menu").hasClass("open")) {
      $(".nav").css("position", "static");
    }

    $("#menu").toggleClass("open");
  });

  // Flash message
  setInterval(() => {
    $(".flash-notice").slideUp().fadeOut();
  }, 3000);

  // Flips cards with perspective
  function flipCard(card, front, back, frontClass, backClass) {
    let count = 0;
    $(card).on("click", function () {
      count++;
      if (count === 1) {
        $(front).addClass(frontClass);
        $(back).addClass(backClass);
        count++;
      } else {
        $(front).removeClass(frontClass);
        $(back).removeClass(backClass);
        count = 0;
      }
    });
  }
  for (let i = 0; i < 15; i++) {
    const card = document.querySelector(`.card${[i]}`);
    const front = document.querySelector(`.front${[i]}`);
    const back = document.querySelector(`.back${[i]}`);
    flipCard(
      $(card),
      $(front),
      $(back),
      `front-visible-${[i]}`,
      `back-visible-${[i]}`
    );
  }

  if (window.location.pathname.includes("/works")) {
    // $(".swiper-slide").css('width', '0px');
    window.addEventListener("scroll", () => {
      if (window.scrollY > 600) {
        $(".swiper-button-next, .swiper-button-prev").css({
          top: "95%",
          right: "0",
        });
        $(".swiper-slide").css({
          "box-shadow": "0px 0px 0px white",
          "border-radius": "25px",
        });
      } else {
        $(".swiper-button-next, .swiper-button-prev").css({
          top: "50%",
          right: "0",
        });
        $(".swiper-slide").css({
          "box-shadow": "0px 0px 0px white",
          "border-radius": "0px",
        });
      }
    });
  }

  if (window.location.pathname == "/works") {
    for (let i = 1; i < 15; i++) {
      let back = document.querySelector(`.back${[i]}`);
      $(back).css("background-color", black);
    }
    //   $(".round").css("background-color", "#ffbe41");
  }
});

const warningIcon = '<i class="fas fa-exclamation-circle"></i>';

// Add warning icons if errors on form inputs
if ($(".form__errors").children().length > 0) {
  let ul = $(".form__errors").children();
  ul.children().prepend(`${warningIcon} `);
  ul.children().append(` ${warningIcon}`);
  $(ul).parent().css("border-bottom", "2px solid red");
}

// Add radio button error message on form submit if not checked
$(".button__login").click(function () {
  if ($(".form__radio-input").is(":checked")) {
    $(".agree-message", ".agree").empty().remove();
  } else {
    let message = window.localStorage.getItem("agree");
    $(".agree").append(`<li class="agree-message">${warningIcon} ${message} ${warningIcon}</li>`);
  }
});

// Remove radio button error message if checked
$(".form__radio-input").bind("click", function () {
  var $t = $(this);

  if ($t.is(":checked")) {
    $(".agree-message", ".agree").empty().remove();
  }
});

$('.generate-pdf').click(function() {
  $('#loading').hide();
});

const yellow = "#ffbe41";
const black = "#27282c";
const grey = "#585858";
const white = "#fff";

window.cookieconsent.initialise({
  palette: {
    popup: {
      background: yellow,
    },
    button: {
      background: "transparent",
      text: black,
      border: black,
    },
  },
  type: "opt-out",
  content: {
    href: "http://slashflex.io.test/terms-of-use",
  },
});

if (window.location.pathname == "/contact") {
  $(".round").css("display", "none");
}

if (
  window.location.pathname == "/" ||
  window.location.pathname.includes("/works/")
) {
  // Prevents text/image selection after a double click
  document.addEventListener(
    "mousedown",
    (event) => {
      if (event.detail > 1) {
        event.preventDefault();
      }
    },
    false
  );
}

if (window.location.pathname == "/works") {
  $(".card-content--image").css("background-size", "cover");
}
// Gooey button on https://slashflex.io/blog
if (window.location.pathname.includes("/blog")) {
  $(".button--bubble").css("color", black);
  $(".button").css("background-color", yellow);
}

// Loader with random quotes
if (
  window.location.pathname == "/" ||
  window.location.pathname == "/works" ||
  window.location.pathname.includes("/works/") ||
  window.location.pathname == "/blog" || window.location.pathname.includes("/blog/post")//  || window.location.pathname.includes("/blog/post")
) {
  const sentence = [
    `“Knowledge is power.” – <i class="loading__sentence" >Francis Bacon</i>`,
    `“Simplicity is the soul of efficiency.” – <i class="loading__sentence" >Austin Freeman</i>`,
    `“Make it work, make it right, make it fast.” – <i class="loading__sentence" >Kent Beck</i>`,
    `“Talk is cheap. Show me the code.“ – <i class="loading__sentence" >Linus Torvalds</i>`,
    `“Software is like sex: It’s better when it’s free.“ – <i class="loading__sentence" >Linus Torvalds</i>`,
    `“The Internet? Is that thing still around?” - <i class="loading__sentence" >Homer Simpson</i>`,
    `“I find your lack of faith disturbing.” - <i class="loading__sentence" >Darth Vader</i>`,
    `“Do. Or do not. There is no try.” - <i class="loading__sentence" >Yoda</i>`,
    `“We meet again at last.” - <i class="loading__sentence" >Darth Vader</i>`,
  ]; // Get a random index from the array sentence
  const randomNumber = Math.floor(Math.random() * sentence.length); // Get a random sentence from randomNumber

  const quote = sentence[randomNumber];
  $("body").append(
    `<div style="width: 100%; background: ${black}; color: ${white}; margin-bottom: 40vh; height: 100%; position: fixed;" id="loading"><div class="loader"></div></div>`
  ); // Displays a random quote inside the loading div

  $("#loading").append(
    `<p class="text-center h1 loading__text">`.concat(quote, "</p>")
  );
  $(window).on("load", () => {
    setTimeout(removeLoader, 600); // Wait for page load
  });

  const removeLoader = () => {
    $("#loading").fadeOut(500, () => {
      // FadeOut complete. Remove the loading div
      $(this).remove(); // Makes page more lightweight
    });
  };

  // Gooey button
  $(".button--bubble").each(function () {
    var $circlesTopLeft = $(this).parent().find(".circle.top-left");
    var $circlesBottomRight = $(this).parent().find(".circle.bottom-right");

    var tl = new TimelineLite();
    var tl2 = new TimelineLite();
    var btTl = new TimelineLite({ paused: true });

    tl.to($circlesTopLeft, 1.2, {
      x: -25,
      y: -25,
      scaleY: 2,
      ease: SlowMo.ease.config(0.1, 0.7, false),
    });
    tl.to($circlesTopLeft.eq(0), 0.1, { scale: 0.2, x: "+=6", y: "-=2" });
    tl.to(
      $circlesTopLeft.eq(1),
      0.1,
      { scaleX: 1, scaleY: 0.8, x: "-=10", y: "-=7" },
      "-=0.1"
    );
    tl.to(
      $circlesTopLeft.eq(2),
      0.1,
      { scale: 0.2, x: "-=15", y: "+=6" },
      "-=0.1"
    );
    tl.to($circlesTopLeft.eq(0), 1, {
      scale: 0,
      x: "-=5",
      y: "-=15",
      opacity: 0,
    });
    tl.to(
      $circlesTopLeft.eq(1),
      1,
      { scaleX: 0.4, scaleY: 0.4, x: "-=10", y: "-=10", opacity: 0 },
      "-=1"
    );
    tl.to(
      $circlesTopLeft.eq(2),
      1,
      { scale: 0, x: "-=15", y: "+=5", opacity: 0 },
      "-=1"
    );

    var tlBt1 = new TimelineLite();
    var tlBt2 = new TimelineLite();

    tlBt1.set($circlesTopLeft, { x: 0, y: 0, rotation: -45 });
    tlBt1.add(tl);

    tl2.set($circlesBottomRight, { x: 0, y: 0 });
    tl2.to($circlesBottomRight, 1.1, {
      x: 30,
      y: 30,
      ease: SlowMo.ease.config(0.1, 0.7, false),
    });
    tl2.to($circlesBottomRight.eq(0), 0.1, { scale: 0.2, x: "-=6", y: "+=3" });
    tl2.to(
      $circlesBottomRight.eq(1),
      0.1,
      { scale: 0.8, x: "+=7", y: "+=3" },
      "-=0.1"
    );
    tl2.to(
      $circlesBottomRight.eq(2),
      0.1,
      { scale: 0.2, x: "+=15", y: "-=6" },
      "-=0.2"
    );
    tl2.to($circlesBottomRight.eq(0), 1, {
      scale: 0,
      x: "+=5",
      y: "+=15",
      opacity: 0,
    });
    tl2.to(
      $circlesBottomRight.eq(1),
      1,
      { scale: 0.4, x: "+=7", y: "+=7", opacity: 0 },
      "-=1"
    );
    tl2.to(
      $circlesBottomRight.eq(2),
      1,
      { scale: 0, x: "+=15", y: "-=5", opacity: 0 },
      "-=1"
    );

    tlBt2.set($circlesBottomRight, { x: 0, y: 0, rotation: 45 });
    tlBt2.add(tl2);

    btTl.add(tlBt1);
    btTl.to(
      $(this).parent().find(".button.effect-button"),
      0.8,
      { scaleY: 1.1 },
      0.1
    );
    btTl.add(tlBt2, 0.2);
    btTl.to(
      $(this).parent().find(".button.effect-button"),
      1.8,
      { scale: 1, ease: Elastic.easeOut.config(1.2, 0.4) },
      1.2
    );

    btTl.timeScale(2.6);

    $(this).on("mouseover", function () {
      btTl.restart();
    });
  });
}

// const collision = ($fix, $moving) => {
//   var x1 = $fix.offset().left,
//     y1 = $fix.offset().top,
//     h1 = $fix.outerHeight(true),
//     w1 = $fix.outerWidth(true),
//     b1 = y1 + h1,
//     r1 = x1 + w1,
//     x2 = $moving.offset().left,
//     y2 = $moving.offset().top,
//     h2 = $moving.outerHeight(true),
//     w2 = $moving.outerWidth(true),
//     b2 = y2 + h2,
//     r2 = x2 + w2;

//   if (b1 < y2 || y1 > b2 || r1 < x2 || x1 > r2) return false;
//   return true;
// };

// // Change color when overlaping same color (Logo & Burger menu)
// // const stylesheet = document.styleSheets[8];

// $(window).scroll(() => {
//   const all = $(".moving");
//   const appendToBurger = $("#trigger").css({
//     width: "5rem",
//     height: "5rem",
//     "background-color": yellow,
//     position: "absolute",
//     right: "1rem",
//     top: "1.7rem",
//   });

//   for (let i = 0; i < all.length; i++) {
//     if (collision($(".fix"), all.eq(i)) && collision($("#burger"), all.eq(i))) {
//       $(".fix").css("color", yellow);
//       console.log("not overlaping");
//       appendToBurger.css("opacity", 0.7);

//       // appendToBurger.css('opacity', .7);
//       // $("#burger").css("background", yellow);
//       // stylesheet.addRule("#burger::before", `background-color: ${yellow}`);
//       // stylesheet.insertRule(
//       //   `#burger::before { background-color: ${yellow} }`);
//       // stylesheet.addRule("#burger::after", `background-color: ${yellow}`);
//       // stylesheet.insertRule(
//       //   `#burger::after { background-color: ${yellow} }`);
//       break;
//     } else {
//       $(".fix").css("color", black);
//       console.log("overlaping");
//       appendToBurger.css("opacity", "0");

//       // appendToBurger;
//       // $("#burger").css("background", black);
//       // stylesheet.addRule("#burger::before", `background-color: ${black}`);
//       // stylesheet.insertRule(
//       //   `#burger::before { background-color: ${black} }`);
//       // stylesheet.addRule("#burger::after", `background-color: ${black}`);
//       // stylesheet.insertRule(`#burger::after { background-color: ${black} }`);
//     }
//   }
// });

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
      ? document.querySelector("html").setAttribute("data-theme", "dark")
      : document.querySelector("html").removeAttribute("data-theme");
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
