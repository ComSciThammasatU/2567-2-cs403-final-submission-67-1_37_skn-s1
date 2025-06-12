<?php
include 'config.php';

$postId = $_GET['post_id'];

$sql = "SELECT comments.*, users.firstname FROM comments JOIN users ON comments.user_id = users.id WHERE post_id = ? ORDER BY timestamp ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
}

$stmt->close();
$conn->close();
header('Content-Type: application/json');
echo json_encode($comments);
?>
