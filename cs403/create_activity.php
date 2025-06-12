<?php
// create_activity.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อน']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $title = $_POST['activityTitle'] ?? '';
    $description = $_POST['activityDetail'] ?? '';
    $imageUrl = $_POST['activityImageUrl'] ?? ''; // รับ URL จากฟอร์ม
    $fileUrls = $_POST['activityFileUrls'] ?? []; // รับ URL ไฟล์แนบ

    if (empty($title) || empty($description) || empty($imageUrl)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
        exit;
    }

    $timestamp = date('Y-m-d H:i:s');

    // Insert data into 'services' table
    $sql = "INSERT INTO services (user_id, title, description, imageUrl, timestamp) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("issss", $user_id, $title, $description, $imageUrl, $timestamp);

    if ($stmt->execute()) {
        $service_id = $conn->insert_id; // Get the ID of the newly inserted service

        // Handle file URLs
        if (!empty($fileUrls)) {
            foreach ($fileUrls as $fileUrl) {
                // Insert file URL into the 'service_files' table
                $fileSql = "INSERT INTO service_files (service_id, file_path) VALUES (?, ?)";
                $fileStmt = $conn->prepare($fileSql);
                if ($fileStmt === false) {
                    echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL (service_files): ' . $conn->error]);
                    exit;
                }
                $fileStmt->bind_param("is", $service_id, $fileUrl);
                if (!$fileStmt->execute()) {
                    echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูลไฟล์: ' . $fileStmt->error]);
                    $fileStmt->close();
                }
                $fileStmt->close();
            }
        }
        echo json_encode(['status' => 'success', 'message' => 'สร้างกิจกรรมสำเร็จ', 'user_id' => $user_id, 'service_id' => $service_id]); // เพิ่ม user_id
    } else {
        // เพิ่มการตรวจสอบ error อย่างละเอียด
        echo json_encode([
            'status' => 'error',
            'message' => 'เกิดข้อผิดพลาดในการสร้างกิจกรรม: ' . $stmt->error,
            'sql_error' => $stmt->error, // เพิ่ม error จาก $stmt->error
            'sql_state' => $stmt->sqlstate, // เพิ่ม sqlstate
            'user_id' => $user_id,
            'title' => $title,
            'description' => $description,
            'imageUrl' => $imageUrl,
            'timestamp' => $timestamp,
            'sql' => $sql, // เพิ่มคำสั่ง sql
            'fileUrls' => $fileUrls // เพิ่มข้อมูลไฟล์
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
