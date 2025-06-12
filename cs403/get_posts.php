<?php
include 'config.php';

$sql = "SELECT posts.*, users.firstname FROM posts JOIN users ON posts.user_id = users.id ORDER BY timestamp DESC";
$result = $conn->query($sql);

$posts = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // นับจำนวนคอมเมนต์ของแต่ละกระทู้
        $post_id = $row['post_id'];
        $sql_count = "SELECT COUNT(*) AS comment_count FROM comments WHERE post_id = ?";
        $stmt_count = $conn->prepare($sql_count);
        $stmt_count->bind_param("i", $post_id);
        $stmt_count->execute();
        $result_count = $stmt_count->get_result();
        $row_count = $result_count->fetch_assoc();
        $comment_count = $row_count['comment_count'];

        // เพิ่มจำนวนคอมเมนต์ลงในข้อมูลกระทู้
        $row['comment_count'] = $comment_count;
        $posts[] = $row;
        $stmt_count->close();
    }
}

$conn->close();
header('Content-Type: application/json');
// ตรวจสอบว่าส่งข้อมูลได้หรือไม่
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'JSON encoding failed', 'details' => json_last_error_msg()]);
} else {
    echo json_encode($posts);
}
?>
