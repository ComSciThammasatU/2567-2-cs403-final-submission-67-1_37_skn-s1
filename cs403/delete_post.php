<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postId = $_POST['postId'];
    $userId = $_SESSION['user_id'];

    // ตรวจสอบว่าผู้ใช้ปัจจุบันเป็นผู้โพสต์หรือไม่
    $sql_check = "SELECT user_id FROM posts WHERE post_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $postId);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $row_check = $result_check->fetch_assoc();
        if ($row_check['user_id'] == $userId) {
            // ลบข้อมูลจากตาราง comments ก่อน
            $sql_delete_comments = "DELETE FROM comments WHERE post_id = ?";
            $stmt_delete_comments = $conn->prepare($sql_delete_comments);
            $stmt_delete_comments->bind_param("i", $postId);
            $stmt_delete_comments->execute();
            $stmt_delete_comments->close();

            // ลบข้อมูลจากตาราง posts
            $sql_delete_post = "DELETE FROM posts WHERE post_id = ?";
            $stmt_delete_post = $conn->prepare($sql_delete_post);
            $stmt_delete_post->bind_param("i", $postId);

            if ($stmt_delete_post->execute()) {
                $response = ['status' => 'success', 'message' => 'Post deleted successfully'];
            } else {
                $response = ['status' => 'error', 'message' => 'Error deleting post: ' . $conn->error];
            }

            $stmt_delete_post->close();
        } else {
            $response = ['status' => 'error', 'message' => 'You are not authorized to delete this post'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Post not found'];
    }

    $stmt_check->close();
    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
