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

    $(".next-step").click(function (event) {
      event.preventDefault();
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
