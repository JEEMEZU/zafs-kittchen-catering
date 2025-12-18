<?php
session_start();
require_once 'connection.php';

// ✅ THIS SCRIPT CHECKS AND AUTO-CANCELS EXPIRED BOOKINGS

try {
    $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
    
    // Find approved bookings with expired downpayment deadline
    $stmt = $conn->prepare("
        SELECT id, full_name, celebrant_name, event_date, downpayment_deadline, total_price
        FROM bookings 
        WHERE booking_status = 'approved' 
        AND downpayment_status != 'paid'
        AND downpayment_deadline < NOW()
        AND auto_cancelled = FALSE
    ");
    
    $stmt->execute();
    $expiredBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $cancelledCount = 0;
    
    foreach ($expiredBookings as $booking) {
        // Cancel the booking
        $cancelStmt = $conn->prepare("
            UPDATE bookings 
            SET booking_status = 'cancelled',
                auto_cancelled = TRUE,
                rejection_reason = 'Automatically cancelled - Downpayment deadline expired',
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        
        $cancelStmt->execute([$booking['id']]);
        
        if ($cancelStmt->rowCount() > 0) {
            $cancelledCount++;
            error_log("AUTO-CANCEL: Booking #{$booking['id']} - {$booking['full_name']} - Deadline: {$booking['downpayment_deadline']}");
        }
    }
    
    if ($cancelledCount > 0) {
        error_log("AUTO-CANCEL SUMMARY: {$cancelledCount} booking(s) cancelled due to expired downpayment deadline");
    }
    
    // Return JSON for AJAX calls
    echo json_encode([
        'success' => true,
        'checked_at' => $now->format('Y-m-d H:i:s'),
        'cancelled_count' => $cancelledCount
    ]);
    
} catch (Exception $e) {
    error_log("AUTO-CANCEL ERROR: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>