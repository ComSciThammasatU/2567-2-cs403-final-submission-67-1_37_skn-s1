<?php
include 'config.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

header('Content-Type: application/json');

// ดึงข้อมูลเฉพาะ title จากตาราง services (หรือตารางที่คุณใช้เก็บข้อมูลกิจกรรม)
// เรียงตาม ID ล่าสุด หรือตาม timestamp ถ้ามี
$sql = "SELECT id, title FROM services ORDER BY id DESC"; // หรือ ORDER BY timestamp DESC
$result = $conn->query($sql);

$titles = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // เก็บทั้ง id และ title ถ้าต้องการใช้ id เป็น value ใน option
        $titles[] = ['id' => $row['id'], 'title' => $row['title']];
        // หรือเก็บแค่ title ถ้า value ไม่สำคัญ
        // $titles[] = $row['title'];
    }
} else {
    // จัดการกรณีไม่มีข้อมูลหรือเกิดข้อผิดพลาด
    // อาจจะส่ง array ว่าง หรือ error message
    // error_log("Error fetching activity titles: " . $conn->error);
}

$conn->close();

// ส่งข้อมูลกลับเป็น JSON
echo json_encode($titles);
?>
