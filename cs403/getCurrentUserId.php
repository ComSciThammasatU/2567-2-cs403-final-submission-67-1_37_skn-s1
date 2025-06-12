<?php
session_start();

// Check if the user is logged in (you might have your own authentication method)
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    echo json_encode(['userId' => $userId]);
} else {
    echo json_encode(['userId' => null]);
}
?>
