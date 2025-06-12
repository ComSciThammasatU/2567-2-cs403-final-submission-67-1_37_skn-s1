<?php
// เปิดการแสดงผล error (สำหรับตอนพัฒนา)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ตั้งค่า header เป็น JSON
header('Content-Type: application/json; charset=utf-8');

// Include ไฟล์เชื่อมต่อฐานข้อมูล
if (!@include 'config.php') {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ไม่สามารถโหลดไฟล์ config.php ได้']);
    exit;
}

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!isset($conn) || $conn->connect_error) {
    http_response_code(500);
    $errorMessage = isset($conn) ? $conn->connect_error : 'ตัวแปร $conn ไม่ได้ถูกกำหนดใน config.php';
    error_log("Database Connection Error: " . $errorMessage);
    echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล']);
    exit;
}

// *** เพิ่ม: รับ user_id จาก GET parameter (ถ้ามี) ***
$filter_user_id = isset($_GET['user_id']) ? filter_var($_GET['user_id'], FILTER_VALIDATE_INT) : null;

$requests = [];
$response = [];
$params = []; // Array สำหรับเก็บ parameters ที่จะ bind
$types = ""; // String สำหรับเก็บ type ของ parameters

// --- เตรียมคำสั่ง SQL ---
$sql = "SELECT
            br.id,
            br.organization_id,
            u.firstname AS organization_name,
            br.plastic_type,
            br.province,
            br.district,
            br.sub_district,
            br.min_kg,
            br.price_per_kg,
            br.destination_info,
            br.created_at
        FROM
            buy_requests br
        INNER JOIN
            users u ON br.organization_id = u.id";

// *** เพิ่ม: เงื่อนไข WHERE ถ้ามีการกรอง user_id ***
if ($filter_user_id !== null && $filter_user_id !== false) {
    $sql .= " WHERE br.organization_id = ?";
    $params[] = $filter_user_id;
    $types .= "i"; // i for integer
}

// *** แก้ไข: เรียงตามประเภทพลาสติกก่อน แล้วค่อยตามวันที่ ***
$sql .= " ORDER BY br.plastic_type ASC, br.created_at DESC";

// --- เตรียมและ Execute Statement ---
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    // กรณี Prepare ล้มเหลว
    http_response_code(500);
    $errorMessage = 'เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: ' . $conn->error;
    error_log("SQL Prepare Error: " . $conn->error . " | SQL: " . $sql);
    $response = ['status' => 'error', 'message' => $errorMessage];
} else {
    // Bind parameters ถ้ามี
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params); // ใช้ splat operator (...) สำหรับ bind_param
    }

    // Execute
    if (!$stmt->execute()) {
        // กรณี Execute ล้มเหลว
        http_response_code(500);
        $errorMessage = 'เกิดข้อผิดพลาดในการดึงข้อมูล: ' . $stmt->error;
        error_log("SQL Execute Error: " . $stmt->error . " | SQL: " . $sql);
        $response = ['status' => 'error', 'message' => $errorMessage];
    } else {
        // กรณี Execute สำเร็จ
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // มีข้อมูล
            while($row = $result->fetch_assoc()) {
                // สร้าง key 'location'
                $row['location'] = trim($row['sub_district'] . ', ' . $row['district'] . ', ' . $row['province'], ', ');

                // จัดรูปแบบวันที่
                if (isset($row['created_at']) && $row['created_at'] !== null) {
                     try {
                        $date = new DateTime($row['created_at']);
                        $row['created_at_formatted'] = $date->format('d/m/Y H:i');
                     } catch (Exception $e) {
                        $row['created_at_formatted'] = 'Invalid Date';
                        error_log("Date formatting error for id " . ($row['id'] ?? 'N/A') . ": " . $e->getMessage());
                     }
                } else {
                    $row['created_at_formatted'] = 'N/A';
                }
                $requests[] = $row;
            }
            $response = ['status' => 'success', 'data' => $requests];
        } else {
            // ไม่มีข้อมูล
            $response = ['status' => 'success', 'data' => [], 'message' => 'ไม่พบรายการรับซื้อที่ตรงตามเงื่อนไข'];
        }
        // $result->free(); // ไม่จำเป็นต้อง free result ที่ได้จาก get_result()
    }
    // ปิด statement
    $stmt->close();
}

// --- ปิดการเชื่อมต่อ ---
$conn->close();

// --- ส่งข้อมูลกลับเป็น JSON ---
if (json_encode($response) === false) {
    http_response_code(500);
    error_log("JSON Encode Error: " . json_last_error_msg());
    echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการสร้างข้อมูล JSON: ' . json_last_error_msg()]);
} else {
    echo json_encode($response);
}
exit;
?>
