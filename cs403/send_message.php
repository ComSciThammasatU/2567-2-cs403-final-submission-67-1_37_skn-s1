<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require 'config.php';

$response = ['status' => 'error', 'message' => 'An unknown error occurred'];

// รับข้อมูลจาก POST
$group_id = filter_input(INPUT_POST, 'group_id', FILTER_VALIDATE_INT);
$message = trim($_POST['message'] ?? '');
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
if (empty($message)) {
    $response['message'] = 'Message cannot be empty.';
    http_response_code(400);
    echo json_encode($response);
    exit;
}

try {
    // (สำคัญ) ตรวจสอบว่าผู้ใช้เป็นสมาชิกกลุ่มหรือไม่ ก่อนอนุญาตให้ส่งข้อความ
    $check_member_sql = "SELECT 1 FROM group_members WHERE group_id = ? AND user_id = ?";
    $stmt_check = $conn->prepare($check_member_sql);
    if (!$stmt_check) throw new Exception("Prepare member check failed: " . $conn->error);
    $stmt_check->bind_param("ii", $group_id, $session_user_id);
    $stmt_check->execute();
    $member_result = $stmt_check->get_result();
    if ($member_result->num_rows === 0) {
        $response['message'] = 'You are not a member of this group.';
        http_response_code(403); // Forbidden
        $stmt_check->close();
        echo json_encode($response);
        exit;
    }
    $stmt_check->close();

    // --- บันทึกข้อความ ---
    $sql = "INSERT INTO chat_messages (group_id, user_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("Database prepare insert failed: " . $conn->error);

    $stmt->bind_param("iis", $group_id, $session_user_id, $message);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message_id'] = $stmt->insert_id; // ส่ง ID ข้อความกลับไปด้วย
        unset($response['message']);
    } else {
        throw new Exception("Database execute failed: " . $stmt->error);
    }
    $stmt->close();

} catch (Exception $e) {
    $response['message'] = "Error sending message: " . $e->getMessage();
    http_response_code(500);
    error_log("Error in send_message.php: GroupID=" . $group_id . " UserID=" . $session_user_id . " - " . $e->getMessage());
} finally {
     if (isset($conn)) $conn->close();
}

echo json_encode($response);
?>
