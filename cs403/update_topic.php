<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require 'config.php';

$response = ['status' => 'error', 'message' => 'An unknown error occurred'];

// รับข้อมูลจาก POST
$group_id = filter_input(INPUT_POST, 'group_id', FILTER_VALIDATE_INT);
$topic = trim($_POST['topic'] ?? ''); // อนุญาตให้เป็นค่าว่างได้
$session_user_id = $_SESSION['user_id'] ?? null;

// --- ตรวจสอบข้อมูล ---
if ($session_user_id === null) {
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

try {
    // (สำคัญ) ตรวจสอบว่าผู้ใช้มีสิทธิ์แก้ไขหัวข้อหรือไม่ (เช่น เป็นสมาชิก หรือเป็น creator)
    $check_permission_sql = "SELECT 1 FROM group_members WHERE group_id = ? AND user_id = ?"; // ตัวอย่าง: เช็คแค่ว่าเป็นสมาชิก
    // หรือ $check_permission_sql = "SELECT 1 FROM `groups` WHERE id = ? AND creator_id = ?"; // เช็คว่าเป็น creator
    $stmt_check = $conn->prepare($check_permission_sql);
    if (!$stmt_check) throw new Exception("Prepare permission check failed: " . $conn->error);
    $stmt_check->bind_param("ii", $group_id, $session_user_id); // ปรับ type ถ้าเช็ค creator
    $stmt_check->execute();
    $permission_result = $stmt_check->get_result();
    if ($permission_result->num_rows === 0) {
        $response['message'] = 'You do not have permission to edit this topic.';
        http_response_code(403); // Forbidden
        $stmt_check->close();
        echo json_encode($response);
        exit;
    }
    $stmt_check->close();


    // --- อัปเดตหัวข้อ ---
    $sql = "UPDATE `groups` SET topic = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("Database prepare update failed: " . $conn->error);

    $stmt->bind_param("si", $topic, $group_id);

    if ($stmt->execute()) {
        // affected_rows อาจเป็น 0 ถ้าใส่ topic เดิม
        $response['status'] = 'success';
        $response['message'] = 'Topic updated successfully.';
        unset($response['message']);
    } else {
        throw new Exception("Database execute failed: " . $stmt->error);
    }
    $stmt->close();

} catch (Exception $e) {
    $response['message'] = "Error updating topic: " . $e->getMessage();
    http_response_code(500);
    error_log("Error in update_topic.php: GroupID=" . $group_id . " UserID=" . $session_user_id . " - " . $e->getMessage());
} finally {
     if (isset($conn)) $conn->close();
}

echo json_encode($response);
?>
