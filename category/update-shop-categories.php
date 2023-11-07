<?php
include '../database.php';
include '../cors.php';
global $conn;

$data = json_decode(file_get_contents('php://input'), true);
$shopId = $data['shopId'];
$checkedCategories = $data['checkedCategories'];

$sql = "DELETE FROM shops_categories WHERE shop_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $shopId);
$stmt->execute();

$sql = "INSERT INTO shops_categories (shop_id, category_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $shopId, $categoryId);

foreach ($checkedCategories as $categoryId) {
    $stmt->execute();
}

$response = array(['status' => 'success', 'message' => 'Shop categories successfully updated.']);
echo json_encode($response);
