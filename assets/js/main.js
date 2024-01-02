/* Password Field */
document.addEventListener('DOMContentLoaded', function() {
    const passwordField = document.getElementById('password-field');
    const toggleIcon = document.getElementById('toggle-icon');
    const togglePassword = () => {
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    };
    
    if (toggleIcon) {
        toggleIcon.addEventListener('click', togglePassword);
    }
});


jQuery(document).ready(function($) {
        /*====================================
			Select2 JS
		======================================*/ 
		$('select').niceSelect();

	
});



$(document).on('click', '.next-step', function (event) {
    event.preventDefault();
    currentStep++;
    if (currentStep < steps.length) {
        showStep(currentStep);
    } else {
        // Handle form submission or completion here
    }

});
document.getElementById("addFieldButton").addEventListener("click", function () {

    var newFieldContainer = document.createElement("div");
    
    var newField = document.createElement("textarea");
    newField.type = "textarea";
    newField.placeholder = "Write Your Requirement?";
    
    var removeButton = document.createElement("button");
    removeButton.textContent = "Remove";
    removeButton.addEventListener("click", function () {
        newFieldContainer.remove();
    });
    
    newFieldContainer.appendChild(newField);
    newFieldContainer.appendChild(removeButton);
    
    document.getElementById("fieldContainer").appendChild(newFieldContainer);
});

document.addEventListener("DOMContentLoaded", function () {
    var dropdownButton = document.querySelector(".budget-dropdown-button");
    var dropdownContent = document.querySelector(".budget-dropdown-content");

    dropdownButton.addEventListener("click", function () {
        dropdownContent.style.display = (dropdownContent.style.display === "block") ? "none" : "block";
    });

    // Close the dropdown if the user clicks outside of it
    window.addEventListener("click", function (event) {
        if (!event.target.matches('.budget-dropdown-button')) {
            dropdownContent.style.display = 'none';
        }
    });
});

function selectBudget(budget) {
    document.querySelector(".budget-dropdown-button").textContent = budget;
    if (budget === 'Budget') {
        document.querySelector(".custom-field-input").style.display = "block";
    } else {
        document.querySelector(".custom-field-input").style.display = "none";
    }
}

//new field

document.addEventListener("DOMContentLoaded", function () {
    var dropdownButton = document.querySelector(".time-dropdown-button");
    var dropdownContent = document.querySelector(".time-dropdown-content");

    dropdownButton.addEventListener("click", function () {
        dropdownContent.style.display = (dropdownContent.style.display === "block") ? "none" : "block";
    });

    // Close the dropdown if the user clicks outside of it
    window.addEventListener("click", function (event) {
        if (!event.target.matches('.time-dropdown-button')) {
            dropdownContent.style.display = 'none';
        }
    });
});

function selectTime(option) {
    var customFieldInput = document.querySelector(".custom-field");
    document.querySelector(".time-dropdown-button").textContent = option;
    if (option === 'Duration') {
        customFieldInput.style.display = "block";
    } else {
        customFieldInput.style.display = "none";
    }
}



