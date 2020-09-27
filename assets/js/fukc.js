// FUCK pieces parallax
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