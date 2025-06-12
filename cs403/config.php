<?php
$servername = "localhost";
$username = "root"; // โดยปกติ user name ของ XAMPP คือ root
$password = ""; // โดยปกติ password ของ XAMPP คือ "" (ว่างเปล่า)
$dbname = "cs403_database";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
