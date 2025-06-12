<?php
header('Content-Type: application/json');
session_start(); // เริ่ม session เพื่อดึง user_id

require 'config.php'; // ต้องแน่ใจว่า path ไปยังไฟล์เชื่อมต่อฐานข้อมูลถูกต้อง

$response = ['status' => 'error', 'message' => 'An unknown error occurred'];

// ตรวจสอบว่ามี user_id ใน session หรือไม่
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // เตรียม SQL query เพื่อดึงข้อมูลจากตาราง recyclings เฉพาะ user_id นี้
    // เลือกเฉพาะคอลัมน์ที่ต้องการแสดงผล และเรียงตาม timestamp ล่าสุดก่อน
    $sql = "SELECT id, waste_type, weight, address, buyer_name, timestamp,status
            FROM recyclings
            WHERE user_id = ?
            ORDER BY timestamp DESC"; // เรียงจากใหม่ไปเก่า

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Database prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $user_id); // "i" คือ integer type สำหรับ user_id
    $stmt->execute();
    $result = $stmt->get_result();

    $recyclings_data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // จัดรูปแบบ timestamp ถ้าต้องการ (ตัวอย่าง: 'd/m/Y H:i')
            $row['timestamp_formatted'] = date('d/m/Y H:i', strtotime($row['timestamp']));
            $recyclings_data[] = $row;
        }
        $response['status'] = 'success';
        $response['data'] = $recyclings_data;
        unset($response['message']); // ลบ message ถ้าสำเร็จ
    } else {
        // ไม่พบข้อมูลสำหรับ user นี้
        $response['status'] = 'success'; // ถือว่า query สำเร็จ แต่ไม่มีข้อมูล
        $response['data'] = []; // ส่ง array ว่างกลับไป
        unset($response['message']);
    }

    $stmt->close();

} catch (Exception $e) {
    $response['message'] = "Database query failed: " . $e->getMessage();
    // Log error จริงๆ ควรทำใน production
    // error_log("Error in get_user_recyclings.php: " . $e->getMessage());
}

$conn->close();
echo json_encode($response);
?>
