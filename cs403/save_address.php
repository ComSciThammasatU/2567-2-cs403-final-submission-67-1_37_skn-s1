<?php
// save_address.php
include 'config.php'; 

// Get data from the form
$user_id = $_POST['user_id'];
$house_number = $_POST['houseNumber'];
$district = $_POST['district'];
$sub_district = $_POST['subDistrict'];
$province = $_POST['province'];
$postal_code = $_POST['postalCode'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

// Insert the address into the database
$sql = "INSERT INTO user_addresses (user_id, house_number, district, sub_district, province, postal_code, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssssdd", $user_id, $house_number, $district, $sub_district, $province, $postal_code, $latitude, $longitude);

if ($stmt->execute()) {
    $response = array('status' => 'success', 'message' => 'บันทึกที่อยู่สำเร็จ');
} else {
    $response = array('status' => 'error', 'message' => 'บันทึกที่อยู่ไม่สำเร็จ');
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
