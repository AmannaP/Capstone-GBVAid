// js/login.js
$(document).ready(function () {
    $('#login-form').submit(function (e) {
        e.preventDefault();

        let email = $('#email').val().trim();
        let password = $('#password').val().trim();

        // Validation
        if (email === '' || password === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all fields!',
            });
            return;
        }

        $.ajax({
            url: '../actions/login_victim_action.php',
            type: 'POST',
            dataType: 'json',
            data: {
                email: email,
                password: password
            },
            success: function (response) {
                console.log("Raw response:", response);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect based on user role
                            if (response.role === '2' || response.role === 2) {
                                // Admin
                                window.location.href = '../admin/dashboard.php';
                            } else {
                                // Regular victim
                                window.location.href = '../user/dashboard.php';
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    });
                }
            },
            error: function (xhr, status, error) {
                console.log("XHR responseText:", xhr.responseText); 
                console.log("Status:", status);
                console.log("Error:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An error occurred! Please try again later.',
                });
            }
        });
    });
});
