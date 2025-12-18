<?php 
date_default_timezone_set('Asia/Manila');
session_start();
require_once 'connection.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated. Please log in again.']);
    exit;
}

// Get POST data
$currentPassword = isset($_POST['current_password']) ? trim($_POST['current_password']) : '';
$newPassword = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';

// Validate input
if (empty($currentPassword) || empty($newPassword)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all password fields.']);
    exit;
}

// Validate new password length
if (strlen($newPassword) < 11) {
    echo json_encode(['success' => false, 'message' => 'New password must be at least 11 characters long.']);
    exit;
}

// Check if new password is same as current
if ($currentPassword === $newPassword) {
    echo json_encode(['success' => false, 'message' => 'New password must be different from current password.']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    // Fetch current password from database using PDO
    $query = "SELECT password FROM usertable WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$userId]);
    
    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
        exit;
    }
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $storedPassword = $user['password'];
    
    // Verify current password
    if (!password_verify($currentPassword, $storedPassword)) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect. Please try again.']);
        exit;
    }
    
    // Hash new password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    
    // Update password in database
    $updateQuery = "UPDATE usertable SET password = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    
    if ($updateStmt->execute([$hashedNewPassword, $userId])) {
        echo json_encode(['success' => true, 'message' => 'Password changed successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update password. Please try again.']);
    }
    
} catch (PDOException $e) {
    error_log("Password change error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
}
?>