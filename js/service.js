// js/service.js

// ================== ADMIN service MANAGEMENT ==================
$(document).ready(function () {
    if ($("#service-form").length > 0) {

        // ============ HELPER FUNCTIONS ============
        function resetForm() {
            $('#service_id').val('');
            $('#service-form')[0].reset();
            $('#save-service').text('Save service');
        }

        function fetchservices() {
            $.ajax({
                url: "../actions/fetch_service_action.php",
                method: "GET",
                dataType: "json",
                success: function (res) {
                    const tbody = $('#service-table tbody');
                    tbody.empty();

                    if (res.status === "success" && Array.isArray(res.services)) {
                        res.services.forEach(p => {
                            const imgUrl = p.service_image
                                ? `../uploads/services/${p.service_image}`
                                : `../uploads/services/default.jpg`;

                            tbody.append(`
                                <tr>
                                    <td>${p.service_id}</td>
                                    <td><img src="${imgUrl}" style="width:80px;height:60px;object-fit:cover;border-radius:6px;"></td>
                                    <td>${p.service_title}</td>
                                    <td>${p.cat_name ?? '—'}</td>
                                    <td>${p.brand_name ?? '—'}</td>
                                    <td>${parseFloat(p.service_price).toFixed(2)}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-btn" data-id="${p.service_id}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-btn ms-2" data-id="${p.service_id}">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        tbody.append('<tr><td colspan="7" class="text-center text-muted">No services found.</td></tr>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    Swal.fire('Error', 'Failed to load services', 'error');
                }
            });
        }

        // Call fetchservices on page load
        fetchservices();

        // ============ ADD OR UPDATE service ============
        $('#service-form').submit(function (e) {
            e.preventDefault();

            const title = $('#service_title').val().trim();
            const price = $('#service_price').val().trim();
            if (!title || !price) {
                Swal.fire('Error', 'Please fill all required fields', 'error');
                return;
            }

            const form = $('#service-form')[0];
            const data = new FormData(form);
            const service_id = $('#service_id').val();
            const url = service_id ? '../actions/update_service_action.php' : '../actions/add_service_action.php';

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: () => {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },
                success: function (res) {
                    if (res.status === 'success') {
                        Swal.fire('Success', res.message, 'success').then(() => {
                            resetForm();
                            fetchservices(); // reload list
                        });
                    } else {
                        Swal.fire('Error', res.message || 'Failed', 'error');
                    }
                },
                error: function (xhr, status, error) {
                    Swal.close();
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    Swal.fire("Error", "Server error occurred.", "error");
                }
            });
        });

        // ============ RESET ============
        $('#reset-form').click(resetForm);

        // ============ EDIT ============
        $(document).on('click', '.edit-btn', function () {
            const id = $(this).data('id');
            $.ajax({
                url: '../actions/fetch_service_action.php',
                method: 'GET',
                data: { id },
                dataType: 'json',
                success: function (res) {
                    console.log(res);
                    if (res.status === 'success' && res.service) {
                        const prod = res.service;
                        $('#service_id').val(prod.service_id);
                        $('#cat_id').val(prod.service_cat);
                        $('#brand_id').val(prod.service_brand);
                        $('#service_title').val(prod.service_title);
                        $('#service_price').val(prod.service_price);
                        $('#service_desc').val(prod.service_desc);
                        $('#service_keywords').val(prod.service_keywords);
                        $('#save-service').text('Update service');
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        console.log("Description element found:", $('#service_desc').length);
                        console.log("service description from response:", prod.service_desc);
                        console.log("service description value:", $('#service_desc').val());

                    } else {
                        Swal.fire('Error', res.message || 'Failed to load service', 'error');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    Swal.fire("Error", "Server error occurred.", "error");
                }
            });
        });

        // ============ DELETE ============
        $(document).on('click', '.delete-btn', function () {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../actions/delete_service_action.php',
                        method: 'POST',
                        data: { id },
                        dataType: 'json',
                        success: function (res) {
                            if (res.status === 'success') {
                                Swal.fire('Deleted!', res.message, 'success').then(() => {
                                    fetchservices();
                                });
                            } else {
                                Swal.fire('Error', res.message || 'Failed to delete service', 'error');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("AJAX Error:", status, error, xhr.responseText);
                            Swal.fire("Error", "Server error occurred.", "error");
                        }
                    });
                }
            });
        });
    }
});

    // ================== USER service DISPLAY & SEARCH ==================
    if ($("#service-list").length > 0) {
        let currentPage = 1;

        function fetchUserservices(filters = {}, page = 1) {
            filters.page = page;

            $.ajax({
                url: "../actions/fetch_service_action.php",
                type: "GET",
                data: filters,
                dataType: "json",
                success: function (res) {
                    const container = $("#service-list");
                    const pagination = $("#pagination");
                    container.empty();
                    pagination.empty();

                    if (res.status === "success" && Array.isArray(res.services) && res.services.length > 0) {
                        // RENDER EACH service
                        res.services.forEach((p) => {
                            const imgUrl = p.service_image
                                ? `../uploads/services/${p.service_image}`
                                : `../uploads/services/default.jpg`;

                            container.append(`
                                <div class="col-md-4 col-sm-6 mb-4">
                                    <div class="card service-card h-100 shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                                        <a href="service_details.php?id=${p.service_id}" style="text-decoration:none;">
                                            <img src="${imgUrl}" class="card-img-top" alt="${p.service_title}" 
                                                style="height: 220px; object-fit: cover;">
                                        </a>
                                        
                                        <div class="card-body d-flex flex-column p-3">
                                            <h5 class="fw-bold text-dark text-truncate">${p.service_title}</h5>
                                            
                                            <div class="d-flex justify-content-between mb-2">
                                                <small class="text-muted"><i class="bi bi-tag"></i> ${p.cat_name}</small>
                                                <small class="text-muted"><i class="bi bi-shop"></i> ${p.brand_name}</small>
                                            </div>
                                            
                                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                                <span class="fw-bold" style="color: #c453eaff; font-size: 1.1rem;">
                                                    GH₵ ${parseFloat(p.service_price).toFixed(2)}
                                                </span>
                                                
                                                <a href="service_details.php?id=${p.service_id}" class="btn text-white fw-bold btn-sm px-3" 
                                                style="background-color: #c453eaff; border-radius: 50px;">
                                                    Book Session
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });

                         // Render pagination buttons
                    if (res.total_pages && res.total_pages > 1) {
                        for (let i = 1; i <= res.total_pages; i++) {
                            pagination.append(`
                                <button class="pagination-btn ${i === res.current_page ? 'active' : ''}" data-page="${i}">
                                    ${i}
                                </button>
                            `);
                        }
                    }
                } else {
                    container.html("<p class='text-center text-muted'>No services found.</p>");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error, xhr.responseText);
                Swal.fire("Error", "Server error occurred.", "error");
            }
            });
        }

        // Initial load
        fetchUserservices();

        // Pagination click
        $(document).on('click', '.pagination-btn', function () {
            const page = $(this).data('page');
            currentPage = page;
            const query = $("#search_box").val().trim();
            const cat_id = $("#category_filter").val();
            const brand_id = $("#brand_filter").val();
            fetchUserservices({ search: query, cat_id, brand_id }, currentPage);
        });

        // Search button click
        $("#search_btn").on("click", function () {
            const query = $("#search_box").val().trim();
            fetchUserservices({ search: query }, 1);
        });

        // Filter change
        $("#category_filter, #brand_filter").on("change", function () {
            const cat_id = $("#category_filter").val();
            const brand_id = $("#brand_filter").val();
            fetchUserservices({ cat_id, brand_id }, 1);
        });
    }
