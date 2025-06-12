<?php
header('Content-Type: application/json');
include 'config.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

// รับค่าและตรวจสอบ group_id
$groupId = filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT);

if ($groupId === false || $groupId === null || $groupId <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'กรุณากรอก group_id ให้ถูกต้อง']);
    exit;
}

$response = ['status' => 'success', 'trash_entries' => [], 'total_weight' => 0.00]; // โครงสร้าง response เริ่มต้น

try {
    // --- ดึงรายการขยะ ---
    $sql_entries = "SELECT * FROM Perissodactyla_Detritus_Manifest WHERE group_id = ? ORDER BY added_at DESC"; // เรียงตามวันที่เพิ่มล่าสุด
    $stmt_entries = $conn->prepare($sql_entries);
    if (!$stmt_entries) throw new Exception("Prepare entries query failed: " . $conn->error);

    $stmt_entries->bind_param("i", $groupId);
    $stmt_entries->execute();
    $result_entries = $stmt_entries->get_result();

    while ($row = $result_entries->fetch_assoc()) {
        // จัดรูปแบบวันที่ ถ้าต้องการ
        // $row['added_at_formatted'] = date('d/m/Y H:i', strtotime($row['added_at']));
        $response['trash_entries'][] = $row;
    }
    $stmt_entries->close();

    // --- คำนวณผลรวมน้ำหนัก ---
    $sql_total = "SELECT SUM(weight) AS total_weight FROM Perissodactyla_Detritus_Manifest WHERE group_id = ?";
    $stmt_total = $conn->prepare($sql_total);
    if (!$stmt_total) throw new Exception("Prepare total weight query failed: " . $conn->error);

    $stmt_total->bind_param("i", $groupId);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    if ($row_total = $result_total->fetch_assoc()) {
        // ใช้ number_format เพื่อให้มีทศนิยม 2 ตำแหน่งเสมอ, ถ้าเป็น null ให้เป็น 0.00
        $response['total_weight'] = number_format($row_total['total_weight'] ?? 0, 2, '.', '');
    }
    $stmt_total->close();

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = "Database error: " . $e->getMessage();
    error_log("Error in get_trash.php for group_id $groupId: " . $e->getMessage());
    http_response_code(500); // Internal Server Error
}

$conn->close();
echo json_encode($response);
?>
