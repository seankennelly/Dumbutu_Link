$(document).ready(function () {
  // PHP Mailer function
  // Accepts formType argument which changes Mailer output depending on form
  const sendEmail = (formType, formData) => {
    $(".sent-message, .error-message").removeClass("d-block").addClass("d-none");
    $(".loading").removeClass("d-none").addClass("d-block");
    
    formData += `&formType=${formType}`;
    // Update formType for form reset in AJAX
    formType = "#" + formType + "-form";
    // console.log("PRE AJAX FORM DATA: ", formData);
    $.ajax({
      type: "POST",
      url: "libraries/php/send_email.php",
      data: formData,
      success: function (response) {
        // console.log(response)
        $(".loading").removeClass("d-block").addClass("d-none");
        $(".sent-message").removeClass("d-none").addClass("d-block");
        $(formType)[0].reset();
      },
      error: function (error) {
        console.log(error);
        $(".loading").removeClass("d-block").addClass("d-none");
        $(".error-message").removeClass("d-none").addClass("d-block").text("An error occurred while sending the message. Please try again.");
      },
    });
  };

  // Event handler for Contact Form
  $("#contact-form").submit(function (e) {
    e.preventDefault();
    let formData = $(this).serialize();
    sendEmail("contact", formData);
  });

  // Event handler for Donate Form
  $("#donate-form").submit(function (e) {
    e.preventDefault();
    let formData = $(this).serializeArray();
    // Add checkbox values manually based on their checked state
    formData.push({
      name: "giftAid",
      value: $("#giftAid").is(":checked") ? "Yes" : "No",
    });
    formData.push({
      name: "commsConsent",
      value: $("#commsConsent").is(":checked") ? "Yes" : "No",
    });
    // Convert formData array to URL-encoded string
    formData = $.param(formData);
    sendEmail("donate", formData);
  });
});
