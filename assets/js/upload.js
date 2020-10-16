const btnUpload = $("#avatar_upload_avatar");
const btnUploadImageFile = $("#project_imageFile_file");
const btnOuter = $(".button_outer");

btnUpload.on("change", function (e) {
  const ext = btnUpload.val().split(".").pop().toLowerCase();

  if ($.inArray(ext, ["gif", "webp", "png", "jpg", "jpeg"]) === -1) {
    $(".error_msg").text("Please upload a valid document (jpg, jpeg, gif, webp or png)");
  } else {
    $(".error_msg").text("");
    btnOuter.addClass("file_uploading");

    setTimeout(function () {
      btnOuter.addClass("file_uploaded");
    }, 3000);

    const uploadedFile = URL.createObjectURL(e.target.files[0]);
    setTimeout(function () {
      $("#uploaded_view")
        .append('<img src="' + uploadedFile + '"  alt="uploaded image"/>')
        .addClass("show");
    }, 3500);
  }
});

btnUploadImageFile.on("change", function (e) {
  const ext = btnUploadImageFile.val().split(".").pop().toLowerCase();

  if ($.inArray(ext, ["gif", "webp", "png", "jpg", "jpeg"]) === -1) {
    $(".error_msg").text("Please upload a valid document (jpg, jpeg, gif, webp or png)");
  } else {
    $(".error_msg").text("");
    btnOuter.addClass("file_uploading");

    setTimeout(function () {
      btnOuter.addClass("file_uploaded");
    }, 3000);

    const uploadedFile = URL.createObjectURL(e.target.files[0]);
    setTimeout(function () {
      $("#uploaded_view")
        .append('<img src="' + uploadedFile + '"  alt="uploaded image"/>')
        .addClass("show");
    }, 3500);
  }
});

$(".file_remove").on("click", function () {
  $("#uploaded_view").removeClass("show");
  $("#uploaded_view").find("img").remove();
  btnOuter.removeClass("file_uploading");
  btnOuter.removeClass("file_uploaded");
});