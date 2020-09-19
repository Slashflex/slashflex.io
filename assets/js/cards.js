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
