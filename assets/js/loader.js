const removeLoader = () => {
  $("#loading-1").slideUp(500, () => {
    $(this).remove();
  });
  $("#loading-2").slideUp(500, () => {
    $(this).remove();
  });
  $(".loader").slideUp(450);
};

$("body").append(
  `<div class="loader"></div>
  <div id="loading-1"></div>
  <div id="loading-2"></div>`
);

$(window).on("load", function () {
  setTimeout(removeLoader, 300); // Wait for page load
});