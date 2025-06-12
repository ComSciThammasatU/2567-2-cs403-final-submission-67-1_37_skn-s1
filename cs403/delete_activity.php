<?php
    // Include database connection details
    require_once 'config.php';

    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
        exit;
    }

    $loggedInUserId = $_SESSION['user_id'];
    
    // Check if serviceId is set
    if (isset($_POST['serviceId'])) {
        $serviceId = $_POST['serviceId'];

        // Check if the post belongs to the user
        $stmt = $conn->prepare("SELECT user_id FROM services WHERE id = ?");
        $stmt->bind_param("i", $serviceId);
        $stmt->execute();
        $stmt->bind_result($serviceUserId);
        $stmt->fetch();
        $stmt->close();

        if ($loggedInUserId != $serviceUserId) {
            echo json_encode(['status' => 'error', 'message' => 'You are not authorized to delete this post.']);
            exit;
        }

        // Delete the post
        $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
        $stmt->bind_param("i", $serviceId);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Activity deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error deleting activity.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'serviceId is not set.']);
    }

    $conn->close();
?>
