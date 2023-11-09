<?php
include '../database.php';
include '../cors.php';
global $conn;

$data = json_decode(file_get_contents('php://input'), true);

$user_id = $data['userId'];

$sql = "SELECT *, ST_X(location) as lat, ST_Y(location) as lng FROM shops WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo json_encode(['status' => 'error', 'message' => "Failed to fetch data from the database: " . $conn->error]);
    exit;
}

$shops = array();
while ($row = $result->fetch_assoc()) {
    $shop = array(
        'id' => $row['id'],
        'lat' => $row['lat'],
        'lng' => $row['lng'],
        'address' => $row['address'],
        'name' => $row['name'],
        'user_id' => $row['user_id']
    );
    $shops[] = $shop;
}

echo json_encode(['status' => 'success', 'shops' => $shops]);
