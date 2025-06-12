<?php
// get_services.php
header('Content-Type: application/json');
include 'config.php';

// SQL query to fetch data from the 'services' table
$sql = "SELECT id, user_id, title, description, imageUrl, timestamp FROM services";
$result = $conn->query($sql);

$services = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Get files for each service
        $serviceId = $row['id'];
        $fileSql = "SELECT file_path FROM service_files WHERE service_id = ?";
        $fileStmt = $conn->prepare($fileSql);
        $fileStmt->bind_param("i", $serviceId);
        $fileStmt->execute();
        $fileResult = $fileStmt->get_result();
        $files = [];
        while ($fileRow = $fileResult->fetch_assoc()) {
            $files[] = $fileRow['file_path'];
        }
        $row['files'] = $files;
        $services[] = $row;
        $fileStmt->close();
    }
}

echo json_encode($services);

$conn->close();
?>
