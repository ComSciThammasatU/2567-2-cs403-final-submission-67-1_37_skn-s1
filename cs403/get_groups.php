<?php
    header('Content-Type: application/json');
    include 'config.php';

    $sql = "SELECT group_id, group_name, group_detail, authorized_user_ids, created_at FROM Xanthophyceae_Coagulum_Repository ORDER BY created_at DESC";
    $result = $conn->query($sql);

    $groups = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $groups[] = $row;
        }
    }

    echo json_encode($groups);
    $conn->close();
    ?>
