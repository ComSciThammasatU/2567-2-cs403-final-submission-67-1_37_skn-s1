<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id']; // ดึง user_id จาก session
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];

    $sql = "INSERT INTO posts (user_id, title, content, category) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $userId, $title, $content, $category);

    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Post created successfully', 'post_id' => $conn->insert_id, 'category' => $category]; // เพิ่ม category
    } else {
        $response = ['status' => 'error', 'message' => 'Error creating post: ' . $conn->error];
    }

    $stmt->close();
    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
