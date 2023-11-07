<?php
include '../database.php';
include '../cors.php';
global $conn;

$data = json_decode(file_get_contents('php://input'), true);
$shop_name = $data['shopName'];
$shop_id = $data['shopId'];

$sql = "UPDATE shops SET name = ? WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $shop_name, $shop_id);

try {
    $stmt->execute();
    echo json_encode(['status' => 'success', 'message' => 'Shop location successfully updated']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Something went wrong with the shop location update.']);
}
exit();
