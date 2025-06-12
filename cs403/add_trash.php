<?php
header('Content-Type: application/json');
include 'config.php';

$groupId = $_POST['group_id'];
$weight = $_POST['weight'];
$trashType = $_POST['trash_type'];

// ตรวจสอบว่าข้อมูลที่ส่งมาไม่ว่าง
if (empty($groupId) || empty($weight) || empty($trashType)) {
    echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกข้อมูลให้ครบ']);
    exit;
}

$sql = "INSERT INTO Perissodactyla_Detritus_Manifest (group_id, weight, trash_type) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("iis", $groupId, $weight, $trashType); // แก้ไขตรงนี้
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'เพิ่มข้อมูลขยะสำเร็จ']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการเพิ่มข้อมูลขยะ: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: ' . $conn->error]);
}

$conn->close();
?>
