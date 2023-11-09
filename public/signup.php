<?php
include 'database.php';
include 'cors.php';
global $conn;

$data = json_decode(file_get_contents('php://input'), true);

$first_name = $data['firstName'];
$last_name = $data['lastName'];
$email = $data['email'];
$password = $data['password'];
$confirm_password = $data['confirmPassword'];
$role = $data['role'] == 'consumer' ? 1 : 2;

$query2 = 'INSERT INTO users (first_name, last_name, email, password, role_id) VALUES (?,?,?,?,?)';

if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit();
}

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

$password = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare($query2);
$stmt->bind_param('sssss', $first_name, $last_name, $email, $password, $role);
if($stmt->execute()) {
    $userId = $conn->insert_id;
    $user['id'] = $userId;
    session_start();
    $_SESSION['user'] = $user;
    echo json_encode(['status' => 'success', 'message' => 'User created successfully']);
}

$stmt->close();
$conn->close();


