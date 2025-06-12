<?php
session_start();
include 'config.php';

$userId = $_GET['userId'];

$sql = "SELECT user_type, firstname FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($userData);
} else {
    echo json_encode(array('error' => 'User not found'));
}
$conn->close();
?>

