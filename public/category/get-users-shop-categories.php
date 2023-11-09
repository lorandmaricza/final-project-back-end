<?php
include '../database.php';
include '../cors.php';

global $conn;

$data = json_decode(file_get_contents('php://input'), true);
$shop_id = $data['shopId'];

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

echo json_encode(['categories' => $categories]);
