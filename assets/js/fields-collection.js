$(document).ready(function () {
    $("#add-content-btn").click(function () {
      const index = +$("#content-counter").val();
  
      const template = $("#project_content")
        .data("prototype")
        .replace(/__name__/g, index);
      console.log(template);
      $("#project_content").append(template);
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
      const formGroupCounter = $("#project_content .form-group").length;
      $("#content-counter").val(formGroupCounter);
    };
  
    deleteContentBtn();
  
    updateCounter();
  
  });
  