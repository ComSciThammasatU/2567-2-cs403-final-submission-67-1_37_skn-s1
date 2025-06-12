<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require 'config.php'; // ไฟล์เชื่อมต่อฐานข้อมูลของคุณ

$response = ['status' => 'error', 'message' => 'An unknown error occurred'];

// 1. ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Access denied. Please login.';
    http_response_code(401); // Unauthorized
    echo json_encode($response);
    exit;
}

$organization_user_id = $_SESSION['user_id'];
$organization_name = null;

try {
    // --- เพิ่ม: ดึงชื่อองค์กรที่ล็อกอินอยู่ ---
    $user_sql = "SELECT firstname FROM users WHERE id = ? AND user_type = 'organization'";
    $user_stmt = $conn->prepare($user_sql);
    if (!$user_stmt) {
        throw new Exception("Failed to prepare user query: " . $conn->error);
    }
    $user_stmt->bind_param("i", $organization_user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    if ($user_row = $user_result->fetch_assoc()) {
        $organization_name = $user_row['firstname'];
    }
    $user_stmt->close();

    if ($organization_name === null) {
        throw new Exception("Could not find organization name or user is not an organization.");
    }
    // --- สิ้นสุด: ดึงชื่อองค์กร ---


    // 2. ดึงข้อมูลรายการรีไซเคิลที่ buyer_name ตรงกับชื่อองค์กร
    // Join กับตาราง users เพื่อเอาชื่อผู้ส่ง (r.user_id คือ ID ของผู้ส่ง)
    $sql = "SELECT
                r.id,
                r.waste_type,
                r.weight,
                r.address,
                r.timestamp,
                r.buyer_name, -- ดึง buyer_name มาด้วย (ถึงแม้จะใช้กรองแล้ว)
                r.status, -- *** เพิ่ม: ดึงคอลัมน์ status จากตาราง recyclings ***
                u.firstname AS user_firstname,
                u.lastname AS user_lastname
            FROM
                recyclings r
            INNER JOIN
                users u ON r.user_id = u.id
            WHERE
                r.buyer_name = ? -- *** เพิ่มเงื่อนไขกรองด้วย buyer_name ***
            ORDER BY
                r.timestamp DESC";

    // ใช้ Prepared Statement เพราะมี parameter (?)
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        // กรณี prepare ล้มเหลว
        throw new Exception("Database prepare failed: " . $conn->error . " | SQL: " . $sql);
    }

    // Bind ชื่อองค์กรเข้ากับ parameter
    $stmt->bind_param("s", $organization_name);

    // Execute query
    if (!$stmt->execute()) {
         throw new Exception("Database execute failed: " . $stmt->error);
    }

    // Get result
    $result = $stmt->get_result();

    $data = [];
    // ตรวจสอบว่ามีข้อมูลที่ดึงมาได้หรือไม่
    if ($result->num_rows > 0) {
         while ($row = $result->fetch_assoc()) {
            // จัดรูปแบบ timestamp
            try {
                $timestamp = strtotime($row['timestamp']);
                if ($timestamp !== false) {
                    $row['timestamp_formatted'] = date('d M Y H:i', $timestamp);
                } else {
                     $row['timestamp_formatted'] = $row['timestamp'];
                     error_log("Warning: Invalid timestamp format in get_org_recyclings.php for recycling ID " . $row['id'] . ": " . $row['timestamp']);
                }
            } catch (Exception $dateEx) {
                 $row['timestamp_formatted'] = $row['timestamp'];
                 error_log("Error formatting timestamp in get_org_recyclings.php for recycling ID " . $row['id'] . ": " . $dateEx->getMessage());
            }

            $data[] = $row;
        }
        $response['status'] = 'success';
        $response['data'] = $data;
        unset($response['message']);
    } else {
        // กรณี query สำเร็จ แต่ไม่มีข้อมูลที่ตรงเงื่อนไข (ไม่มีรายการที่เลือกองค์กรนี้)
        $response['status'] = 'success';
        $response['data'] = [];
        unset($response['message']);
    }

    $stmt->close(); // ปิด statement

} catch (Exception $e) {
    // จัดการ Exception ที่เกิดขึ้น
    $response['message'] = "Error fetching data: " . $e->getMessage();
    http_response_code(500); // Internal Server Error
    error_log("Error in get_org_recyclings.php: " . $e->getMessage());
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();

// ส่งข้อมูลกลับเป็น JSON
if (json_encode($response) === false) {
    http_response_code(500);
    error_log("JSON Encode Error in get_org_recyclings.php: " . json_last_error_msg());
    echo json_encode(['status' => 'error', 'message' => 'JSON Encoding Error: ' . json_last_error_msg()]);
} else {
    echo json_encode($response);
}
exit;
?>
