function selectTime(option) {
  var customFieldInput = document.querySelector(".custom-field");
  document.querySelector(".time-dropdown-button").textContent = option;
  if (option === "Duration") {
    customFieldInput.style.display = "block";
  } else {
    customFieldInput.style.display = "none";
  }
}

document.addEventListener("DOMContentLoaded", function () {
  var dropdownButton = document.querySelector(".budget-dropdown-button");
  var dropdownContent = document.querySelector(".budget-dropdown-content");

  dropdownButton.addEventListener("click", function () {
    dropdownContent.style.display =
      dropdownContent.style.display === "block" ? "none" : "block";
  });

  // Close the dropdown if the user clicks outside of it
  window.addEventListener("click", function (event) {
    if (!event.target.matches(".budget-dropdown-button")) {
      dropdownContent.style.display = "none";
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  var dropdownButton = document.querySelector(".time-dropdown-button");
  var dropdownContent = document.querySelector(".time-dropdown-content");

  dropdownButton.addEventListener("click", function () {
    dropdownContent.style.display =
      dropdownContent.style.display === "block" ? "none" : "block";
  });

  // Close the dropdown if the user clicks outside of it
  window.addEventListener("click", function (event) {
    if (!event.target.matches(".time-dropdown-button")) {
      dropdownContent.style.display = "none";
    }
  });
});

function selectBudget(budget) {
  document.querySelector(".budget-dropdown-button").textContent = budget;
  if (budget === "Budget") {
    document.querySelector(".custom-field-input").style.display = "block";
  } else {
    document.querySelector(".custom-field-input").style.display = "none";
  }
}

/* Password Field */
document.addEventListener("DOMContentLoaded", function () {
  const passwordField = document.getElementById("password-field");
  const toggleIcon = document.getElementById("toggle-icon");
  const togglePassword = () => {
    if (passwordField.type === "password") {
      passwordField.type = "text";
      toggleIcon.classList.remove("fa-eye");
      toggleIcon.classList.add("fa-eye-slash");
    } else {
      passwordField.type = "password";
      toggleIcon.classList.remove("fa-eye-slash");
      toggleIcon.classList.add("fa-eye");
    }
  };

  if (toggleIcon) {
    toggleIcon.addEventListener("click", togglePassword);
  }
});

document
  .getElementById("addFieldButton")
  .addEventListener("click", function () {
    var newFieldContainer = document.createElement("div");

    var newField = document.createElement("textarea");
    newField.type = "textarea";
    newField.placeholder = "Write Your Requirement?";
    // newField.name = "requirement";
    newField.classList.add("custom-requirement-field");

    var removeButton = document.createElement("button");
    removeButton.textContent = "Remove";
    removeButton.addEventListener("click", function () {
      newFieldContainer.remove();
    });

    newFieldContainer.appendChild(newField);
    newFieldContainer.appendChild(removeButton);

    document.getElementById("fieldContainer").appendChild(newFieldContainer);
  });

// jQuery Code here
(function ($) {
  $(document).ready(function () {
    // Nice select
    $("select").niceSelect();

    // Step up functionalities
    $(document).on("click", ".next-step", function (event) {
      event.preventDefault();
      currentStep++;
      if (currentStep < steps.length) {
        showStep(currentStep);
      } else {
        // Handle form submission or completion here
      }
    });

    // Client site form validation
    // Display Error message
    $("#submit-btn").on("click", function (e) {
      e.preventDefault();

      // Get form data
      var software = $('input[name="software"]:checked').val();
      var website = $('input[name="website"]:checked').val();
      var mobileApp = $('input[name="mobile-app"]:checked').val();
      var requirement = $("#requirement").val();
      var firstName = $('input[name="first-name"]').val();
      var address = $('input[name="address"]').val();
      var email = $('input[name="email"]').val();
      var number = $('input[name="number"]').val();
      var watsAppNumber = $('input[name="whats-app-number"]').val();

      if (
        requirement === "" ||
        firstName === "" ||
        address === "" ||
        email === "" ||
        number === "" ||
        watsAppNumber === ""
      ) {
        $(".error-message").fadeIn();
        $(".success-message").fadeOut();
        setTimeout(() => {
          $(".error-message").fadeOut();
        }, 2000);
      } else {
        $(".success-message").fadeIn();
        $(".error-message").fadeOut();
        setTimeout(() => {
          $(".success-message").fadeOut();
        }, 2000);
      }
    });

    // Save Form Data with AJAX
    var selectedBudget;
    var selectDeadline;
    $("#submit-btn").on("click", function (e) {
      e.preventDefault();

      // Get form data
      var software = $('input[name="software"]:checked').val();
      var website = $('input[name="website"]:checked').val();
      var mobileApp = $('input[name="mobile-app"]:checked').val();
      var requirement = $("#requirement").val();
      var firstName = $('input[name="first-name"]').val();
      var address = $('input[name="address"]').val();
      var email = $('input[name="email"]').val();
      var number = $('input[name="number"]').val();
      var watsAppNumber = $('input[name="whats-app-number"]').val();

      // Custom requirement field value get conditionally
      var newRequirement = $(".custom-requirement-field").val();
      var toUseNewRequirement = newRequirement !== "" ? newRequirement : null;

      // Custom budget field value get conditionally
      var customBudgetPlanner = $("#budget_planer_custom_field").val();
      var toUseCustomBudgetPlanner =
        customBudgetPlanner !== "" ? customBudgetPlanner : null;

      // Custom deadline field value get conditionally
      var customProjectDeadline = $("#project_deadline_custom_field").val();
      var toUseCustomProjectDeadline =
        customProjectDeadline !== "" ? customProjectDeadline : null;

      $.ajax({
        type: "POST",
        url: "../inc/database.php",
        data: {
          software: software,
          website: website,
          mobileApp: mobileApp,
          requirement: requirement,
          newRequirement: toUseNewRequirement, // added newRequirement
          firstName: firstName,
          address: address,
          email: email,
          number: number,
          watsAppNumber: watsAppNumber,
          budget: selectedBudget,
          customBudget: toUseCustomBudgetPlanner, // added customBudget
          deadline: selectDeadline,
          customProjectDeadline: toUseCustomProjectDeadline, // added customProjectDeadline
        },
        success: function (response) {
          // Clear form data
        },
      });
    });

    // Clear form field value after form submit
    function clearInputField() {
      console.log("cleaning input field");
      // Clear the input fields
      $('input[name="software"]').prop("checked", false);
      $('input[name="website"]').prop("checked", false);
      $('input[name="mobile-app"]').prop("checked", false);
      $("#requirement").val("");
      $('input[name="first-name"]').val("");
      $('input[name="address"]').val("");
      $('input[name="email"]').val("");
      $('input[name="number"]').val("");
      $('input[name="whats-app-number"]').val("");
      $(".custom-requirement-field").val("");
      $("#budget_planer_custom_field").val("");
      $("#project_deadline_custom_field").val("");
    }

    // Form clear after submit form
    $("#submit-btn").click(function (e) {
      e.preventDefault();

      clearInputField();
    });

    // Budget Dropdown item select
    $(".budget-dropdown-content a").click(function (e) {
      e.preventDefault(); // Prevent the default link behavior

      selectedBudget = $(this).text().trim(); // Get the text of the clicked link
    });

    // Select Dead line
    $(".time-dropdown-content a").click(function (e) {
      e.preventDefault(); // Prevent the default link behavior

      selectDeadline = $(this).text().trim();
    });
  });
})(jQuery);
