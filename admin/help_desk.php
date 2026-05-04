<?php
// admin/help_desk.php
require_once '../settings/core.php';
require_once '../controllers/help_desk_controller.php';
requireAdmin();

$tickets = get_all_tickets_ctr() ?: [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Help Desk | GBVAid Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #0f0a1e; font-family: 'Poppins', sans-serif; color: #ffffff; }
        .navbar-admin { background: rgba(26, 16, 51, 0.95); border-bottom: 2px solid #bf40ff; padding: 15px 0; }
        .card-custom { background: rgba(26, 16, 51, 0.8); border: 1px solid #3c2a61; border-radius: 15px; overflow: hidden; }
        .table-custom { color: #fff; margin-bottom: 0; }
        .table-custom th { background: rgba(60, 42, 97, 0.5); border-bottom: 1px solid #bf40ff; color: #e0aaff; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; padding: 15px; }
        .table-custom td { padding: 15px; border-bottom: 1px solid #3c2a61; vertical-align: middle; background: transparent; color: #f8f9fa; }
        .table-custom tbody tr:hover { background: rgba(191, 64, 255, 0.05); }
        .badge-pending { background-color: rgba(255, 193, 7, 0.2); color: #ffc107; border: 1px solid #ffc107; }
        .badge-resolved { background-color: rgba(40, 167, 69, 0.2); color: #28a745; border: 1px solid #28a745; }
        
        .modal-content { background: #1a1033; border: 1px solid #bf40ff; border-radius: 15px; }
        .modal-header { border-bottom: 1px solid #3c2a61; }
        .modal-title { color: #e0aaff; }
        .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
        .modal-body { color: #fff; }
        .modal-footer { border-top: 1px solid #3c2a61; }
        .form-control { background: #0f0a1e; border: 1px solid #3c2a61; color: #fff; }
        .form-control:focus { background: #0f0a1e; border-color: #bf40ff; color: #fff; box-shadow: 0 0 0 0.25rem rgba(191, 64, 255, 0.25); }
        .btn-primary-custom { background: linear-gradient(135deg, #9d4edd 0%, #bf40ff 100%); border: none; font-weight: 500; }
        .btn-primary-custom:hover { background: linear-gradient(135deg, #7b2cbf 0%, #9d4edd 100%); }

        .ticket-message-preview { max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    </style>
</head>
<body>

<?php include 'admin_nav.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: #e0aaff;"><i class="bi bi-headset me-2"></i>Help Desk Tickets</h2>
    </div>

    <div class="card-custom">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle">
                <thead>
                    <tr>
                        <th width="15%">User</th>
                        <th width="15%">Category</th>
                        <th width="30%">Message Preview</th>
                        <th width="15%">Date</th>
                        <th width="10%">Status</th>
                        <th width="15%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tickets)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5" style="color: #c8a8e9;">
                                <i class="bi bi-inbox-fill fs-1 d-block mb-2"></i>
                                No tickets found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($ticket['victim_name']) ?></div>
                                    <div class="small" style="color: #c8a8e9;"><?= htmlspecialchars($ticket['victim_email']) ?></div>
                                </td>
                                <td>
                                    <span class="badge" style="background: rgba(191,64,255,0.2); border: 1px solid #bf40ff; color:#e0aaff;">
                                        <?= htmlspecialchars($ticket['category']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="ticket-message-preview" title="<?= htmlspecialchars($ticket['message']) ?>">
                                        <?= htmlspecialchars($ticket['message']) ?>
                                    </div>
                                </td>
                                <td class="small" style="color: #c8a8e9;">
                                    <?= date('M d, Y h:i A', strtotime($ticket['created_at'])) ?>
                                </td>
                                <td>
                                    <?php if ($ticket['status'] === 'Pending'): ?>
                                        <span class="badge badge-pending rounded-pill px-3 py-2"><i class="bi bi-clock me-1"></i>Pending</span>
                                    <?php else: ?>
                                        <span class="badge badge-resolved rounded-pill px-3 py-2"><i class="bi bi-check-circle me-1"></i>Resolved</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-light view-ticket-btn" 
                                            data-id="<?= $ticket['ticket_id'] ?>"
                                            data-user="<?= htmlspecialchars($ticket['victim_name']) ?>"
                                            data-email="<?= htmlspecialchars($ticket['victim_email']) ?>"
                                            data-category="<?= htmlspecialchars($ticket['category']) ?>"
                                            data-message="<?= htmlspecialchars($ticket['message']) ?>"
                                            data-status="<?= $ticket['status'] ?>"
                                            data-reply="<?= htmlspecialchars($ticket['admin_reply'] ?? '') ?>"
                                            data-date="<?= date('M d, Y h:i A', strtotime($ticket['created_at'])) ?>">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View/Reply Ticket Modal -->
<div class="modal fade" id="ticketModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-ticket-detailed me-2"></i>Ticket Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small style="color: #c8a8e9; text-transform: uppercase;">Sent By</small>
                        <div class="fw-bold fs-5" id="ticketUser">User Name</div>
                        <div id="ticketEmail" style="color: #bf40ff;">user@email.com</div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <small style="color: #c8a8e9; text-transform: uppercase;">Ticket Info</small>
                        <div><span id="ticketBadge" class="badge bg-secondary mb-1">Category</span></div>
                        <div class="small" id="ticketDate" style="color: #c8a8e9;">Date</div>
                    </div>
                </div>

                <div class="card card-body mb-4" style="background: rgba(191,64,255,0.05); border: 1px dashed rgba(191,64,255,0.3);">
                    <h6 style="color: #e0aaff;">Message:</h6>
                    <div id="ticketMessage" style="white-space: pre-wrap;">Message content goes here...</div>
                </div>

                <form id="replyForm">
                    <input type="hidden" id="ticketId" name="ticket_id">
                    <div class="mb-3">
                        <label class="form-label" style="color: #e0aaff;">Admin Reply / Resolution Notes</label>
                        <textarea class="form-control" id="adminReply" name="admin_reply" rows="4" placeholder="Type your response to the user or internal resolution notes..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" style="color: #e0aaff;">Status</label>
                        <select class="form-select" id="ticketStatus" name="status">
                            <option value="Pending">Pending</option>
                            <option value="Resolved">Resolved</option>
                        </select>
                    </div>

                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-outline-light me-2" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary-custom px-4" id="saveBtn"><i class="bi bi-save me-2"></i>Save Ticket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $('.view-ticket-btn').on('click', function() {
        // Populate modal data
        $('#ticketId').val($(this).data('id'));
        $('#ticketUser').text($(this).data('user'));
        $('#ticketEmail').text($(this).data('email'));
        $('#ticketBadge').text($(this).data('category'));
        $('#ticketMessage').text($(this).data('message'));
        $('#ticketDate').text($(this).data('date'));
        
        $('#adminReply').val($(this).data('reply'));
        $('#ticketStatus').val($(this).data('status'));
        
        // Disable editing if already resolved (optional UX choice, allowing edits for now)
        
        $('#ticketModal').modal('show');
    });

    $('#replyForm').on('submit', function(e) {
        e.preventDefault();
        
        const btn = $('#saveBtn');
        const originalText = btn.html();
        btn.html('<i class="bi bi-hourglass me-2"></i>Saving...').prop('disabled', true);

        $.ajax({
            url: '../actions/admin_update_ticket_action.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Ticket has been updated successfully.',
                        confirmButtonColor: '#bf40ff',
                        background: '#1a1033',
                        color: '#fff'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: response.message, background: '#1a1033', color: '#fff' });
                    btn.html(originalText).prop('disabled', false);
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Server Error', text: 'Something went wrong.', background: '#1a1033', color: '#fff' });
                btn.html(originalText).prop('disabled', false);
            }
        });
    });
</script>
</body>
</html>
