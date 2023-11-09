<?php
include '../database.php';
include '../cors.php';
global $conn;

$data = json_decode(file_get_contents('php://input'), true);
$shop_id = $data['shopId'];

$sql = "DELETE FROM shops WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $shop_id);

try {
    $stmt->execute();
    echo json_encode(['status' => 'success', 'message' => 'Shop successfully deleted']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e]);
}
exit();