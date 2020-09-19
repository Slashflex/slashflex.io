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

// Chess pieces parallax
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
