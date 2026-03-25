<?php
require_once '../settings/core.php';
require_once '../settings/db_class.php';

if (file_exists('../vendor/autoload.php')) {
    require_once '../vendor/autoload.php';
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }

    $db = new db_conn();
    if (!$db->db_connect()) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
        exit;
    }

    // Check if email exists
    $sql = "SELECT victim_id FROM victim WHERE victim_email = ?";
    $stmt = $db->db->prepare($sql);
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Generate Token
        $token = bin2hex(random_bytes(32)); // 64-char hex string
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Save to DB
        $update_sql = "UPDATE victim SET reset_token = ?, token_expires_at = ? WHERE victim_id = ?";
        $update_stmt = $db->db->prepare($update_sql);
        
        if ($update_stmt->execute([$token, $expires, $user['victim_id']])) {
            
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
            $domain = getenv('RAILWAY_STATIC_URL') ?: $_SERVER['HTTP_HOST'];
            
            // For local development, construct XAMPP path. For Railway, use the root domain.
            if ($domain === 'localhost' || $domain === '127.0.0.1') {
                $reset_link = "$protocol://$domain/Capstone-GBVAid/login/reset_password.php?token=$token";
            } else {
                // Adjust this if your Railway app is not at the root
                $reset_link = "$protocol://$domain/login/reset_password.php?token=$token";
            }

            // If Composer vendor is not found (e.g. testing locally without composer), return fallback
            if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'PHPMailer not installed. Demo Link: ' . $reset_link,
                    'reset_link' => $reset_link
                ]);
                exit;
            }

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = getenv('SMTP_HOST') ?: 'smtp.gmail.com'; 
                $mail->SMTPAuth   = true;
                $mail->Username   = getenv('SMTP_USER') ?: 'your-email@gmail.com'; 
                $mail->Password   = getenv('SMTP_PASS') ?: 'your-app-password';
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = getenv('SMTP_PORT') ?: 587;

                $mail->setFrom($mail->Username, 'GBVAid Support');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request - GBVAid';
                $mail->Body    = "Hello,<br><br>You recently requested to reset your password for your GBVAid account. Click the link below to reset it:<br><br><a href='{$reset_link}'>{$reset_link}</a><br><br>If you did not request a password reset, please ignore this email.<br><br>Stay safe,<br>GBVAid Team";
                $mail->AltBody = "Hello,\n\nYou recently requested to reset your password for your GBVAid account. Copy and paste the link below into your browser to reset it:\n\n{$reset_link}\n\nIf you did not request a password reset, please ignore this email.\n\nStay safe,\nGBVAid Team";

                $mail->send();
                echo json_encode(['success' => true, 'message' => 'Password reset link has been sent to your email!']);
            } catch (Exception $e) {
                // If SMTP isn't configured correctly on Railway, fallback softly
                echo json_encode(['success' => false, 'message' => "Message could not be sent. Please configure SMTP variables. Mailer Error: {$mail->ErrorInfo}"]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to generate reset token.']);
        }
    } else {
        // Obfuscation: To prevent email enumeration, return success even if email not found
        // But for development/demo, we'll return an error so the tester knows.
        echo json_encode(['success' => false, 'message' => 'Email address not found.']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request array.']);
}
?>
