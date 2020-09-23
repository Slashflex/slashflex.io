// Loader with random quotes
const removeLoader = () => {
  $("#loading").fadeOut(500, () => {
    // FadeOut complete. Remove the loading div
    $(this).remove(); // Makes page more lightweight
  });
};

if (!window.location.pathname.includes("/admin")) {
  const sentence = [
    `“Knowledge is power.”<i class="loading__sentence"><span style="color: ${yellow}"> – </span>Francis Bacon</i>`,
    `“Simplicity is the soul of efficiency.”<i class="loading__sentence"><span style="color: ${yellow}"> – </span>Austin Freeman</i>`,
    `“Make it work, make it right, make it fast.”<i class="loading__sentence"><span style="color: ${yellow}"> – </span>Kent Beck</i>`,
    `“Talk is cheap. Show me the code.“<i class="loading__sentence"><span style="color: ${yellow}"> – </span>Linus Torvalds</i>`,
    `“Software is like sex: It’s better when it’s free.“<i class="loading__sentence"><span style="color: ${yellow}"> – </span>Linus Torvalds</i>`,
    `“The Internet? Is that thing still around?”<i class="loading__sentence"><span style="color: ${yellow}"> – </span>Homer Simpson</i>`,
    `“I find your lack of faith disturbing.”<i class="loading__sentence"><span style="color: ${yellow}"> – </span>Darth Vader</i>`,
    `“Do. Or do not. There is no try.”<i class="loading__sentence"><span style="color: ${yellow}"> – </span>Yoda</i>`,
    `“We meet again at last.”<i class="loading__sentence"><span style="color: ${yellow}"> – </span>Darth Vader</i>`,
  ]; // Get a random index from the array sentence
  const randomNumber = Math.floor(Math.random() * sentence.length); // Get a random sentence from randomNumber

  const quote = sentence[randomNumber];
  $("body").append(
    `<div style="width: 100%; background: ${black}; color: ${white}; margin-bottom: 40vh; height: 100%; position: fixed;" id="loading"><div class="loader"></div></div>`
  ); // Displays a random quote inside the loading div

  $("#loading").append(
    `<p class="text-center h1 loading__text">`.concat(quote, "</p>")
  );
  $(window).on("load", function() {
    setTimeout(removeLoader, 600); // Wait for page load
  });
}