<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

function sendApprovalEmail($email, $name, $bookingDetails) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'zafskitchen95@gmail.com';
        $mail->Password   = 'edsrxcmgytunsawi';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('zafskitchen95@gmail.com', "Zaf's Kitchen");
        $mail->addAddress($email, htmlspecialchars($name, ENT_QUOTES, 'UTF-8'));
        $mail->addReplyTo('zafskitchen95@gmail.com', "Zaf's Kitchen Support");

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Booking Approved - Payment Required';
        
        $safe_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $booking_id = htmlspecialchars($bookingDetails['id'], ENT_QUOTES, 'UTF-8');
        $event_date = date('F d, Y', strtotime($bookingDetails['event_date']));
        $event_time = date('g:i A', strtotime($bookingDetails['start_time']));
        $total_price = number_format($bookingDetails['total_price'], 2);
        $deadline = date('F d, Y g:i A', strtotime('+20 hours'));
        
        $mail->Body = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #DC2626, #B91C1C); color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; }
                .info-box { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #DC2626; border-radius: 4px; }
                .warning-box { background: #fef3c7; padding: 15px; margin: 20px 0; border-left: 4px solid #f59e0b; border-radius: 4px; }
                .button { display: inline-block; background: #DC2626; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin: 20px 0; }
                .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
                strong { color: #DC2626; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🎉 Booking Approved!</h1>
                </div>
                
                <div class='content'>
                    <p>Hi <strong>{$safe_name}</strong>,</p>
                    
                    <p>Great news! Your booking has been approved.</p>
                    
                    <div class='info-box'>
                        <p><strong>📋 Booking ID:</strong> #{$booking_id}</p>
                        <p><strong>📅 Event Date:</strong> {$event_date}</p>
                        <p><strong>⏰ Time:</strong> {$event_time}</p>
                        <p><strong>💰 Total Amount:</strong> ₱{$total_price}</p>
                    </div>
                    
                    <div class='warning-box'>
                        <h3>⚠️ IMPORTANT - Payment Required</h3>
                        <p><strong>Payment Deadline:</strong> {$deadline}</p>
                        <p>Please complete your payment within <strong>20 hours</strong> to secure your booking.</p>
                        <p>⏱️ Your booking will be automatically cancelled if payment is not received by the deadline.</p>
                    </div>
                    
                    <p><strong>Payment Instructions:</strong></p>
                    <ul>
                        <li>Log in to your dashboard to view payment details</li>
                        <li>Submit proof of payment through the system</li>
                        <li>Wait for payment confirmation</li>
                    </ul>
                    
                    <center>
                        <a href='http://localhost/zaf/dashboard.php' class='button'>View My Booking</a>
                    </center>
                    
                    <p>Need help? Contact us at zafskitchen95@gmail.com</p>
                </div>
                
                <div class='footer'>
                    <p>Zaf's Kitchen Catering Services<br>
                    Thank you for choosing us for your special event!</p>
                </div>
            </div>
        </body>
        </html>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email send failed: " . $mail->ErrorInfo);
        return false;
    }
}
?>