<?php

include '../database.php';
include '../cors.php';
global $conn;

$data = json_decode(file_get_contents('php://input'), true);

if (
    $data === null ||
    !isset($data['distance']) ||
    !isset($data['categories']) ||
    !isset($data['lat']) ||
    !isset($data['lng'])) {
    die('{"ok": false}');
}

$distance = $data['distance'];
$categories = $data['categories'];
$lat = $data['lat'];
$lng = $data['lng'];
$distance *= 1000;
$shops = array();

$lat = $conn->escape_string($lat);
$lng = $conn->escape_string($lng);
$distance = $conn->escape_string($distance);
$categories_str = implode(",", $categories);

$sql = "SELECT s.id, 
       s.address, 
       s.name, 
       s.user_id, 
       ST_X(s.location) as lat, 
       ST_Y(s.location) as lng 
        FROM shops AS s 
        JOIN shops_categories AS sc ON s.id = sc.shop_id
        WHERE sc.category_id IN ($categories_str)
        AND (
            ST_Distance_Sphere(s.location, Point($lat, $lng))
            ) <= $distance
        GROUP BY s.id";

$result = $conn->query($sql);

if ($result === false) {
    die('{"ok": false}');
}

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

