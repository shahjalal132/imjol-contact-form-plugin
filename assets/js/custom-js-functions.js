// get steps buttons
let firstStep = document.querySelector(".first-step");
let secondStep = document.querySelector(".second-step");
let thirdStep = document.querySelector(".third-step");
let fourthStep = document.querySelector(".fourth-step");

// get first step fields values
const websiteValue = document.querySelector('input[name="website"]');
const mobileAppValue = document.querySelector('input[name="mobile-app"]');
const softwareValue = document.querySelector('input[name="software"]');

// get requirement field value
const requirementValue = document.querySelector("#requirement");

let errorCheckWebAppSoft = document.querySelector(".error-check-web-app-soft");
let errorRequirementMessage = document.querySelector(
  ".error-requirement-message"
);
let errorBudgetMessage = document.querySelector(".error-budget-message");
let errorDeadlineMessage = document.querySelector(".error-deadline-message");

// jQuery code
(function ($) {
  $(document).ready(function () {
    const form = $("#multiStepForm");
    const steps = form.find(".tab-pane");
    const links = $(".formify-form__nav a"); // Updated selector to target navigation links
    const progress = $(".progress-bar");
    const completionPercent = $(".formify-form__quiz-banner-progress--percent");

    let currentStep = 0;

    function showStep(stepIndex) {
      steps.removeClass("show active");
      $(steps[stepIndex]).addClass("show active");
      links.removeClass("active");
      $(links[stepIndex]).addClass("active");
      updateProgress();
      $(".formify-form__quiz-current").addClass("zoom-out");
      setTimeout(function () {
        $(".formify-form__quiz-current")
          .text(stepIndex + 1)
          .removeClass("zoom-out")
          .addClass("zoom-in");
      }, 300);
      updateCompletionPercent();
    }

    function updateProgress() {
      const percent = (currentStep / (steps.length - 1)) * 100;
      progress.css("width", percent + "%");
    }

    function updateCompletionPercent() {
      const percent = ((currentStep + 1) / steps.length) * 100;
      completionPercent.text(`${percent.toFixed(0)}% Complete`);
    }

    var choiceBudget;
    var choiceDateline;
    // Budget Dropdown item select
    $(".budget-dropdown-content a").click(function (e) {
      e.preventDefault(); // Prevent the default link behavior

      choiceBudget = $(this).text().trim(); // Get the text of the clicked link
    });

    // Select Dead line
    $(".time-dropdown-content a").click(function (e) {
      e.preventDefault(); // Prevent the default link behavior

      choiceDateline = $(this).text().trim();
    });

    // next step functionality
    $(".next-step").click(function (event) {
      event.preventDefault();

      if (
        currentStep === 0 &&
        websiteValue.checked === false &&
        mobileAppValue.checked === false &&
        softwareValue.checked === false
      ) {
        errorCheckWebAppSoft.style.display = "block";
        setTimeout(function () {
          errorCheckWebAppSoft.style.display = "none";
        }, 4000);
        return false;
      }

      if (currentStep === 1 && requirementValue.value.trim() === "") {
        // Check if requirementValue is empty
        errorRequirementMessage.style.display = "block";
        setTimeout(function () {
          errorRequirementMessage.style.display = "none";
        }, 4000);
        return false;
      }

      if (currentStep === 2 && !choiceBudget) {
        // Check if choiceBudget is empty
        errorBudgetMessage.style.display = "block";
        setTimeout(function () {
          errorBudgetMessage.style.display = "none";
        }, 4000);
        return false;
      }

      if (currentStep === 3 && !choiceDateline) {
        // Check if choiceBudget is empty
        errorDeadlineMessage.style.display = "block";
        setTimeout(function () {
          errorDeadlineMessage.style.display = "none";
        }, 4000);
        return false;
      }

      currentStep++;

      if (currentStep < steps.length) {
        showStep(currentStep);
      } else {
        // Handle form submission or completion here
      }
    });

    $(".prev-step").click(function (event) {
      event.preventDefault();
      currentStep--;
      if (currentStep >= 0) {
        showStep(currentStep);
      }
    });

    links.click(function (event) {
      // Added click event for navigation links
      event.preventDefault();
      const clickedStep = links.index(this);
      if (clickedStep >= 0 && clickedStep < steps.length) {
        currentStep = clickedStep;
        showStep(currentStep);
      }
    });

    showStep(currentStep);
  });
})(jQuery);
