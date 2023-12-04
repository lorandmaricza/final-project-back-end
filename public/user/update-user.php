<?php
include '../database.php';
include '../cors.php';
global $conn;

$data = json_decode(file_get_contents('php://input'), true);

if (
    $data === null ||
    !isset($data['id']) ||
    !isset($data['firstName']) ||
    !isset($data['lastName']) ||
    !isset($data['email']) ||
    !isset($data['previousPassword']) ||
    !isset($data['password']) ||
    !isset($data['confirmPassword'])) {
    die('{"status":"error", "message":"Missing input data"}');
}

$id = $data['id'];
$first_name = $data['firstName'];
$last_name = $data['lastName'];
$email = $data['email'];
$previous_password = $data['previousPassword'];
$password = $data['password'];
$confirm_password = $data['confirmPassword'];

$query = 'UPDATE users SET first_name = ?, last_name = ?, email = ?';
$query_password = ', password = ?';
$query_end = ' WHERE id = ?';

if (empty($first_name) || empty($last_name) || empty($email)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit();
}

if ($password !== '' && $password !== $confirm_password) {
    echo json_encode(['status' => 'error', 'message' => 'Passwords do not match']);
    exit();
}

if ($password !== '') {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query .= $query_password;
}

$query .= $query_end;

$stmt = $conn->prepare($query);

if ($password !== '') {
    $stmt->bind_param("ssssi", $first_name, $last_name, $email, $password, $id);
} else {
    $stmt->bind_param("sssi", $first_name, $last_name, $email, $id);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'User updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error updating user']);
}

$stmt->close();
$conn->close();


