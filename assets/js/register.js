// Remove radio button error message if checked
$(".form__radio-input").bind("click", function () {
  const $t = $(this);

  if ($t.is(":checked")) {
    $(".agree-message", ".agree").empty().remove();
  }
});