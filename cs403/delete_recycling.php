<?php
header('Content-Type: application/json');
session_start(); // เริ่ม session เพื่อตรวจสอบ user_id

require 'config.php'; // ตรวจสอบ path การเชื่อมต่อ DB

$response = ['status' => 'error', 'message' => 'An unknown error occurred'];

// 1. ตรวจสอบว่า User Login หรือยัง
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}
$current_user_id = $_SESSION['user_id'];

// 2. ตรวจสอบว่ามีการส่ง ID ของรายการมาหรือไม่ และเป็น POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    $response['message'] = 'Invalid request method or missing ID.';
    echo json_encode($response);
    exit;
}

$recycling_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($recycling_id === false || $recycling_id <= 0) {
    $response['message'] = 'Invalid recycling ID.';
    echo json_encode($response);
    exit;
}

// 3. เตรียมคำสั่ง SQL เพื่อลบข้อมูล
// *** สำคัญ: ต้องลบเฉพาะรายการที่เป็นของ User คนนี้เท่านั้น ***
$sql = "DELETE FROM recyclings WHERE id = ? AND user_id = ?";

try {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Database prepare failed: " . $conn->error);
    }

    // ผูกค่า id และ user_id
    $stmt->bind_param("ii", $recycling_id, $current_user_id);

    // Execute คำสั่ง
    if ($stmt->execute()) {
        // ตรวจสอบว่ามีแถวถูกลบจริงหรือไม่
        if ($stmt->affected_rows > 0) {
            $response['status'] = 'success';
            $response['message'] = 'Recycling record deleted successfully.';
        } else {
            // ไม่พบรายการที่ตรงกับ ID และ User ID นี้ (อาจจะถูกลบไปแล้ว หรือ ID ไม่ถูกต้อง)
            $response['message'] = 'Record not found or you do not have permission to delete it.';
        }
    } else {
        throw new Exception("Database execute failed: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    $response['message'] = "Database operation failed: " . $e->getMessage();
    // ควร Log error จริงๆ ในระบบ Production
    // error_log("Error in delete_recycling.php: " . $e->getMessage());
}

$conn->close();
echo json_encode($response);
?>
