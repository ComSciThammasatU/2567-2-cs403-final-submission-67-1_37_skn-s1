<?php
session_start();
include 'config.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

header('Content-Type: application/json');

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];

// --- แก้ไขชื่อตารางตรงนี้ ---
$sql = "SELECT province, district, sub_district, house_number, postal_code FROM user_addresses WHERE user_id = ?"; // เปลี่ยนจาก addresses เป็น user_addresses
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    echo json_encode(['error' => 'Database query preparation failed.']);
    $conn->close();
    exit;
}

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // สมมติว่าผู้ใช้มีได้แค่ 1 ที่อยู่ ถ้ามีได้หลายที่อยู่ ต้องปรับโค้ดตรงนี้
    $addressData = $result->fetch_assoc();
    echo json_encode($addressData);
} else {
    // ส่ง error กลับไปเมื่อไม่พบข้อมูล
    echo json_encode(['error' => 'ไม่พบข้อมูลที่อยู่']);
}

$stmt->close();
$conn->close();
?>
