// FUCK pieces parallax
$("#wrapper").mousemove((e) => {
  parallaxIt(e, ".keyF", -40);
  parallaxIt(e, ".keyU", 100);
  parallaxIt(e, ".keyK", -90);
  parallaxIt(e, ".keyC", -25);
  parallaxIt(e, ".wrapper__sites", -45);
});

$('.hero__projects').mousemove((e) => {
  parallaxIt(e, ".model-human, .model-book, .model-laptop, .model-contact", 45);
});

const parallaxIt = (e, target, movement) => {
  const wrapper = $("#wrapper, .hero__projects");
  const relX = e.pageX - wrapper.offset().left;
  const relY = e.pageY - wrapper.offset().top;

  TweenMax.to(target, 1, {
    x: ((relX - wrapper.width() / 2) / wrapper.width()) * movement,
    y: ((relY - wrapper.height() / 2) / wrapper.height()) * movement,
  });
};