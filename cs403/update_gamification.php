<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require 'config.php';

$response = ['status' => 'error', 'message' => 'An unknown error occurred'];

// รับข้อมูล (อาจจะมาจากระบบบันทึกขยะ หรือผู้ใช้กรอกเอง)
$group_id = filter_input(INPUT_POST, 'group_id', FILTER_VALIDATE_INT);
$added_weight = filter_input(INPUT_POST, 'weight', FILTER_VALIDATE_FLOAT); // น้ำหนักที่เพิ่ม
$user_id = $_SESSION['user_id'] ?? null; // ผู้ใช้ที่เพิ่มน้ำหนัก

// --- ตรวจสอบข้อมูล ---
if ($user_id === null) {
    $response['message'] = 'User not logged in.';
    http_response_code(401);
    echo json_encode($response);
    exit;
}
if (!$group_id || $group_id <= 0) {
    $response['message'] = 'Invalid or missing group_id.';
    http_response_code(400);
    echo json_encode($response);
    exit;
}
if ($added_weight === null || $added_weight <= 0) {
    $response['message'] = 'Invalid or missing weight.';
    http_response_code(400);
    echo json_encode($response);
    exit;
}

try {
    // (สำคัญ) ตรวจสอบว่าผู้ใช้เป็นสมาชิกกลุ่มหรือไม่ ก่อนอนุญาตให้อัปเดต
    // ... (โค้ดคล้ายกับใน send_message.php) ...

    // --- อัปเดตน้ำหนักรวม ---
    $sql = "UPDATE `groups` SET total_weight = total_weight + ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("Database prepare failed: " . $conn->error);

    $stmt->bind_param("di", $added_weight, $group_id); // 'd' for double/decimal

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response['status'] = 'success';
            $response['message'] = 'Total weight updated.';
            unset($response['message']);

            // ดึงน้ำหนักใหม่กลับไปแสดงผล (Optional)
            $get_weight_sql = "SELECT total_weight FROM `groups` WHERE id = ?";
            $stmt_get = $conn->prepare($get_weight_sql);
            $stmt_get->bind_param("i", $group_id);
            $stmt_get->execute();
            $weight_result = $stmt_get->get_result();
            if($weight_row = $weight_result->fetch_assoc()){
                 $response['new_total_weight'] = $weight_row['total_weight'];
            }
            $stmt_get->close();

        } else {
            $response['message'] = 'Group not found or weight not updated.';
            // อาจจะเกิดจาก group_id ไม่ถูกต้อง
        }
    } else {
        throw new Exception("Database execute failed: " . $stmt->error);
    }
    $stmt->close();

} catch (Exception $e) {
    $response['message'] = "Error updating gamification data: " . $e->getMessage();
    http_response_code(500);
    error_log("Error in update_gamification.php: GroupID=" . $group_id . " UserID=" . $user_id . " Weight=" . $added_weight . " - " . $e->getMessage());
} finally {
     if (isset($conn)) $conn->close();
}

echo json_encode($response);
?>
