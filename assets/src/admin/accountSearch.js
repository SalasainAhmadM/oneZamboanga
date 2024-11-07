
// Get references to the input element, table rows, and no-match message
const searchInput = document.querySelector('.input_group input');
const tableRows = document.querySelectorAll('#mainTable tbody tr');
const noMatchMessage = document.querySelector('.no-match-message');

// Add event listener to the input field
searchInput.addEventListener('input', function () {
    const searchValue = searchInput.value.toLowerCase(); // Get the search term and convert it to lowercase for case-insensitive search
    let hasVisibleRow = false; // Track if any row is visible

    // Loop through all table rows and compare the search term with the Barangay and Full Name columns
    tableRows.forEach(function (row) {
        const barangay = row.querySelector('td:nth-child(1)').textContent.toLowerCase(); // First column - Barangay
        const fullName = row.querySelector('td:nth-child(2)').textContent.toLowerCase(); // Second column - Full Name

        // Check if search term matches either the barangay or full name
        if (barangay.includes(searchValue) || fullName.includes(searchValue)) {
            row.style.display = ''; // Show the row if it matches
            hasVisibleRow = true;   // Mark that at least one row is visible
        } else {
            row.style.display = 'none'; // Hide the row if it doesn't match
        }
    });

    // Display the no-match message if no rows are visible
    if (!hasVisibleRow) {
        noMatchMessage.style.display = 'block';
    } else {
        noMatchMessage.style.display = 'none';
    }
});

