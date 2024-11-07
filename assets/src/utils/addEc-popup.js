// Get the elements
        
const showAdd = document.querySelector('.addBg-admin');
const addForm = document.querySelector('.addEC-container');
const closeForm = document.querySelector('.closeForm');
const body = document.querySelector('body'); // to get the body element

// add event listener
showAdd.addEventListener('click', function() {
    addForm.style.display = 'block';
    body.classList.add('body-overlay'); // add the overlay class to the body
});

closeForm.addEventListener('click', function() {
    addForm.style.display = 'none';
    body.classList.remove('body-overlay'); // remove the overlay class to the body
});