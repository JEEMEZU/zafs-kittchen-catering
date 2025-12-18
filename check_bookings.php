<?php
require_once 'connection.php';

echo "<h1>📋 All Bookings in Database</h1>";
echo "<hr>";

try {
    $stmt = $conn->prepare("
        SELECT 
            b.id,
            b.full_name,
            b.celebrant_name,
            b.event_type,
            b.booking_status,
            b.created_at,
            u.email,
            u.name as user_name
        FROM bookings b 
        LEFT JOIN usertable u ON b.user_id = u.id 
        ORDER BY b.id DESC
        LIMIT 20
    ");
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($bookings) == 0) {
        echo "<p style='color: red;'>❌ NO BOOKINGS FOUND!</p>";
        echo "<p>Create a booking first before testing.</p>";
    } else {
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
        echo "<tr style='background: #DC2626; color: white;'>
                <th>ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Celebrant</th>
                <th>Event Type</th>
                <th>Status</th>
                <th>Created</th>
              </tr>";
        
        foreach ($bookings as $booking) {
            $email = $booking['email'] ?? '❌ NO EMAIL';
            $status_color = '';
            
            switch($booking['booking_status']) {
                case 'pending': $status_color = 'orange'; break;
                case 'approved': $status_color = 'green'; break;
                case 'cancelled': $status_color = 'red'; break;
            }
            
            echo "<tr>";
            echo "<td><strong>" . $booking['id'] . "</strong></td>";
            echo "<td>" . htmlspecialchars($booking['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($email) . "</td>";
            echo "<td>" . htmlspecialchars($booking['celebrant_name']) . "</td>";
            echo "<td>" . htmlspecialchars($booking['event_type']) . "</td>";
            echo "<td style='color: $status_color; font-weight: bold;'>" . strtoupper($booking['booking_status']) . "</td>";
            echo "<td>" . date('M d, Y', strtotime($booking['created_at'])) . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
        echo "<hr>";
        echo "<h2>✅ Use any ID from above for testing!</h2>";
        echo "<p>Example: If you see ID <strong>5</strong>, update test_approval.php line 6 to:</p>";
        echo "<code style='background: #f0f0f0; padding: 10px; display: block;'>\$booking_id = 5; // Change this</code>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database Error: " . $e->getMessage() . "</p>";
}
?>
