<?php
include '../database.php';
include '../cors.php';
global $conn;

$data = json_decode(file_get_contents('php://input'), true);

if ($data === null ) {
    die('{"status":"error", "message":"Missing input data"}');
} else if (
    !isset($data['firstName']) ||
    !isset($data['lastName']) ||
    !isset($data['email']) ||
    !isset($data['password']) ||
    !isset($data['role'])) {
    die('{"status":"error", "message":"All fields are required"}');
}

$first_name = $data['firstName'];
$last_name = $data['lastName'];
$email = $data['email'];
$password = $data['password'];
$password_hashed = password_hash($password, PASSWORD_DEFAULT);
$confirm_password = $data['confirmPassword'];
$role = $data['role'] == 'consumer' ? 1 : 2;

if($password !== $confirm_password) {
    echo json_encode(['status' => 'error', 'message' => 'Passwords do not match']);
    exit();
}

$query1 = 'SELECT * FROM users WHERE email = ?';

$stmt = $conn->prepare($query1);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if($user) {
    echo json_encode([
            'status' => 'error',
            'message' => 'Email already exists'
        ]);
    exit();
}

$query2 = 'INSERT INTO users (first_name, last_name, email, password, role_id) VALUES (?,?,?,?,?)';

$stmt = $conn->prepare($query2);
$stmt->bind_param('sssss', $first_name, $last_name, $email, $password_hashed, $role);
if($stmt->execute()) {
    $userId = $conn->insert_id;
    $user['id'] = $userId;
    session_start();
    $_SESSION['user'] = $user;
    echo json_encode(['status' => 'success', 'message' => 'User created successfully']);
}

$stmt->close();
$conn->close();


