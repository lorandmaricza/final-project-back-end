<?php
include '../database.php';
include '../cors.php';
global $conn;

$data = json_decode(file_get_contents('php://input'), true);
$category_id = $data['category_id'];

$sql = "DELETE FROM categories WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $category_id);
try {
    $stmt->execute();
    echo json_encode(['status' => 'success', 'message' => 'Category successfully deleted']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e]);
}
exit();
