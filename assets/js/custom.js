$(document).ready(() => {
  // Removes Download link
  if (window.location.pathname.endsWith("new")) {
    const download = document.querySelectorAll("a[download]");
    download[1].style.display = "none";
  }

  const files = document.querySelectorAll('input[type="file"]');
  files.forEach((e) => {
    e.classList.add("inputfile");
  });

  // Swiper
  const swiper = new Swiper(".swiper-container", {
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
  }, 8000);

  setInterval(() => {
    $(".flash-notice-error").slideUp().fadeOut();
  }, 8000);
});

const warningIcon = '<i class="fas fa-exclamation-circle"></i>';

// Add warning icons if errors on form inputs
if ($(".form__errors").children().length > 0) {
  let ul = $(".form__errors").children();
  ul.children().prepend(`${warningIcon} `);
  // ul.children().append(` ${warningIcon}`);
  $(ul).parent().css("border-bottom", "2px solid #eb4d4b");

  // Remove errors when input value is >= 6
  $("input").keypress(function () {
    if ($(this).val().length >= 6) {
      $(ul).parent().css("border-bottom", "2px solid transparent");
      ul.detach();
    }
  });
}

// Add radio button error message on form submit if not checked
$(".button__login").click(function () {
  if ($(".form__radio-input").is(":checked")) {
    $(".agree-message", ".agree").empty().remove();
  } else {
    let message = window.localStorage.getItem("agree");
    $(".agree").append(
      `<li class="agree-message">${warningIcon} ${message} ${warningIcon}</li>`
    );
  }
});

// Cookie consent policy
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
    href: "https://slashflex.io/terms-of-use",
  },
});

if (
  window.location.pathname === "/" ||
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

// Gooey button
$(".button--bubble").each(function () {
  const $circlesTopLeft = $(this).parent().find(".circle.top-left");
  const $circlesBottomRight = $(this).parent().find(".circle.bottom-right");

  const tl = new TimelineLite();
  const tl2 = new TimelineLite();
  const btTl = new TimelineLite({
    paused: true,
  });

  tl.to($circlesTopLeft, 1.2, {
    x: -25,
    y: -25,
    scaleY: 2,
    ease: SlowMo.ease.config(0.1, 0.7, false),
  });
  tl.to($circlesTopLeft.eq(0), 0.1, {
    scale: 0.2,
    x: "+=6",
    y: "-=2",
  });
  tl.to(
    $circlesTopLeft.eq(1),
    0.1, {
      scaleX: 1,
      scaleY: 0.8,
      x: "-=10",
      y: "-=7",
    },
    "-=0.1"
  );
  tl.to(
    $circlesTopLeft.eq(2),
    0.1, {
      scale: 0.2,
      x: "-=15",
      y: "+=6",
    },
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
    1, {
      scaleX: 0.4,
      scaleY: 0.4,
      x: "-=10",
      y: "-=10",
      opacity: 0,
    },
    "-=1"
  );
  tl.to(
    $circlesTopLeft.eq(2),
    1, {
      scale: 0,
      x: "-=15",
      y: "+=5",
      opacity: 0,
    },
    "-=1"
  );

  const tlBt1 = new TimelineLite();
  const tlBt2 = new TimelineLite();

  tlBt1.set($circlesTopLeft, {
    x: 0,
    y: 0,
    rotation: -45,
  });
  tlBt1.add(tl);

  tl2.set($circlesBottomRight, {
    x: 0,
    y: 0,
  });
  tl2.to($circlesBottomRight, 1.1, {
    x: 30,
    y: 30,
    ease: SlowMo.ease.config(0.1, 0.7, false),
  });
  tl2.to($circlesBottomRight.eq(0), 0.1, {
    scale: 0.2,
    x: "-=6",
    y: "+=3",
  });
  tl2.to(
    $circlesBottomRight.eq(1),
    0.1, {
      scale: 0.8,
      x: "+=7",
      y: "+=3",
    },
    "-=0.1"
  );
  tl2.to(
    $circlesBottomRight.eq(2),
    0.1, {
      scale: 0.2,
      x: "+=15",
      y: "-=6",
    },
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
    1, {
      scale: 0.4,
      x: "+=7",
      y: "+=7",
      opacity: 0,
    },
    "-=1"
  );
  tl2.to(
    $circlesBottomRight.eq(2),
    1, {
      scale: 0,
      x: "+=15",
      y: "-=5",
      opacity: 0,
    },
    "-=1"
  );

  tlBt2.set($circlesBottomRight, {
    x: 0,
    y: 0,
    rotation: 45,
  });
  tlBt2.add(tl2);

  btTl.add(tlBt1);
  btTl.to(
    $(this).parent().find(".button.effect-button"),
    0.8, {
      scaleY: 1.1,
    },
    0.1
  );
  btTl.add(tlBt2, 0.2);
  btTl.to(
    $(this).parent().find(".button.effect-button"),
    1.8, {
      scale: 1,
      ease: Elastic.easeOut.config(1.2, 0.4),
    },
    1.2
  );

  btTl.timeScale(2.6);

  $(this).on("mouseover", function () {
    btTl.restart();
  });
});
