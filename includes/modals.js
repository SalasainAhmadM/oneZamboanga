function editCategory(categoryId, currentName) {
    // Prompt user with SweetAlert for category editing
    Swal.fire({
        title: "Edit Category",
        input: "text",
        inputValue: currentName,
        showCancelButton: true,
        confirmButtonText: "Save",
        cancelButtonText: "Cancel",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        preConfirm: (newName) => {
            if (!newName) {
                Swal.showValidationMessage("Category name cannot be empty");
            } else {
                return fetch(`../endpoints/edit_category.php`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ id: categoryId, name: newName })
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: "Success!",
                                text: "Category updated successfully.",
                                icon: "success",
                            }).then(() => {
                                location.reload(); // Reload the page to show updated data
                            });
                        } else {
                            Swal.fire("Error!", "Failed to update category.", "error");
                        }
                    })
                    .catch(error => {
                        Swal.fire("Error!", "Request failed. Please try again.", "error");
                    });
            }
        }
    });
}


function deleteCategory(categoryId) {
    Swal.fire({
        title: "Are you sure?",
        text: "This action cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`../endpoints/delete_category.php`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id: categoryId })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire("Deleted!", "Category has been deleted.", "success")
                            .then(() => {
                                location.reload(); // Reload the page to show updated data
                            });
                    } else {
                        Swal.fire("Error!", "Failed to delete category.", "error");
                    }
                })
                .catch(error => {
                    Swal.fire("Error!", "Request failed. Please try again.", "error");
                });
        }
    });
}



// end of Category CRUD