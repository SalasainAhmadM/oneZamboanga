// const form = document.querySelector("form"),
//         nextBtn = form.querySelector(".nextBtn"),
//         backBtn = form.querySelector(".backBtn"),
//         allInput = form.querySelectorAll('.first input');

// nextBtn.addEventListener("click", ()=> {
//     allInput.forEach(input => {
//         if(input.value != "") {
//             form.classList.add('secActive');
//         }else {
//             form.classList.remove('secActive');
//         }
//     })
// })

// backBtn.addEventListener("click", () => form.classList.remove('secActive'));


// form select with others
function handleSelectChange(select) {
    const inputField = document.getElementById('otherReason');
    
    if (select.value === 'others') {
        // Hide the select and show the input field
        select.style.display = 'none';
        inputField.style.display = 'block';
        inputField.focus(); // Focus on the input field when it becomes visible
    }
}

function handleInputBlur() {
    const select = document.getElementById('evacuate');
    const inputField = document.getElementById('otherReason');
    
    // If the user leaves the input field blank, switch back to the select
    if (!inputField.value) {
        inputField.style.display = 'none';
        select.style.display = 'block';
        select.value = ''; // Reset the select
    }
}
// =====================


// Get all radio buttons in the group
const radioButtons = document.querySelectorAll('input[name="special-needs"]');

// Variable to store the previously selected radio button
let lastSelected = null;

// Attach a click event to each radio button
radioButtons.forEach(radio => {
    radio.addEventListener('click', function() {
        // If the clicked radio button is already selected, uncheck it
        if (this === lastSelected) {
            this.checked = false;
            lastSelected = null;
        } else {
            // Otherwise, mark it as the last selected one
            lastSelected = this;
        }
    });
});
