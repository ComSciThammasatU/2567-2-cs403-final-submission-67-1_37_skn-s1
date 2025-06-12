<?php
// /Applications/XAMPP/xamppfiles/htdocs/cs403/get_buyers.php
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL); // แสดง error เพื่อ debug
ini_set('display_errors', 1);

$buyers = [];
$response = [];

// --- SQL ดึงข้อมูลองค์กร + ที่อยู่ + ข้อมูลจากโพสต์ล่าสุด (ถ้ามี) ---
// ใช้ Subquery เพื่อหา id ของโพสต์ล่าสุดของแต่ละองค์กร
$sql = "SELECT
            u.id AS organization_id,
            u.firstname AS organization_name,
            ua.house_number,
            ua.sub_district,
            ua.district,
            ua.province,
            ua.postal_code,
            latest_br.min_kg,       -- ดึง min_kg จากโพสต์ล่าสุด
            latest_br.price_per_kg  -- ดึง price_per_kg จากโพสต์ล่าสุด
        FROM
            users u
        LEFT JOIN
            user_addresses ua ON u.id = ua.user_id
        LEFT JOIN -- Join กับ buy_requests ผ่าน Subquery ที่หาโพสต์ล่าสุด
            buy_requests latest_br ON u.id = latest_br.organization_id
            AND latest_br.id = (
                SELECT MAX(id) -- หา id ล่าสุด (สมมติว่า id เรียงตามเวลา)
                FROM buy_requests
                WHERE organization_id = u.id
            )
        WHERE
            u.user_type = 'organization'
        ORDER BY
            u.firstname ASC";

$result = $conn->query($sql);

if ($result === false) {
    http_response_code(500);
    $errorMessage = 'เกิดข้อผิดพลาดในการดึงข้อมูลผู้ซื้อ: ' . $conn->error;
    error_log("SQL Query Error (get_buyers): " . $conn->error . " | SQL: " . $sql);
    $response = ['status' => 'error', 'message' => $errorMessage];
} else {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // จัดรูปแบบที่อยู่
            $addressParts = array_filter([
                $row['house_number'], $row['sub_district'], $row['district'],
                $row['province'], $row['postal_code']
            ]);
            $row['location'] = !empty($addressParts) ? implode(', ', $addressParts) : 'ไม่ระบุที่อยู่';
            
            // ค่า min_kg และ price_per_kg จะมาจาก latest_br (อาจจะเป็น null ถ้าไม่มีโพสต์)
            // ไม่ต้องกำหนดเป็น null ซ้ำ

            $buyers[] = $row;
        }
        $response = ['status' => 'success', 'data' => $buyers];
    } else {
        $response = ['status' => 'success', 'data' => [], 'message' => 'ไม่พบข้อมูลองค์กรผู้รับซื้อ'];
    }
    $result->free();
}

$conn->close();

// ส่งข้อมูลกลับ (ยังคงส่งเฉพาะ array ข้อมูล)
if ($response['status'] === 'success') {
    echo json_encode($response['data']);
} else {
    // ถ้าเกิด error ควรส่ง JSON บอก error แทน array ว่าง
    // echo json_encode(['error' => $response['message']]);
    echo json_encode([]); // ส่ง array ว่างตามโค้ดเดิมไปก่อน
}
exit;
?>
