// js/brand.js

$(document).ready(function () {
    // Validate brand name
    function validateBrandName(name, category) {
        if (!name || typeof name !== "string" || name.trim().length < 2) {
            Swal.fire({
                icon: "error",
                title: "Invalid Input",
                text: "Brand name must be at least 2 characters long.",
            });
            return false;
        }
        if (!category) {
            Swal.fire({
                icon: "error",
                title: "Missing Category",
                text: "Please select a category for this brand.",
            });
            return false;
        }
        return true;
    }

    // Fetch all brands
    function fetchBrands() {
        $.ajax({
            url: "../actions/fetch_brand_action.php",
            type: "GET",
            dataType: "json",
            success: function (res) {
                let tbody = $("#brand-table tbody");
                tbody.empty();

                if (res.status === "success" && res.brands.length > 0) {
                    res.brands.forEach((b) => {
                        tbody.append(`
                            <tr>
                                <td>${b.brand_id}</td>
                                <td>${b.brand_name}</td>
                                <td>${b.cat_name ?? "—"}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm update-btn" data-id="${b.brand_id}" data-name="${b.brand_name}">
                                        Update
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${b.brand_id}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append(`<tr><td colspan="4" class="text-center text-muted">No brands found.</td></tr>`);
                }
            },
            error: function () {
                Swal.fire("Error", "Failed to load brands.", "error");
            },
        });
    }

    fetchBrands(); // Load brands when page loads

    // CREATE new brand
    $("#add-brand-form").submit(function (e) {
        e.preventDefault();

        const brand_name = $("#brand_name").val().trim();
        const category_id = $("#category_id").val();

        if (!validateBrandName(brand_name, category_id)) return;

        $.ajax({
            url: "../actions/add_brand_action.php",
            type: "POST",
            dataType: "json",
            data: { brand_name, cat_id: category_id },
            success: function (res) {
                if (res.status === "success") {
                    Swal.fire("Success", res.message, "success").then(() => {
                        $("#add-brand-form")[0].reset();
                        fetchBrands();
                    });
                } else {
                    Swal.fire("Error", res.message, "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Server error occurred.", "error");
            },
        });
    });

    // UPDATE brand
    $(document).on("click", ".update-btn", function () {
        const brand_id = $(this).data("id");
        const oldName = $(this).data("name");

        Swal.fire({
            title: "Update Brand Name",
            input: "text",
            inputLabel: "Enter new brand name",
            inputValue: oldName,
            showCancelButton: true,
            confirmButtonText: "Update",
            preConfirm: (value) => {
                if (!value || value.trim().length < 2) {
                    Swal.showValidationMessage("Please enter a valid brand name (at least 2 characters)");
                }
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../actions/update_brand_action.php",
                    type: "POST",
                    dataType: "json",
                    data: { brand_id: brand_id, brand_name: result.value.trim() },
                    success: function (res) {
                        if (res.status === "success") {
                            Swal.fire("Updated!", res.message, "success").then(fetchBrands);
                        } else {
                            Swal.fire("Error", res.message, "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error", "Server error occurred.", "error");
                    }
                });
            }
        });
    });


    // DELETE brand
    $(document).on("click", ".delete-btn", function () {
        const brand_id = $(this).data("id");

        Swal.fire({
            title: "Delete this brand?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Yes, delete it",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../actions/delete_brand_action.php",
                    type: "POST",
                    dataType: "json",
                    data: { brand_id },
                    success: function (res) {
                        if (res.status === "success") {
                            Swal.fire("Deleted!", res.message, "success").then(fetchBrands);
                        } else {
                            Swal.fire("Error", res.message, "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error", "Server error occurred.", "error");
                    },
                });
            }
        });
    });
});
