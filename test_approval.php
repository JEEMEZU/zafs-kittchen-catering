<?php
session_start();
require_once 'connection.php';
require_once 'sendmail.php';

// Test with booking ID 51
$booking_id = 51; // ⬅️ CHANGED FROM 1 to 51

$stmt = $conn->prepare("
    SELECT 
        b.*,
        u.email,
        COALESCE(u.name, b.full_name) as name
    FROM bookings b 
    JOIN usertable u ON b.user_id = u.id 
    WHERE b.id = ?
");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("❌ Booking not found!");
}

echo "<h1>Testing Approval Email</h1>";
echo "<p><strong>Booking ID:</strong> " . $booking['id'] . "</p>";
echo "<p><strong>Customer:</strong> " . $booking['full_name'] . "</p>";
echo "<p><strong>Email:</strong> " . $booking['email'] . "</p>";
echo "<p><strong>Celebrant:</strong> " . $booking['celebrant_name'] . "</p>";
echo "<p><strong>Event Type:</strong> " . $booking['event_type'] . "</p>";
echo "<p><strong>Status:</strong> " . $booking['booking_status'] . "</p>";
echo "<hr>";

echo "<h2>📧 Attempting to send email to: <span style='color: blue;'>" . $booking['email'] . "</span></h2>";
echo "<p>Please wait...</p>";

$result = sendBookingApprovalEmail($booking);

echo "<hr>";

if ($result) {
    echo "<h3 style='color: green; font-size: 24px;'>✅ EMAIL SENT SUCCESSFULLY!</h3>";
    echo "<p style='font-size: 18px;'>Check the inbox of: <strong>" . $booking['email'] . "</strong></p>";
    echo "<p style='color: orange;'>⚠️ Also check SPAM/JUNK folder!</p>";
} else {
    echo "<h3 style='color: red; font-size: 24px;'>❌ EMAIL FAILED TO SEND!</h3>";
    echo "<p>Check error log at:</p>";
    echo "<code style='background: #f0f0f0; padding: 10px; display: block;'>C:\\xampp\\php\\logs\\php_error_log</code>";
}

echo "<hr>";
echo "<h3>🔍 Debug Information:</h3>";
echo "<pre>";
echo "PHPMailer installed: " . (class_exists('PHPMailer\PHPMailer\PHPMailer') ? '✅ YES' : '❌ NO') . "\n";
echo "sendmail.php loaded: " . (file_exists('sendmail.php') ? '✅ YES' : '❌ NO') . "\n";
echo "Function exists: " . (function_exists('sendBookingApprovalEmail') ? '✅ YES' : '❌ NO') . "\n";
echo "</pre>";
?>