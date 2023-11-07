<?php
include 'database.php';
include 'cors.php';
global $conn;

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];
$first_name = $data['first_name'];
$last_name = $data['last_name'];
$email = $data['email'];
// is it ok?
$previous_password = $data['previous_password'];
$password = $data['password'];
$confirm_password = $data['confirm_password'];

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


