<?php
include '../database.php';
include '../cors.php';
global $conn;

$data = json_decode(file_get_contents('php://input'), true);
$category_name = $data['categoryName'];

$check_sql = "SELECT * FROM categories WHERE name = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param('s', $category_name);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Category already exists']);
    exit();
}

$insert_sql = "INSERT INTO categories (name) VALUES (?)";
$insert_stmt = $conn->prepare($insert_sql);
$insert_stmt->bind_param('s', $category_name);

try {
    $insert_stmt->execute();
    echo json_encode(['status' => 'success', 'message' => 'Category successfully added']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Something went wrong with the category addition']);
}
exit();
