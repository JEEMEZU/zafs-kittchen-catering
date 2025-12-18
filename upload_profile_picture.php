<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit;
}

$file = $_FILES['profile_picture'];
$user_id = $_SESSION['user_id'];

// Validate file type
$allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
$file_type = mime_content_type($file['tmp_name']);

if (!in_array($file_type, $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and GIF allowed.']);
    exit;
}

// Validate file size (max 5MB)
$max_size = 5 * 1024 * 1024; // 5MB in bytes
if ($file['size'] > $max_size) {
    echo json_encode(['success' => false, 'message' => 'File too large. Maximum size is 5MB.']);
    exit;
}

// Create uploads directory if it doesn't exist
$upload_dir = 'uploads/profile_pictures/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'user_' . $user_id . '_' . time() . '.' . $extension;
$filepath = $upload_dir . $filename;

// Delete old profile picture if exists
require_once 'connection.php';
try {
    $stmt = $conn->prepare("SELECT avatar_url FROM usertable WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && $user['avatar_url'] && strpos($user['avatar_url'], 'uploads/') === 0) {
        // It's an uploaded file, delete it
        if (file_exists($user['avatar_url'])) {
            unlink($user['avatar_url']);
        }
    }
} catch (Exception $e) {
    // Continue even if deletion fails
    error_log("Error deleting old picture: " . $e->getMessage());
}

// Move uploaded file
if (!move_uploaded_file($file['tmp_name'], $filepath)) {
    echo json_encode(['success' => false, 'message' => 'Failed to save file']);
    exit;
}

// Update database
try {
    $stmt = $conn->prepare("UPDATE usertable SET avatar_url = ? WHERE id = ?");
    $stmt->execute([$filepath, $user_id]);
    
    // Update session
    $_SESSION['avatar_url'] = $filepath;
    
    echo json_encode([
        'success' => true,
        'message' => 'Profile picture uploaded successfully',
        'avatar_url' => $filepath
    ]);
} catch (Exception $e) {
    // Delete uploaded file if database update fails
    if (file_exists($filepath)) {
        unlink($filepath);
    }
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>