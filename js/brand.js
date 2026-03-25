// js/brand.js

$(document).ready(function () {
    // Validate brand name
    function validateBrandName(name, category) {
        if (!name || typeof name !== "string" || name.trim().length < 2) {
            Swal.fire({
                icon: "error",
                title: "Invalid Input",
                text: "Brand name must be at least 2 characters long.",
                confirmButtonColor: "#bf40ff",
                background: "#1a1033",
                color: "#fff"
            });
            return false;
        }
        if (!category) {
            Swal.fire({
                icon: "error",
                title: "Missing Category",
                text: "Please select a category for this brand.",
                confirmButtonColor: "#bf40ff",
                background: "#1a1033",
                color: "#fff"
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
                            <tr style="border-color: #3c2a61;">
                                <td style="color: #c8a8e9; font-weight: 600;">${b.brand_id}</td>
                                <td style="color: #ffffff; font-weight: 500;">
                                    <i class="bi bi-building me-2" style="color: #bf40ff;"></i>${b.brand_name}
                                </td>
                                <td>
                                    <span style="background: rgba(191,64,255,0.15); border: 1px solid rgba(191,64,255,0.3); color: #e0aaff; border-radius: 50px; padding: 3px 12px; font-size: 0.82rem;">
                                        ${b.cat_name ?? "—"}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm update-btn me-1"
                                            style="background: rgba(191,64,255,0.15); border: 1px solid #bf40ff; color: #e0aaff; border-radius: 50px; padding: 4px 14px;"
                                            data-id="${b.brand_id}" data-name="${b.brand_name}">
                                        <i class="bi bi-pencil-fill me-1"></i>Edit
                                    </button>
                                    <button class="btn btn-sm delete-btn"
                                            style="background: rgba(220,53,69,0.15); border: 1px solid rgba(220,53,69,0.5); color: #ff6b6b; border-radius: 50px; padding: 4px 14px;"
                                            data-id="${b.brand_id}">
                                        <i class="bi bi-trash-fill me-1"></i>Delete
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append(`<tr><td colspan="4" class="text-center py-4" style="color: #8a68b0;">No brands found.</td></tr>`);
                }
            },
            error: function () {
                Swal.fire({ icon: "error", title: "Error", text: "Failed to load brands.", confirmButtonColor: "#bf40ff", background: "#1a1033", color: "#fff" });
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
                    Swal.fire({ icon: "success", title: "Success", text: res.message, confirmButtonColor: "#bf40ff", background: "#1a1033", color: "#fff" }).then(() => {
                        $("#add-brand-form")[0].reset();
                        fetchBrands();
                    });
                } else {
                    Swal.fire({ icon: "error", title: "Error", text: res.message, confirmButtonColor: "#bf40ff", background: "#1a1033", color: "#fff" });
                }
            },
            error: function () {
                Swal.fire({ icon: "error", title: "Server Error", text: "Server error occurred.", confirmButtonColor: "#bf40ff", background: "#1a1033", color: "#fff" });
            },
        });
    });

    // UPDATE brand
    $(document).on("click", ".update-btn", function () {
        const brand_id = $(this).data("id");
        const oldName = $(this).data("name");
        
        // Grab the categories from the existing dropdown on the page
        const categoryOptions = $('#category_id').html();

        Swal.fire({
            title: "Update Brand",
            background: "#1a1033",
            color: "#fff",
            html: `
                <div class="text-start">
                    <label class="form-label small" style="color: #d980ff;">Brand Name</label>
                    <input id="swal-input1" class="form-control mb-3" value="${oldName}">
                    <label class="form-label small" style="color: #d980ff;">Category</label>
                    <select id="swal-input2" class="form-select">
                        ${categoryOptions}
                    </select>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: "Save Changes",
            confirmButtonColor: "#bf40ff",
            preConfirm: () => {
                return {
                    name: document.getElementById('swal-input1').value.trim(),
                    cat: document.getElementById('swal-input2').value
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { name, cat } = result.value;
                if (!name || name.length < 2 || !cat) {
                    Swal.fire("Error", "Please provide valid name and category", "error");
                    return;
                }

                $.ajax({
                    url: "../actions/update_brand_action.php",
                    type: "POST",
                    dataType: "json",
                    data: { brand_id: brand_id, brand_name: name, cat_id: cat },
                    success: function (res) {
                        if (res.status === "success") {
                            Swal.fire({ icon: "success", title: "Updated!", text: res.message, confirmButtonColor: "#bf40ff", background: "#1a1033", color: "#fff" }).then(fetchBrands);
                        } else {
                            Swal.fire({ icon: "error", title: "Error", text: res.message, confirmButtonColor: "#bf40ff", background: "#1a1033", color: "#fff" });
                        }
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
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#3c2a61",
            confirmButtonText: "Yes, delete it",
            background: "#1a1033",
            color: "#fff"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../actions/delete_brand.php",
                    type: "POST",
                    dataType: "json",
                    data: { brand_id },
                    success: function (res) {
                        if (res.status === "success") {
                            Swal.fire({ icon: "success", title: "Deleted!", text: res.message, confirmButtonColor: "#bf40ff", background: "#1a1033", color: "#fff" }).then(fetchBrands);
                        } else {
                            Swal.fire({ icon: "error", title: "Error", text: res.message, confirmButtonColor: "#bf40ff", background: "#1a1033", color: "#fff" });
                        }
                    },
                    error: function () {
                        Swal.fire({ icon: "error", title: "Error", text: "Server error occurred.", confirmButtonColor: "#bf40ff", background: "#1a1033", color: "#fff" });
                    },
                });
            }
        });
    });
});
