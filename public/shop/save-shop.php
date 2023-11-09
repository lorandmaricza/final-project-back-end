<?php
include '../database.php';
include '../cors.php';
global $conn;

$data = json_decode(file_get_contents('php://input'), true);
$lat = $data['lat'];
$lng = $data['lng'];
$address = $data['address'];
$user_id = $data['userId'];
$categories_array = $data['checkedCategories'];
$shop_name = $data['shopName'];

$sql = 'INSERT INTO shops (location, user_id, address, name) VALUES (POINT(?, ?), ?, ?, ?)';
$stmt = $conn->prepare($sql);
$stmt->bind_param('ddiss', $lat, $lng, $user_id, $address, $shop_name);

try {
    $stmt->execute();
    $shop_id = $conn->insert_id;
    foreach ($categories_array as $category_id) {
        $sql = 'INSERT INTO shops_categories (shop_id, category_id) VALUES (?, ?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $shop_id, $category_id);
        $stmt->execute();
    }
    echo json_encode(['status' => 'success', 'message' => 'Shop successfully added.']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Something went wrong with the shop addition.']);
}
exit();

