<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require 'config.php';

$response = ['status' => 'error', 'message' => 'An unknown error occurred'];

$group_id = filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT);
$current_user_id = $_SESSION['user_id'] ?? null;

if (!$group_id || $group_id <= 0) {
    $response['message'] = 'Invalid or missing group_id.';
    http_response_code(400);
    echo json_encode($response);
    exit;
}

if ($current_user_id === null) {
    $response['message'] = 'User not logged in.';
    http_response_code(401);
    echo json_encode($response);
    exit;
}

// (Optional) ตรวจสอบว่าเป็นสมาชิกกลุ่มหรือไม่ ก่อนอนุญาตให้ดึงข้อความ
// ...

try {
    // ดึงข้อความล่าสุด (เช่น 100 ข้อความ) พร้อมชื่อผู้ส่ง
    $limit = 100; // กำหนดจำนวนข้อความที่จะโหลด
    $sql = "SELECT cm.id, cm.user_id, cm.message, cm.timestamp, u.firstname AS sender_name
            FROM chat_messages cm
            JOIN users u ON cm.user_id = u.id
            WHERE cm.group_id = ?
            ORDER BY cm.timestamp DESC -- ดึงจากล่าสุดก่อน
            LIMIT ?"; // จำกัดจำนวน

    $stmt = $conn->prepare($sql);
     if (!$stmt) throw new Exception("Prepare messages query failed: " . $conn->error);
    $stmt->bind_param("ii", $group_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages_data = [];
    while ($row = $result->fetch_assoc()) {
        // จัดรูปแบบ timestamp
        try {
            $timestamp = strtotime($row['timestamp']);
            $row['timestamp_formatted'] = ($timestamp !== false) ? date('H:i', $timestamp) : $row['timestamp']; // แสดงแค่เวลา
        } catch (Exception $e) {
            $row['timestamp_formatted'] = $row['timestamp'];
        }
        $messages_data[] = $row;
    }
    $stmt->close();

    // เรียงข้อมูลกลับจากเก่าไปใหม่ เพื่อให้ JavaScript แสดงผลถูกต้อง
    $messages_data = array_reverse($messages_data);

    $response['status'] = 'success';
    $response['messages'] = $messages_data;
    unset($response['message']);

} catch (Exception $e) {
    $response['message'] = "Error fetching messages: " . $e->getMessage();
    http_response_code(500);
    error_log("Error in get_messages.php: GroupID=" . $group_id . " UserID=" . $current_user_id . " - " . $e->getMessage());
} finally {
     if (isset($conn)) $conn->close();
}

echo json_encode($response);
?>
