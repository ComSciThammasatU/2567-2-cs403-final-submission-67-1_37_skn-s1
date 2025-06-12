<?php
session_start(); // เริ่ม session (ต้องอยู่บนสุดเสมอ)

include 'config.php'; // เชื่อมต่อกับฐานข้อมูล

// Get data from the form
$username = $_POST['username'];
$password = $_POST['password'];

// Fetch the user from the database
$sql = "SELECT id, password, user_type, firstname, lastname, email FROM users WHERE username = ?"; // เพิ่ม field ที่ต้องการส่งกลับ
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$response = []; // กำหนดค่าเริ่มต้นให้ $response

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        // --- บันทึก ID และ User Type ลง Session ---
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type']; // เอาคอมเมนต์ออกแล้ว
        // ---------------------------

        // ส่งข้อมูลกลับไปให้ Client (รวม user_type และข้อมูลอื่นๆ)
        $response = array(
            'status' => 'success',
            // ส่งข้อมูลผู้ใช้กลับไปเป็น array เดียว ไม่ใช่ array ซ้อน array
            // เพื่อให้ JavaScript เข้าถึงง่ายขึ้น (เช่น data.userData.user_id)
            'userData' => array(
                'user_id'   => $user['id'],
                'user_type' => $user['user_type'], // เอาคอมเมนต์ออกแล้ว
                'firstname' => $user['firstname'], // ส่งชื่อไปด้วย
                'lastname'  => $user['lastname'],  // ส่งนามสกุล (ถ้ามี)
                'email'     => $user['email']      // ส่งอีเมล (ถ้าต้องการ)
                // เพิ่ม field อื่นๆ ที่ต้องการส่งกลับได้ที่นี่
            )
        );
    } else {
        $response = array('status' => 'error', 'message' => 'รหัสผ่านไม่ถูกต้อง');
    }
} else {
    $response = array('status' => 'error', 'message' => 'ไม่พบผู้ใช้นี้');
}

$stmt->close(); // ปิด statement
$conn->close(); // ปิด connection

header('Content-Type: application/json');
echo json_encode($response);
?>
