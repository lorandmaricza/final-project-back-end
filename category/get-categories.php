<?php
include '../database.php';
include '../cors.php';
global $conn;

$sql = 'SELECT * FROM categories';
$categories = $conn->query($sql)->fetch_all();

echo json_encode(['status' => 'success', 'categories' => $categories]);