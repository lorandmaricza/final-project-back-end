<?php
include 'database.php';
include 'cors.php';

if (isset($_SESSION['user'])) {
    $userData = $_SESSION['user'];
    echo json_encode(['status' => 'logged_in', 'userData' => $userData, 'sessionId' => session_id()]);
} else {
    echo json_encode(['status' => 'not_logged_in', 'sessionId' => session_id()]);
}
exit();
