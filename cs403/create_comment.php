<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postId = $_POST['post_id'];
    $userId = $_SESSION['user_id']; // ดึง user_id จาก session
    $commentText = $_POST['comment_text'];

    $sql = "INSERT INTO comments (post_id, user_id, comment_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $postId, $userId, $commentText);

    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Comment created successfully'];
    } else {
        $response = ['status' => 'error', 'message' => 'Error creating comment: ' . $conn->error];
    }

    $stmt->close();
    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
