const black = "#27282c";

for (let i = 1; i < 15; i++) {
  let back = document.querySelector(`.back${[i]}`);
  $(back).css("background-color", black);
}

$(".card-content--image").css("background-size", "cover");

// Specific media queries
if (window.innerHeight > window.innerWidth) {
  // iphone 5/SE -> portrait
  if (window.innerWidth <= 320) {
    $(".round").css("top", "8.5%");
    $(".model-laptop").css("width", "88%");
    $(".wrapper__sites").css({
      marginTop: "0rem"
    });
  }
  // iphone 6/7/8 -> portrait
  if (window.innerWidth >= 321 && window.innerWidth <= 375) {
    $(".round").css({
      top: "7.8%",
      width: "30rem",
      height: "30rem",
      marginLeft: "-14.5rem",
    });
    $(".model-laptop").css("width", "88%");
    $(".wrapper__sites").css({
      marginTop: "-4rem",
      fontSize: "9.5rem"
    });
  }
  // iphone 6/7/8 Plus -> portrait
  if (window.innerWidth >= 376 && window.innerWidth <= 414) {
    $(".round").css({
      top: "9.8%",
      marginLeft: "-13.5rem",
      width: "28rem",
      height: "28rem",
    });
    $(".model-laptop").css("width", "80%");
    $(".wrapper__sites").css({
      fontSize: "10rem",
      marginBottom: "7rem"
    });
  }
  // iphone X -> portrait
  if (window.innerWidth == 375 && window.innerHeight == 812) {
    $(".round").css({
      top: "10.8%",
      marginLeft: "-13.8rem",
      width: "28rem",
      height: "28rem",
    });
    $(".wrapper__sites").css({
      fontSize: "10.5rem"
    });
  }
} else {
  // iphone 5/SE -> landscape
  if (window.innerWidth <= 568) {
    $(".round").css({
      top: "3.5%",
      marginLeft: "-9.8rem",
      width: "20rem",
      height: "20rem",
    });
    $(".wrapper__sites").css({
      fontSize: "9rem"
    });
  }
  // iphone 6/7/8 -> landscape
  if (window.innerWidth >= 569 && window.innerWidth <= 667) {
    $(".round").css({
      top: "4%",
      marginLeft: "-11.5rem",
      width: "23rem",
      height: "23rem",
    });
    $(".wrapper__sites").css({
      fontSize: "9rem"
    });
  }
  // iphone 6/7/8 Plus -> landscape
  if (window.innerWidth >= 668 && window.innerWidth <= 736) {
    $(".round").css({
      top: "4.5%",
      marginLeft: "-12.5rem",
      width: "25rem",
      height: "25rem",
    });
    $(".wrapper__sites").css({
      fontSize: "9rem"
    });
  }
  // iphone X -> landscape
  if (window.innerWidth >= 737 && window.innerWidth <= 812) {
    $(".round").css({
      top: "3.8%",
      marginLeft: "-12.5rem",
      width: "25rem",
      height: "25rem",
    });
    $(".wrapper__sites").css({
      fontSize: "9rem"
    });
  }
}