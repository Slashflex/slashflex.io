// Project > Attachment collection
$("#add-images-btn").click(function () {
  const index = +$("#widgets-counter").val();
  const template = $("#project_attachments")
    .data("prototype")
    .replace(/__name__/g, index);

  $("#project_attachments").append(template);
  $("#widgets-counter").val(index + 1);

  deleteImageBtn();
});

// Delete a specific .form-group block
const deleteImageBtn = () => {
  $("button[data-action='delete']").click(function () {
    const target = $(this).data("target");
    console.log(target);
    $(target).remove();
  });
};

const updateCounter = () => {
  const formGroupCounter = $("#project_attachments .form-group").length;
  $("#widgets-counter").val(formGroupCounter);
};

deleteImageBtn();

updateCounter();