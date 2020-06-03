$(document).ready(function () {
  $("#add-content-btn").click(function () {
    const index = +$("#content-counter").val();

    const template = $("#article_content")
      .data("prototype")
      .replace(/__name__/g, index);
    console.log(template);
    $("#article_content").append(template);
    $("#content-counter").val(index + 1);

    deleteContentBtn();
  });

  // Delete a specific .form-group block
  const deleteContentBtn = () => {
    $("button[data-action='delete']").click(function () {
      const target = $(this).data("target");
      console.log(target);
      $(target).remove();
    });
  };

  const updateCounter = () => {
    const formGroupCounter = $("#article_content .form-group").length;
    $("#content-counter").val(formGroupCounter);
  };

  deleteContentBtn();

  updateCounter();
});
