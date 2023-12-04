<?php
include '../database.php';
include '../cors.php';
global $conn;

$data = json_decode(file_get_contents('php://input'), true);

if ($data === null || !isset($data['shopId'])) {
    die('{"status":"error", "message":"Missing input data"}');
}

$shop_id = $data['shopId'];

$sql = "SELECT address, name FROM shops WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $shop_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo json_encode(['status' => 'error', 'message' => "Failed to fetch data from the database: " . $conn->error]);
    exit;
}

$shop = $result->fetch_assoc();

$sql = 'SELECT categories.id, categories.name
FROM categories 
INNER JOIN shops_categories ON categories.id = shops_categories.category_id 
WHERE shops_categories.shop_id = ?';

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $shop_id);

$categories = array();

if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

echo json_encode(['status' => 'success', 'shop' => $shop, 'categories' => $categories]);
