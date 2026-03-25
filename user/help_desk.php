<?php
require_once '../settings/core.php';
require_once '../controllers/victim_controller.php';

if (!checkLogin()) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['id'];
$user = get_victim_ctr($user_id);
$display_name = htmlspecialchars($user['victim_name'] ?? $_SESSION['name'] ?? 'Friend');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Help Desk | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #0f0a1e;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            min-height: 100vh;
        }

        .help-card {
            background: rgba(26, 16, 51, 0.95);
            border: 1px solid #3c2a61;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(191, 64, 255, 0.15);
            margin-bottom: 30px;
        }

        .help-card:hover {
            border-color: #bf40ff;
            transition: border-color 0.3s;
        }

        .form-control, .form-select {
            background-color: #0f0a1e;
            border: 1px solid #3c2a61;
            color: #fff;
            border-radius: 10px;
        }
        .form-control:focus, .form-select:focus {
            background-color: #150d2b;
            border-color: #bf40ff;
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(191, 64, 255, 0.25);
        }
        .form-control::placeholder { color: #6c4898; }
        .form-label { color: #d980ff; font-weight: 500; }
        select option { background-color: #1a1033; }

        .btn-submit {
            background: linear-gradient(135deg, #9d4edd 0%, #bf40ff 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 50px;
            width: 100%;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(191, 64, 255, 0.4);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(191, 64, 255, 0.6);
            color: white;
        }

        .contact-info-card {
            background: rgba(191, 64, 255, 0.08);
            border: 1px solid rgba(191, 64, 255, 0.3);
            border-radius: 15px;
            padding: 1.5rem;
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(191, 64, 255, 0.2);
            border: 1px solid rgba(191, 64, 255, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #bf40ff;
            flex-shrink: 0;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            background: rgba(34, 197, 94, 0.2);
            border: 1px solid #22c55e;
            color: #22c55e;
        }

        .faq-item {
            border: 1px solid #3c2a61;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 12px;
        }

        .faq-question {
            background: rgba(60, 42, 97, 0.3);
            padding: 15px 20px;
            cursor: pointer;
            font-weight: 500;
            color: #e0aaff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            user-select: none;
        }

        .faq-question:hover { background: rgba(60, 42, 97, 0.5); }
        .faq-answer {
            padding: 15px 20px;
            color: #c8a8e9;
            display: none;
            border-top: 1px solid #3c2a61;
        }

        .page-header {
            background: linear-gradient(135deg, rgba(76, 29, 149, 0.8) 0%, rgba(30, 27, 75, 0.6) 100%);
            border-bottom: 1px solid #bf40ff;
            padding: 50px 0 40px;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>

<?php include '../views/navbar.php'; ?>

<div class="page-header text-center animate__animated animate__fadeIn">
    <div class="container">
        <div style="font-size: 3.5rem; margin-bottom: 15px;">🎧</div>
        <h2 class="fw-bold" style="background: linear-gradient(to right, #ffffff, #e0aaff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            Contact Help Desk
        </h2>
        <p style="color: #c8a8e9; font-size: 1.05rem;">We're here to help. Send us a message and we'll get back to you shortly.</p>
        <span class="status-badge"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>Support Online</span>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4">
        <!-- Left: Contact Form -->
        <div class="col-lg-8 animate__animated animate__fadeInLeft">
            <div class="help-card">
                <h4 class="mb-1" style="color: #e0aaff;"><i class="bi bi-envelope-fill me-2" style="color: #bf40ff;"></i>Send a Message</h4>
                <p style="color: #c8a8e9; font-size: 0.9rem;" class="mb-4">Describe the issue you're facing, and our team will respond within 24 hours.</p>

                <form id="help-form">
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-person me-1"></i>Your Name</label>
                        <input type="text" class="form-control" name="name" value="<?= $display_name ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-envelope me-1"></i>Your Email</label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['victim_email'] ?? '') ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-tag me-1"></i>Issue Category</label>
                        <select class="form-select" name="category" required>
                            <option value="">-- Select a category --</option>
                            <option>Account & Login Issues</option>
                            <option>Evidence Vault Problems</option>
                            <option>PIN Generation Issues</option>
                            <option>Service Booking Help</option>
                            <option>Chat Room Access</option>
                            <option>Report Submission Error</option>
                            <option>Privacy & Data Concern</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-chat-text me-1"></i>Describe Your Issue</label>
                        <textarea class="form-control" name="message" rows="5" placeholder="Please describe the issue in as much detail as possible..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-submit mt-2">
                        <i class="bi bi-send-fill me-2"></i>Send Message
                    </button>
                </form>
            </div>

            <!-- FAQ Section -->
            <div class="help-card">
                <h4 class="mb-4" style="color: #e0aaff;"><i class="bi bi-question-circle-fill me-2" style="color: #bf40ff;"></i>Frequently Asked Questions</h4>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        How do I reset my password? <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Go to the login page and click "Forgot Password". Enter your registered email address and follow the link sent to your inbox.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        Why isn't my Generate PIN working? <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Go to your Profile page and click the "Generate New PIN" button inside the Safe Space Handshake card. Ensure your profile has a valid session and try refreshing the page first.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        How do I upload evidence securely? <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Navigate to My Profile and scroll to the "Secure Evidence Archive" section. You can upload files (images, PDFs, audio/video) or type a secure text note.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        My service booking isn't showing up. What should I do? <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Go to your Dashboard → Safety Services and check the booking page. If it doesn't appear within a few minutes, please contact us using the form above.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        Is my data private and secure? <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Yes. All evidence is stored privately and is only accessible with your account. Your vault PIN is required to share evidence with a Service Provider, and you control when it expires.
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Contact Info -->
        <div class="col-lg-4 animate__animated animate__fadeInRight">
            <div class="help-card">
                <h5 class="mb-4" style="color: #e0aaff;"><i class="bi bi-info-circle-fill me-2" style="color: #bf40ff;"></i>Contact Information</h5>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="contact-icon"><i class="bi bi-envelope-at-fill"></i></div>
                    <div>
                        <div style="color: #d980ff; font-weight: 600; font-size: 0.85rem; margin-bottom: 2px;">Email Support</div>
                        <div style="color: #ffffff;">support@gbvaid.org</div>
                        <small style="color: #c8a8e9;">Response within 24 hours</small>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="contact-icon"><i class="bi bi-telephone-fill"></i></div>
                    <div>
                        <div style="color: #d980ff; font-weight: 600; font-size: 0.85rem; margin-bottom: 2px;">Helpline</div>
                        <div style="color: #ffffff;">+233 800 GBV-AID</div>
                        <small style="color: #c8a8e9;">Mon–Fri, 8am–6pm</small>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="contact-icon"><i class="bi bi-clock-fill"></i></div>
                    <div>
                        <div style="color: #d980ff; font-weight: 600; font-size: 0.85rem; margin-bottom: 2px;">Support Hours</div>
                        <div style="color: #ffffff;">8:00 AM – 9:00 PM</div>
                        <small style="color: #c8a8e9;">Weekdays & Weekends</small>
                    </div>
                </div>

                <hr style="border-color: #3c2a61;">

                <div style="background: rgba(191,64,255,0.08); border-radius: 12px; padding: 15px; border: 1px dashed rgba(191,64,255,0.3);">
                    <div class="fw-bold mb-1" style="color: #e0aaff;"><i class="bi bi-shield-lock-fill me-1"></i>Your Privacy Matters</div>
                    <p class="mb-0" style="color: #c8a8e9; font-size: 0.85rem;">All communications are confidential. We will never share your identity or case details without your explicit consent.</p>
                </div>
            </div>

            <div class="help-card text-center">
                <div style="font-size: 2.5rem; margin-bottom: 10px;">🆘</div>
                <h5 style="color: #ff6b6b; font-weight: 700;">Emergency?</h5>
                <p style="color: #c8a8e9; font-size: 0.9rem;">If you are in immediate danger, please use the SOS button on your dashboard or contact emergency services directly.</p>
                <a href="dashboard.php" class="btn btn-submit" style="background: linear-gradient(135deg, #dc2626, #991b1b);">
                    <i class="bi bi-broadcast me-2"></i>Go to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Submit form - show success message (demo mode)
    $('#help-form').on('submit', function(e) {
        e.preventDefault();

        const category = $('[name="category"]').val();
        const message = $('[name="message"]').val();

        if (!category || !message) {
            Swal.fire({ icon: 'warning', title: 'Missing Info', text: 'Please select a category and describe your issue.', confirmButtonColor: '#bf40ff', background: '#1a1033', color: '#fff' });
            return;
        }

        const btn = $(this).find('.btn-submit');
        btn.html('<i class="bi bi-hourglass-split me-2"></i>Sending...').prop('disabled', true);

        // Submit form via AJAX
        $.ajax({
            url: '../actions/submit_ticket_action.php',
            method: 'POST',
            data: { category: category, message: message },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Message Sent!',
                        html: 'Thank you for reaching out. Our support team will review your message and get back to you within <strong>24 hours</strong>.',
                        confirmButtonColor: '#bf40ff',
                        background: '#1a1033',
                        color: '#fff',
                        confirmButtonText: 'Got it!'
                    }).then(() => {
                        $('[name="category"]').val('');
                        $('[name="message"]').val('');
                        btn.html('<i class="bi bi-send-fill me-2"></i>Send Message').prop('disabled', false);
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: response.message, confirmButtonColor: '#bf40ff', background: '#1a1033', color: '#fff' });
                    btn.html('<i class="bi bi-send-fill me-2"></i>Send Message').prop('disabled', false);
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Server Error', text: 'Something went wrong. Please try again later.', confirmButtonColor: '#bf40ff', background: '#1a1033', color: '#fff' });
                btn.html('<i class="bi bi-send-fill me-2"></i>Send Message').prop('disabled', false);
            }
        });
    });

    // FAQ toggle
    function toggleFaq(el) {
        const answer = el.nextElementSibling;
        const icon = el.querySelector('.bi-chevron-down, .bi-chevron-up');
        if (answer.style.display === 'block') {
            answer.style.display = 'none';
            icon.className = 'bi bi-chevron-down';
        } else {
            answer.style.display = 'block';
            icon.className = 'bi bi-chevron-up';
        }
    }
</script>
</body>
</html>
