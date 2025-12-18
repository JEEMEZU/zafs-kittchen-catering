<?php
// ADVANCED EMAIL TEST SCRIPT - DELETE AFTER TESTING!
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><style>
body { font-family: Arial; padding: 20px; background: #f5f5f5; }
.container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
.success { color: green; font-weight: bold; }
.error { color: red; font-weight: bold; }
.info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 20px 0; }
.debug { background: #f5f5f5; padding: 15px; margin: 10px 0; font-family: monospace; font-size: 12px; overflow-x: auto; }
hr { margin: 30px 0; }
</style></head><body><div class='container'>";

echo "<h1>🔧 Advanced Email Test Script</h1>";
echo "<p>Testing SMTP connection and email sending...</p><hr>";

// Test 1: Check PHP extensions
echo "<h2>Test 1: PHP Extensions</h2>";
$extensions = ['openssl', 'sockets', 'mbstring'];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<div class='success'>✅ $ext - Loaded</div>";
    } else {
        echo "<div class='error'>❌ $ext - NOT Loaded (Required!)</div>";
    }
}

// Test 2: Check PHPMailer
echo "<hr><h2>Test 2: PHPMailer Installation</h2>";
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo "<div class='success'>✅ PHPMailer is installed</div>";
} else {
    echo "<div class='error'>❌ PHPMailer NOT found!</div>";
    exit;
}

// Test 3: SMTP Connection Test
echo "<hr><h2>Test 3: SMTP Connection Test</h2>";

$debug_output = [];
$mail = new PHPMailer(true);

try {
    // Capture debug output
    $mail->SMTPDebug = 3; // Maximum debug level
    $mail->Debugoutput = function($str, $level) use (&$debug_output) {
        $debug_output[] = htmlspecialchars($str);
    };
    
    // SMTP Configuration - Port 587
    echo "<div class='info'><strong>Testing with Port 587 (STARTTLS)</strong></div>";
    
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'zafskitchen95@gmail.com';
    $mail->Password = 'edsrxcmgytunsawi';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->Timeout = 30;
    
    // Disable SSL verification (for Railway/Docker)
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    
    // Email setup
    $test_email = 'agbojames00@gmail.com'; // Your email
    $mail->setFrom('zafskitchen95@gmail.com', "Zaf's Kitchen Test");
    $mail->addAddress($test_email);
    $mail->Subject = 'Test Email from Railway - ' . date('Y-m-d H:i:s');
    $mail->isHTML(true);
    $mail->Body = '<h1>Success!</h1><p>If you receive this, email is working! ✅</p>';
    
    echo "<div class='info'>Attempting to send email to: <strong>$test_email</strong></div>";
    
    // Send
    if ($mail->send()) {
        echo "<div class='success'><h2>✅ EMAIL SENT SUCCESSFULLY!</h2>";
        echo "<p>Check your inbox and spam folder: <strong>$test_email</strong></p></div>";
    } else {
        echo "<div class='error'><h2>❌ SEND FAILED</h2>";
        echo "<p>Error: " . htmlspecialchars($mail->ErrorInfo) . "</p></div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'><h2>❌ EXCEPTION CAUGHT</h2>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Mail Error:</strong> " . htmlspecialchars($mail->ErrorInfo) . "</p>";
    echo "</div>";
}

// Show debug output
echo "<hr><h2>SMTP Debug Output:</h2>";
echo "<div class='debug'>";
if (!empty($debug_output)) {
    foreach ($debug_output as $line) {
        echo $line . "<br>";
    }
} else {
    echo "No debug output captured.";
}
echo "</div>";

// Test 4: Alternative - Port 465
echo "<hr><h2>Test 4: Alternative - Port 465 (SSL)</h2>";

$debug_output2 = [];
$mail2 = new PHPMailer(true);

try {
    $mail2->SMTPDebug = 3;
    $mail2->Debugoutput = function($str, $level) use (&$debug_output2) {
        $debug_output2[] = htmlspecialchars($str);
    };
    
    echo "<div class='info'><strong>Testing with Port 465 (SSL)</strong></div>";
    
    $mail2->isSMTP();
    $mail2->Host = 'smtp.gmail.com';
    $mail2->SMTPAuth = true;
    $mail2->Username = 'zafskitchen95@gmail.com';
    $mail2->Password = 'edsrxcmgytunsawi';
    $mail2->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail2->Port = 465;
    $mail2->Timeout = 30;
    
    $mail2->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    
    $test_email = 'agbojames00@gmail.com';
    $mail2->setFrom('zafskitchen95@gmail.com', "Zaf's Kitchen Test Port 465");
    $mail2->addAddress($test_email);
    $mail2->Subject = 'Test Email Port 465 - ' . date('Y-m-d H:i:s');
    $mail2->isHTML(true);
    $mail2->Body = '<h1>Success with Port 465!</h1><p>This email was sent via SSL port 465 ✅</p>';
    
    if ($mail2->send()) {
        echo "<div class='success'><h2>✅ PORT 465 WORKS!</h2>";
        echo "<p>Email sent successfully via port 465</p></div>";
    } else {
        echo "<div class='error'><h2>❌ PORT 465 FAILED</h2>";
        echo "<p>Error: " . htmlspecialchars($mail2->ErrorInfo) . "</p></div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'><h2>❌ PORT 465 EXCEPTION</h2>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Mail Error:</strong> " . htmlspecialchars($mail2->ErrorInfo) . "</p>";
    echo "</div>";
}

echo "<div class='debug'>";
if (!empty($debug_output2)) {
    foreach ($debug_output2 as $line) {
        echo $line . "<br>";
    }
} else {
    echo "No debug output for port 465.";
}
echo "</div>";

// Server info
echo "<hr><h2>Server Information:</h2>";
echo "<div class='debug'>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server: " . php_uname() . "<br>";
echo "OpenSSL: " . (extension_loaded('openssl') ? OPENSSL_VERSION_TEXT : 'Not loaded') . "<br>";
echo "</div>";

echo "<hr><div class='error'><strong>⚠️ IMPORTANT:</strong> Delete this test file after testing for security!</div>";
echo "</div></body></html>";
?>
