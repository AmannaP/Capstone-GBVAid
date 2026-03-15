// js/category.js

$(document).ready(function () {
    // Validate category name
    function validateCategoryName(name) {
        if (!name || typeof name !== "string" || name.trim().length < 2) {
            Swal.fire({
                icon: "error",
                title: "Invalid Input",
                text: "Category name must be at least 2 characters long.",
                confirmButtonColor: "#bf40ff",
                background: "#1a1033",
                color: "#fff"
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
                        `<tr><td colspan='3' class='text-center py-4' style='color:#8a68b0;'>No service categories found yet.</td></tr>`
                    );
                    return;
                }

                response.categories.forEach((cat) => {
                    tableBody.append(`
                        <tr style="border-color: #3c2a61;">
                            <td class="text-center" style="color: #c8a8e9; font-weight: 600;">${cat.cat_id}</td>
                            <td style="color: #ffffff; font-weight: 500;">
                                <i class="bi bi-tag-fill me-2" style="color: #bf40ff;"></i>${cat.cat_name}
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm update-btn me-1"
                                        style="background: rgba(191,64,255,0.15); border: 1px solid #bf40ff; color: #e0aaff; border-radius: 50px; padding: 4px 14px;"
                                        data-id="${cat.cat_id}"
                                        data-name="${cat.cat_name}">
                                    <i class="bi bi-pencil-fill me-1"></i>Edit
                                </button>
                                <button class="btn btn-sm delete-btn"
                                        style="background: rgba(220,53,69,0.15); border: 1px solid rgba(220,53,69,0.5); color: #ff6b6b; border-radius: 50px; padding: 4px 14px;"
                                        data-id="${cat.cat_id}">
                                    <i class="bi bi-trash-fill me-1"></i>Delete
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
                    confirmButtonColor: "#bf40ff",
                    background: "#1a1033",
                    color: "#fff"
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
                        confirmButtonColor: "#bf40ff",
                        background: "#1a1033",
                        color: "#fff"
                    }).then(() => {
                        $("#cat_name").val("");
                        fetchCategories();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message || "Failed to add category (maybe duplicate?)",
                        confirmButtonColor: "#bf40ff",
                        background: "#1a1033",
                        color: "#fff"
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Something went wrong on the server.",
                    confirmButtonColor: "#bf40ff",
                    background: "#1a1033",
                    color: "#fff"
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
            confirmButtonColor: "#bf40ff",
            background: "#1a1033",
            color: "#fff"
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
                                confirmButtonColor: "#bf40ff",
                                background: "#1a1033",
                                color: "#fff"
                            }).then(() => fetchCategories());
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response.message || "Failed to update category.",
                                confirmButtonColor: "#bf40ff",
                                background: "#1a1033",
                                color: "#fff"
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: "error",
                            title: "Server Error",
                            text: "Could not update category.",
                            confirmButtonColor: "#bf40ff",
                            background: "#1a1033",
                            color: "#fff"
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
            title: "Delete this category?",
            text: "This category will be permanently deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#3c2a61",
            confirmButtonText: "Yes, delete it!",
            background: "#1a1033",
            color: "#fff"
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
                                confirmButtonColor: "#bf40ff",
                                background: "#1a1033",
                                color: "#fff"
                            }).then(() => fetchCategories());
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response.message || "Failed to delete category.",
                                confirmButtonColor: "#bf40ff",
                                background: "#1a1033",
                                color: "#fff"
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: "error",
                            title: "Server Error",
                            text: "Failed to delete category.",
                            confirmButtonColor: "#bf40ff",
                            background: "#1a1033",
                            color: "#fff"
                        });
                    }
                });
            }
        });
    });
});
