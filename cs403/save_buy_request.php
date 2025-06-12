<?php
header('Content-Type: application/json'); // กำหนดให้ response เป็น JSON
include 'config.php'; // ไฟล์เชื่อมต่อฐานข้อมูล (ต้องมีอยู่จริง)

// ตรวจสอบว่าเป็น POST request หรือไม่
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

// รับข้อมูลจาก POST request
$organization_id = isset($_POST['organization_id']) ? trim($_POST['organization_id']) : null;
$plastic_type = isset($_POST['plastic_type']) ? trim($_POST['plastic_type']) : null;
$province = isset($_POST['province']) ? trim($_POST['province']) : null;
$district = isset($_POST['district']) ? trim($_POST['district']) : null;
$sub_district = isset($_POST['sub_district']) ? trim($_POST['sub_district']) : null;
$min_kg = isset($_POST['min_kg']) ? trim($_POST['min_kg']) : null;
$price_per_kg = isset($_POST['price_per_kg']) ? trim($_POST['price_per_kg']) : null;
$destination_info = isset($_POST['destination_info']) ? trim($_POST['destination_info']) : null;

// --- การตรวจสอบข้อมูลเบื้องต้น ---
$errors = [];
if (empty($organization_id) || !filter_var($organization_id, FILTER_VALIDATE_INT)) {
    $errors[] = 'Organization ID ไม่ถูกต้อง';
}
if (empty($plastic_type)) {
    $errors[] = 'กรุณาระบุประเภทพลาสติก';
}
if (empty($province)) {
    $errors[] = 'กรุณาระบุจังหวัด';
}
if (empty($district)) {
    $errors[] = 'กรุณาระบุอำเภอ';
}
if (empty($sub_district)) {
    $errors[] = 'กรุณาระบุตำบล';
}
if (empty($min_kg) || !is_numeric($min_kg) || $min_kg <= 0) {
    $errors[] = 'กรุณาระบุน้ำหนักขั้นต่ำที่ถูกต้อง (มากกว่า 0)';
}
if (empty($price_per_kg) || !is_numeric($price_per_kg) || $price_per_kg <= 0) {
    $errors[] = 'กรุณาระบุราคาต่อกิโลกรัมที่ถูกต้อง (มากกว่า 0)';
}
if (empty($destination_info)) {
    $errors[] = 'กรุณาระบุรายละเอียดการนำไปรีไซเคิล';
}

// ถ้ามีข้อผิดพลาด ให้ส่งกลับไป
if (!empty($errors)) {
    echo json_encode(['status' => 'error', 'message' => implode(', ', $errors)]);
    $conn->close();
    exit;
}

// --- เตรียมคำสั่ง SQL เพื่อบันทึกข้อมูล ---
$sql = "INSERT INTO buy_requests (organization_id, plastic_type, province, district, sub_district, min_kg, price_per_kg, destination_info)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

if ($stmt = $conn->prepare($sql)) {
    // Bind parameters (s = string, i = integer, d = double/decimal)
    // ตรวจสอบชนิดข้อมูลให้ถูกต้อง
    $stmt->bind_param("issssdds",
        $organization_id,
        $plastic_type,
        $province,
        $district,
        $sub_district,
        $min_kg,
        $price_per_kg,
        $destination_info
    );

    // Execute the statement
    if ($stmt->execute()) {
        // บันทึกสำเร็จ
        echo json_encode(['status' => 'success', 'message' => 'โพสต์รายการรับซื้อสำเร็จ']);
    } else {
        // บันทึกล้มเหลว
        error_log("SQL Error: " . $stmt->error); // บันทึก error ลง log ของ server
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $stmt->error]);
    }

    // ปิด statement
    $stmt->close();
} else {
    // เตรียม statement ล้มเหลว
    error_log("SQL Prepare Error: " . $conn->error); // บันทึก error ลง log ของ server
    echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: ' . $conn->error]);
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
