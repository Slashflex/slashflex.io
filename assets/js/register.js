// Remove radio button error message if checked
$(".form__radio-input").bind("click", function () {
  var $t = $(this);

  if ($t.is(":checked")) {
    $(".agree-message", ".agree").empty().remove();
  }
});