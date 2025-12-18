<?php 
date_default_timezone_set('Asia/Manila');
session_start();
require_once 'connection.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated.']);
    exit;
}

$userId = $_SESSION['user_id'];
$avatarUrl = '';

// Handle FILE upload
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['avatar'];
    
    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, GIF, and WebP files are allowed.']);
        exit;
    }
    
    // Validate file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'File size must be less than 5MB.']);
        exit;
    }
    
    // Create uploads directory if not exists
    $uploadDir = 'uploads/avatars/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Generate unique filename
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = 'avatar_' . $userId . '_' . time() . '.' . $fileExtension;
    $filePath = $uploadDir . $fileName;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        $avatarUrl = '/' . $filePath;
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload file.']);
        exit;
    }
} 
// Handle URL upload (existing functionality)
else if (isset($_POST['avatar_url'])) {
    $avatarUrl = trim($_POST['avatar_url']);
    
    if (empty($avatarUrl)) {
        echo json_encode(['success' => false, 'message' => 'Avatar URL is required.']);
        exit;
    }
    
    // Validate URL
    if (!filter_var($avatarUrl, FILTER_VALIDATE_URL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid avatar URL.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No avatar data provided.']);
    exit;
}

try {
    // Update avatar in database
    $query = "UPDATE usertable SET avatar_url = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt->execute([$avatarUrl, $userId])) {
        // Update session variable
        $_SESSION['avatar_url'] = $avatarUrl;
        
        echo json_encode([
            'success' => true, 
            'message' => 'Avatar saved successfully!', 
            'avatar_url' => $avatarUrl
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save avatar.']);
    }
    
} catch (PDOException $e) {
    error_log("Avatar save error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred.']);
}
?>