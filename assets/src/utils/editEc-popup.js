const modalOption = document.querySelector('.ecenter-toggle');
const body2 = document.querySelector('body'); // to get the body element

const editBtn = document.querySelector('.ecenterEdit');
const editForm = document.querySelector('.editEC-container');
const closeEdit = document.querySelector('.closeEdit');

const deleteBtn = document.querySelector('.ecenterDelete');


editBtn.addEventListener('click', function() {
    editForm.style.display = 'block';

    // If modalOption is an input (checkbox or radio button), uncheck it
    if (modalOption.type === 'checkbox' || modalOption.type === 'radio') {
        modalOption.checked = false; // Uncheck the input
    }

    body2.classList.add('body-overlay'); // add the overlay class to the body
});

closeEdit.addEventListener('click', function() {
    editForm.style.display = 'none';
    body2.classList.remove('body-overlay');
});

deleteBtn.addEventListener('click', function() {

    // to hide/unchecked the modal
    if (modalOption.type === 'checkbox' || modalOption.type === 'radio') {
        modalOption.checked = false;
    }
})