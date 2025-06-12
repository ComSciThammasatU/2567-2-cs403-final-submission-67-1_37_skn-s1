<?php
session_start(); // เริ่ม session เพื่อเข้าถึง user_id
header('Content-Type: application/json');
include 'config.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อนสร้างกลุ่ม']);
    exit;
}

$currentUserId = $_SESSION['user_id']; // ID ของผู้ใช้ที่สร้างกลุ่ม
// --- DEBUG: ตรวจสอบ ID ผู้ใช้ปัจจุบัน ---
// error_log("Current User ID: " . $currentUserId);
// ------------------------------------

$groupName = trim($_POST['title'] ?? '');
$groupDetail = trim($_POST['content'] ?? '');
$inputAuthorizedUserIds = trim($_POST['userId'] ?? ''); // ID ที่ผู้ใช้กรอกมา
// --- DEBUG: ตรวจสอบ ID ที่รับมาจากฟอร์ม ---
// error_log("Input User IDs from form: " . $inputAuthorizedUserIds);
// ---------------------------------------

// ตรวจสอบว่าข้อมูลที่จำเป็นไม่ว่างเปล่า
if (empty($groupName) || empty($groupDetail)) {
    echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกชื่อกลุ่มและรายละเอียด']);
    exit;
}

// เตรียม array ของ ID ผู้มีสิทธิ์
$authorizedUserIdsArray = [];
if (!empty($inputAuthorizedUserIds)) {
    $authorizedUserIdsArray = array_map('trim', explode(',', $inputAuthorizedUserIds));
    $authorizedUserIdsArray = array_filter($authorizedUserIdsArray, function($id) {
        return filter_var($id, FILTER_VALIDATE_INT) && $id > 0;
    });
    // --- DEBUG: ตรวจสอบ Array หลัง Filter ---
    // error_log("Filtered Input IDs Array: " . print_r($authorizedUserIdsArray, true));
    // -------------------------------------
}

// เพิ่ม ID ของผู้สร้างกลุ่มเข้าไปใน array ถ้ายังไม่มี
if (!in_array($currentUserId, $authorizedUserIdsArray)) {
    $authorizedUserIdsArray[] = $currentUserId;
    // --- DEBUG: แจ้งเตือนเมื่อเพิ่ม ID ผู้สร้าง ---
    // error_log("Creator ID ($currentUserId) added to array.");
    // ----------------------------------------
} else {
    // --- DEBUG: แจ้งเตือนถ้า ID ผู้สร้างมีอยู่แล้ว ---
    // error_log("Creator ID ($currentUserId) already exists in array.");
    // ------------------------------------------
}

// ทำให้ ID ไม่ซ้ำกัน
$authorizedUserIdsArray = array_unique($authorizedUserIdsArray);
// --- DEBUG: ตรวจสอบ Array หลัง Unique ---
// error_log("Array after unique: " . print_r($authorizedUserIdsArray, true));
// -------------------------------------

// แปลง array กลับเป็น string คั่นด้วยคอมม่า
$finalAuthorizedUserIds = implode(',', $authorizedUserIdsArray);
// --- DEBUG: ตรวจสอบ String สุดท้ายก่อนบันทึก ---
// error_log("Final Authorized IDs String to save: " . $finalAuthorizedUserIds);
// --------------------------------------------

// เพิ่มข้อมูลลงในตาราง Xanthophyceae_Coagulum_Repository
$sql = "INSERT INTO Xanthophyceae_Coagulum_Repository (group_name, group_detail, authorized_user_ids, target_weight) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sssd", $groupName, $groupDetail, $finalAuthorizedUserIds, $targetWeight);
    $targetWeight = $_POST['targetWeight'] ?? null; // รับค่า targetWeight
    if ($stmt->execute()) {
        $new_group_id = $conn->insert_id;
        echo json_encode([
            'status' => 'success',
            'message' => 'สร้างกลุ่มสำเร็จ',
            'group_id' => $new_group_id,
            'group_name' => $groupName,
            'group_detail' => $groupDetail,
            'authorized_user_ids' => $finalAuthorizedUserIds,
            'target_weight' => $targetWeight
        ]);
    } else {
        error_log("Error executing statement: " . $stmt->error);
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการสร้างกลุ่ม']);
    }
    $stmt->close();
} else {
    error_log("Error preparing statement: " . $conn->error);
    echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL']);
}

$conn->close();
?>
