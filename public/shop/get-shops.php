<?php
include '../database.php';
include '../cors.php';
global $conn;

$sql = 'SELECT *, ST_X(location) as lat, ST_Y(location) as lng FROM shops';
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['status' => 'error', 'message' => "Failed to fetch data from the database: " . $conn->error]);
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
