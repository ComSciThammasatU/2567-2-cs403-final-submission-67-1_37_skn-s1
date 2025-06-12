<?php
// Start session at the very beginning
session_start();
// Set Content-Type header for JSON responses
header('Content-Type: application/json');

include 'config.php'; // เชื่อมต่อกับฐานข้อมูล

// เช็คว่าเป็น method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจาก JavaScript และตัดช่องว่าง
    $firstname = trim($_POST['firstname']);
    $lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : ''; // Handle optional lastname
    $username = trim($_POST['username']);
    $password = $_POST['password']; // รับรหัสผ่านแบบเดิม
    $email = trim($_POST['email']);
    $userType = $_POST['user_type'];

    // ตรวจสอบว่าข้อมูลครบถ้วน (lastname is now optional)
    if (empty($firstname) || empty($username) || empty($password) || empty($email) || empty($userType)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน.']);
        if (isset($conn)) $conn->close();
        exit;
    }
    // ตรวจสอบความถูกต้องของ Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'รูปแบบ Email ไม่ถูกต้อง.']);
        if (isset($conn)) $conn->close();
        exit;
    }
     // ตรวจสอบ password
     if (strlen($password) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร.']);
        if (isset($conn)) $conn->close();
        exit;
    }
     // เข้ารหัส password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // ตรวจสอบว่า username หรือ email ซ้ำหรือไม่
    $stmt_check = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
    if ($stmt_check === false) {
        error_log("Prepare failed (check user): " . $conn->error);
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการตรวจสอบข้อมูลผู้ใช้.']);
        if (isset($conn)) $conn->close();
        exit;
    }
    $stmt_check->bind_param("ss", $username, $email);
    if (!$stmt_check->execute()) {
        error_log("Execute failed (check user): " . $stmt_check->error);
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการดำเนินการตรวจสอบผู้ใช้.']);
        $stmt_check->close();
        if (isset($conn)) $conn->close();
        exit;
    }
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Username หรือ Email นี้มีผู้ใช้งานแล้ว.']);
        $stmt_check->close();
        if (isset($conn)) $conn->close();
        exit;
    } else {
      $stmt_check->close(); // Close the check statement

      // กำหนดค่าเริ่มต้นสำหรับคอลัมน์ใหม่
      $default_organization_id = null; // หรือค่าเริ่มต้นอื่นๆ ที่เหมาะสม ถ้า user_type เป็น organization อาจจะต้องมี logic เพิ่ม
      $default_total_points = 0;

      // เพิ่มผู้ใช้งานใหม่
      // เพิ่ม organization_id และ total_points ในคำสั่ง INSERT
      $stmt_insert = $conn->prepare("INSERT INTO users (firstname, lastname, username, password, email, user_type, organization_id, total_points) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
      if ($stmt_insert === false) {
          error_log("Prepare failed (insert user): " . $conn->error);
          echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการเตรียมข้อมูลลงทะเบียน.']);
          if (isset($conn)) $conn->close();
          exit;
      }
      // ปรับ bind_param type เป็น "ssssssii" (s=string, i=integer) และเพิ่มตัวแปร
      $stmt_insert->bind_param("ssssssii", $firstname, $lastname, $username, $passwordHash, $email, $userType, $default_organization_id, $default_total_points);

      if ($stmt_insert->execute()) {
          // ดึงข้อมูล user_id ที่เพิ่งลงทะเบียน
          $user_id = $conn->insert_id;
          // Session already started at the top
          $_SESSION['user_id'] = $user_id;
          // เพิ่ม user_type เข้า session ด้วย (ถ้าต้องการ)
          $_SESSION['user_type'] = $userType;

          echo json_encode(['status' => 'success', 'message' => 'ลงทะเบียนสำเร็จ.']);
      } else {
          // Log the error to a file (for debugging)
          error_log("Database error (insert user): " . $stmt_insert->error . " | SQL State: " . $stmt_insert->sqlstate);
          echo json_encode(['status' => 'error', 'message' => 'ลงทะเบียนไม่สำเร็จ. กรุณาลองใหม่อีกครั้ง']);
      }
      $stmt_insert->close();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

if (isset($conn)) {
    $conn->close();
}
// Ensure no further output after this point if an exit wasn't called earlier.
// All paths should ideally call exit after json_encode.
?>
