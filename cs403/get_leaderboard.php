<?php
header('Content-Type: application/json; charset=utf-8'); // ระบุ charset
require 'config.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

$response = ['status' => 'error', 'message' => 'An unknown error occurred'];

try {
    // --- ตรวจสอบการเชื่อมต่อฐานข้อมูล ---
    // เช็คว่า $conn ถูกสร้างขึ้นและไม่มีข้อผิดพลาดในการเชื่อมต่อหรือไม่
    if (!isset($conn) || $conn->connect_error) {
        $error_message = isset($conn) ? "Database Connection Error: " . $conn->connect_error : "Database connection object not found in config.php.";
        throw new Exception($error_message);
    }

    // ดึงข้อมูลผู้ใช้ทั่วไป (user) ที่มีคะแนนมากกว่า 0 เรียงจากมากไปน้อย
    // เลือกเฉพาะ id, firstname, lastname, total_points
    $sql = "SELECT id, firstname, lastname, total_points
            FROM users
            WHERE user_type = 'user'
            ORDER BY total_points DESC
            LIMIT 100"; // Optional: จำกัดจำนวน เช่น 100 อันดับแรก

    $stmt = $conn->prepare($sql); // ใช้ $conn จาก config.php

    if (!$stmt) {
        throw new Exception("SQL prepare failed: " . $conn->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("SQL execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $leaderboardData = [];
    while ($row = $result->fetch_assoc()) {
        $leaderboardData[] = $row;
    }
    $stmt->close();

    $response['status'] = 'success';
    $response['data'] = $leaderboardData;
    unset($response['message']); // ลบ message ถ้าสำเร็จ

} catch (Exception $e) {
    $response['message'] = "Error fetching leaderboard: " . $e->getMessage();
    error_log("Error in get_leaderboard.php: " . $e->getMessage());
    http_response_code(500); // ตั้งค่าสถานะ HTTP เป็น 500 สำหรับข้อผิดพลาดฝั่ง Server
} finally {
    // ปิดการเชื่อมต่อเฉพาะเมื่อ $conn ถูกสร้างและเชื่อมต่อสำเร็จ
    if (isset($conn) && !$conn->connect_error) {
        $conn->close();
    }
}
echo json_encode($response);
?>