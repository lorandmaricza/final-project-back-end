<?php
include 'database.php';
include 'cors.php';

session_unset();
session_destroy();

echo json_encode(array("status" => "success", "message" => "Successfully logged out"));
exit();
