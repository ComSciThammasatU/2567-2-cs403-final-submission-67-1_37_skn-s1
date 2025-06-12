<?php
header('Content-Type: application/json');
session_start();
require 'config.php'; // ไฟล์เชื่อมต่อฐานข้อมูลของคุณ

$response = ['status' => 'error', 'message' => 'An unknown error occurred'];

// 1. ตรวจสอบการล็อกอินและประเภทผู้ใช้
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'organization') {
    $response['message'] = 'Access denied. Please login as an organization.';
    echo json_encode($response);
    exit;
}

// 2. รับข้อมูลจาก POST
if (!isset($_POST['id']) || !isset($_POST['status'])) {
    $response['message'] = 'Missing required parameters (id, status).';
    echo json_encode($response);
    exit;
}

$recycling_id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
$requested_status = trim($_POST['status']); // สถานะที่กดจากปุ่ม ('รับ', 'ไม่รับ', 'กำลังไป', 'สำเร็จแล้ว')
$organization_id = $_SESSION['user_id'];

// 3. ตรวจสอบข้อมูล Input
$allowed_statuses = ['รับ', 'ไม่รับ', 'กำลังไป', 'สำเร็จแล้ว'];
if ($recycling_id === false || $recycling_id <= 0) {
    $response['message'] = 'Invalid recycling ID.';
    echo json_encode($response);
    exit;
}
if (!in_array($requested_status, $allowed_statuses)) {
    $response['message'] = 'Invalid status value provided.';
    echo json_encode($response);
    exit;
}

// 4. กำหนดสถานะที่จะบันทึกลง DB จริง
$final_db_status = $requested_status; // Default
if ($requested_status === 'ไม่รับ') {
    $final_db_status = 'ยกเลิกโดยองค์กร'; // สถานะใน DB สำหรับการปฏิเสธ
} elseif ($requested_status === 'สำเร็จแล้ว') {
    $final_db_status = 'เสร็จสิ้น'; // สถานะใน DB สำหรับการเสร็จสิ้น
}
// 'รับ' และ 'กำลังไป' ใช้ค่าตรงๆ

try {
    $conn->begin_transaction();
    // 5. ตรวจสอบสิทธิ์ (สำคัญมาก!) - เช็คว่ารายการนี้เป็นขององค์กรที่ล็อกอินจริงหรือไม่
    $check_sql = "SELECT id FROM recyclings WHERE id = ? AND organization_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    if (!$check_stmt) throw new Exception("Check prepare failed: " . $conn->error);
    $check_stmt->bind_param("ii", $recycling_id, $organization_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        $check_stmt->close();
        $conn->rollback();
        $response['message'] = 'Permission denied or invalid ID for this organization.';
        echo json_encode($response);
        exit;
    }
    $check_stmt->close();
    // --- เพิ่ม: ตรรกะการให้คะแนนเมื่อสถานะเป็น 'เสร็จสิ้น' ---
    if ($final_db_status === 'เสร็จสิ้น') {
        // ดึง user_id และ weight จาก recyclings
        $get_data_sql = "SELECT user_id, weight FROM recyclings WHERE id = ?";
        $get_data_stmt = $conn->prepare($get_data_sql);
        if (!$get_data_stmt) throw new Exception("Get data prepare failed: " . $conn->error);
        $get_data_stmt->bind_param("i", $recycling_id);
        $get_data_stmt->execute();
        $data_result = $get_data_stmt->get_result();
        
        if ($row = $data_result->fetch_assoc()) {
            $seller_user_id = $row['user_id'];
            $weight = $row['weight'];

            // คำนวณคะแนน
            $points_to_add = round((float)$weight * 2.5);

            // อัปเดต total_points ในตาราง users
            if ($points_to_add > 0) { // เพิ่มคะแนนเฉพาะเมื่อมีค่ามากกว่า 0
                $update_points_sql = "UPDATE users SET total_points = total_points + ? WHERE id = ?";
                $update_points_stmt = $conn->prepare($update_points_sql);
                if (!$update_points_stmt) throw new Exception("Update points prepare failed: " . $conn->error);
                $update_points_stmt->bind_param("ii", $points_to_add, $seller_user_id);
                if (!$update_points_stmt->execute()) {
                    throw new Exception("Update points execute failed: " . $update_points_stmt->error);
                }
                $update_points_stmt->close();
            }
        } else {
            // ไม่ควรเกิดขึ้นถ้า check permission ผ่าน แต่ใส่ไว้เผื่อ
            throw new Exception("Could not retrieve recycling data for points calculation.");
        }
        $get_data_stmt->close();
    }
    // --- สิ้นสุด: ตรรกะการให้คะแนน ---

    // 6. อัปเดตสถานะในฐานข้อมูล
    $update_sql = "UPDATE recyclings SET status = ? WHERE id = ? AND organization_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    if (!$update_stmt) throw new Exception("Update prepare failed: " . $conn->error);

    $update_stmt->bind_param("sii", $final_db_status, $recycling_id, $organization_id);

    if ($update_stmt->execute()) {
         // หากการดำเนินการทั้งหมดใน transaction (เช่น อัปเดตคะแนน และอัปเดตสถานะ) สำเร็จ
        // ให้ทำการ commit transaction
        $conn->commit();
        if ($update_stmt->affected_rows > 0) {
            $response['status'] = 'success';
            $response['message'] = 'Status updated successfully to ' . $final_db_status;
        } else {
            // อาจจะเกิดกรณีที่สถานะเป็นค่าเดิมอยู่แล้ว หรือ ID ไม่ตรง (ซึ่งไม่ควรเกิดเพราะเช็คไปแล้ว)
            $response['status'] = 'success'; // ถือว่าสำเร็จ แต่ไม่มีอะไรเปลี่ยนแปลง
            $response['message'] = 'Status is already set to \'' . htmlspecialchars($final_db_status) . '\' or no changes were needed.';
        }
        $conn->commit();
    } else {
        throw new Exception("Database update failed: " . $update_stmt->error);
    }

    $update_stmt->close();

} catch (Exception $e) {
    $conn->rollback();
    $response['message'] = "Transaction failed: " . $e->getMessage(); // ปรับข้อความให้ครอบคลุมมากขึ้น
    error_log("Error in update_recycling_status.php: " . $e->getMessage());
}

$conn->close();
echo json_encode($response);
?>
