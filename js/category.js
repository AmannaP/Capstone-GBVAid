// js/category.js

$(document).ready(function () {
    // Validate category name
    function validateCategoryName(name) {
        if (!name || typeof name !== "string" || name.trim().length < 2) {
            Swal.fire({
                icon: "error",
                title: "Invalid Input",
                text: "Category name must be at least 2 characters long.",
                confirmButtonColor: "#b77a7a"
            });
            return false;
        }
        return true;
    }

    // Load categories
    function fetchCategories() {
        $.ajax({
            url: "../actions/fetch_category_action.php",
            method: "GET",
            dataType: "json",
            success: function (response) {
                let tableBody = $("#category-table tbody");
                tableBody.empty();

                if (response.status !== "success" || !response.categories || response.categories.length === 0) {
                    tableBody.append(
                        `<tr><td colspan='4' class='text-center text-muted'>No service categories found yet.</td></tr>`
                    );
                    return;
                }

                response.categories.forEach((cat) => {
                    tableBody.append(`
                        <tr>
                            <td>${cat.cat_id}</td>
                            <td>${cat.cat_name}</td>
                            <td>
                                <button class="btn btn-warning btn-sm update-btn" 
                                        data-id="${cat.cat_id}" 
                                        data-name="${cat.cat_name}">
                                    Edit
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-btn" 
                                        data-id="${cat.cat_id}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    `);
                });
            },
            error: function (xhr, status, error) {
                console.error("Error loading categories:", xhr.responseText);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Failed to load service categories.",
                    confirmButtonColor: "#b77a7a"
                });
            }
        });
    }

    // Load categories on page load
    fetchCategories();

    // Add category
    $("#add-category-form").submit(function (e) {
        e.preventDefault();
        const cat_name = $("#cat_name").val().trim();

        if (!validateCategoryName(cat_name)) return;

        $.ajax({
            url: "../actions/add_category_action.php",
            method: "POST",
            dataType: "json",
            data: { cat_name },
            success: function (response) {
                if (response.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Added!",
                        text: response.message || "Service category added successfully.",
                        confirmButtonColor: "#b77a7a"
                    }).then(() => {
                        $("#cat_name").val("");
                        fetchCategories(); // 🔁 reload categories
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message || "Failed to add category (maybe duplicate?)",
                        confirmButtonColor: "#b77a7a"
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Something went wrong on the server.",
                    confirmButtonColor: "#b77a7a"
                });
            }
        });
    });

    // Update category
    $(document).on("click", ".update-btn", function () {
        const cat_id = $(this).data("id");
        const oldName = $(this).data("name");

        Swal.fire({
            title: "Edit Service Category",
            input: "text",
            inputLabel: "Enter new category name",
            inputValue: oldName,
            showCancelButton: true,
            confirmButtonText: "Update",
            confirmButtonColor: "#b77a7a"
        }).then((result) => {
            if (result.isConfirmed && validateCategoryName(result.value)) {
                $.ajax({
                    url: "../actions/update_category_action.php",
                    method: "POST",
                    dataType: "json",
                    data: { cat_id, new_name: result.value.trim() },
                    success: function (response) {
                        if (response.status === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "Updated!",
                                text: response.message || "Category updated successfully.",
                                confirmButtonColor: "#b77a7a"
                            }).then(() => fetchCategories());
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response.message || "Failed to update category.",
                                confirmButtonColor: "#b77a7a"
                            });
                            }
                    },
                    error: function () {
                        Swal.fire({
                            icon: "error",
                            title: "Server Error",
                            text: "Could not update category.",
                            confirmButtonColor: "#b77a7a"
                        });
                    }
                });
            }
        });
    });

    // Delete category
    $(document).on("click", ".delete-btn", function () {
        const cat_id = $(this).data("id");

        Swal.fire({
            title: "Are you sure?",
            text: "This category will be permanently deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#b77a7a",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../actions/delete_category_action.php",
                    method: "POST",
                    dataType: "json",
                    data: { cat_id },
                    success: function (response) {
                        if (response.status === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "Deleted!",
                                text: response.message || "Category deleted successfully.",
                                confirmButtonColor: "#b77a7a"
                            }).then(() => fetchCategories());
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response.message || "Failed to delete category.",
                                confirmButtonColor: "#b77a7a"
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: "error",
                            title: "Server Error",
                            text: "Failed to delete category.",
                            confirmButtonColor: "#b77a7a"
                        });
                    }
                });
            }
        });
    });
});
