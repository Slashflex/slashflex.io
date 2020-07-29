$(document).ready(() => {
  // Removes Download link
  if (
    window.location.pathname.endsWith("new") ||
    window.location.pathname.endsWith("edit")
  ) {
    const download = document.querySelectorAll("a[download]");
    download[1].style.display = "none";
  }

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

  $("#wrapper").mousemove(function (e) {
    parallaxIt(e, ".keyF", -10);
    parallaxIt(e, ".keyU", 10);
    parallaxIt(e, ".keyK", -15);
    parallaxIt(e, ".keyC", 15);
  });

  const parallaxIt = (e, target, movement) => {
    var $this = $("#wrapper");
    var relX = e.pageX - $this.offset().left;
    var relY = e.pageY - $this.offset().top;

    TweenMax.to(target, 1, {
      x: ((relX - $this.width() / 2) / $this.width()) * movement,
      y: ((relY - $this.height() / 2) / $this.height()) * movement,
    });
  };

  // Swiper
  var swiper = new Swiper(".swiper-container", {
    loop: true,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
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
  $("#menu-toggle").click(function () {
    $(this).toggleClass("active");
    if ($(this).hasClass("active")) {
      $(".nav").css("position", "static");
    } else {
      $(".nav").css("position", "fixed");
    }
    $("#menu").toggleClass("open");
  });

  // Flash message
  setInterval(function () {
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

  if (window.location.pathname == "/projects") {
    for (let i = 1; i < 15; i++) {
      let back = document.querySelector(`.back${[i]}`);
      $(back).css("background-color", "#27282c");
    }
    $(".round").css("background-color", "#ffbe41");
  }
});

// Loader only on root page
if (
  window.location.pathname == "/" ||
  window.location.pathname == "/projects" ||
  window.location.pathname == "/articles"
) {
  var sentence = [
    '“Knowledge is power.” – <i style="color: #585858; font-size: 1.9rem">Francis Bacon</i>',
    '“Simplicity is the soul of efficiency.” – <i style="color: #585858; font-size: 1.9rem">Austin Freeman</i>',
    '“Make it work, make it right, make it fast.” – <i style="color: #585858; font-size: 1.9rem">Kent Beck</i>',
    '“Talk is cheap. Show me the code.“ – <i style="color: #585858; font-size: 1.9rem">Linus Torvalds</i>',
    '“Software is like sex: It’s better when it’s free.“ – <i style="color: #585858; font-size: 1.9rem">Linus Torvalds</i>',
    '“The Internet? Is that thing still around?” - <i style="color: #585858; font-size: 1.9rem">Homer Simpson</i>',
    '“I find your lack of faith disturbing.” - <i style="color: #585858; font-size: 1.9rem">Darth Vader</i>',
    '“Do. Or do not. There is no try.” - <i style="color: #585858; font-size: 1.9rem">Yoda</i>',
    '“We meet again at last.” - <i style="color: #585858; font-size: 1.9rem">Darth Vader</i>',
  ]; // Get a random index from the array sentence
  var randomNumber = Math.floor(Math.random() * sentence.length); // Get a random sentence from randomNumber

  var quote = sentence[randomNumber];
  $("body").append(
    '<div style="width: 100%; background: #27282c; color: #fff; margin-bottom: 40vh; height: 100%; position: fixed;" id="loading"><div class="loader"></div></div>'
  ); // Displays a random quote inside the loading div

  $("#loading").append(
    '<p class="text-center h1" style="color: #ffbe41; margin-bottom: 33rem;">'.concat(
      quote,
      "</p>"
    )
  );
  $(window).on("load", function () {
    setTimeout(removeLoader, 600); // Wait for page load
  });

  var removeLoader = function removeLoader() {
    $("#loading").fadeOut(500, function () {
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
