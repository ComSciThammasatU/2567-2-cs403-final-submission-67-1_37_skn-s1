<?php
// save_recycling.php
require_once 'config.php'; // รวมไฟล์ config.php

header('Content-Type: application/json'); // Set header early

// รับข้อมูลจาก AJAX (ใช้ ?? null เพื่อป้องกัน warning ถ้าไม่มี key)
$user_id = $_POST['user_id'] ?? null;
$waste_type = $_POST['waste_type'] ?? null;
$weight = $_POST['weight'] ?? null;
$address = $_POST['address'] ?? null;
$latitude = $_POST['latitude'] ?? null;
$longitude = $_POST['longitude'] ?? null;
$buyer_name = $_POST['buyer_name'] ?? null; // ชื่อองค์กรที่เลือก

$response = ['status' => 'error']; // Default response

// --- ตรวจสอบข้อมูลเบื้องต้น ---
if (empty($user_id) || empty($waste_type) || empty($weight) || empty($address) || empty($buyer_name)) {
    $response['message'] = 'Missing required fields.';
    echo json_encode($response);
    exit;
}
// ควรเพิ่ม validation อื่นๆ เช่น is_numeric สำหรับ weight, latitude, longitude

$organization_id = null;

// --- หา organization_id จาก buyer_name ---
try {
    // ใช้ Prepared Statement เพื่อความปลอดภัย
    $org_sql = "SELECT id FROM users WHERE firstname = ? AND user_type = 'organization'";
    $org_stmt = $conn->prepare($org_sql);
    if (!$org_stmt) {
        throw new Exception("Failed to prepare organization query: " . $conn->error);
    }
    $org_stmt->bind_param("s", $buyer_name);
    $org_stmt->execute();
    $org_result = $org_stmt->get_result();
    if ($org_row = $org_result->fetch_assoc()) {
        $organization_id = $org_row['id'];
    }
    $org_stmt->close();

    // ตรวจสอบว่าหา organization_id เจอหรือไม่
    if ($organization_id === null) {
        // อาจจะแจ้ง error หรือใช้ค่า default ถ้ามีนโยบายอื่น
        throw new Exception("Organization '$buyer_name' not found or is not registered as an organization.");
    }

    // --- SQL สำหรับบันทึกข้อมูล (ใช้ Prepared Statement) ---
    // เพิ่ม organization_id และ status เริ่มต้นใน INSERT
    $sql = "INSERT INTO recyclings (user_id, waste_type, weight, address, latitude, longitude, buyer_name, organization_id, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'รอการตอบกลับ')"; // กำหนด status เริ่มต้น

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
         throw new Exception("Failed to prepare insert query: " . $conn->error);
    }

    // Bind parameters (i=int, s=string, d=double)
    // ตรวจสอบ type ให้ตรง: user_id(i), waste_type(s), weight(d), address(s), lat(d), lon(d), buyer_name(s), org_id(i)
    // *** แก้ไข type ของ weight, latitude, longitude เป็น 'd' (double) ***
    $stmt->bind_param("isdsddsi",
        $user_id,
        $waste_type,
        $weight,
        $address,
        $latitude,
        $longitude,
        $buyer_name,
        $organization_id // เพิ่ม organization_id ที่หามาได้
    );

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Recycling request saved successfully.';
    } else {
        throw new Exception("Failed to execute insert query: " . $stmt->error);
    }
    $stmt->close();

} catch (Exception $e) {
    $response['message'] = "Error saving recycling request: " . $e->getMessage();
    error_log("Error in save_recycling.php: " . $e->getMessage()); // Log the error
}

$conn->close();
echo json_encode($response);
?>
