<?php
session_start();
error_reporting(E_ALL);

$DATABASE_HOST = '127.0.0.1';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'root';
$DATABASE_NAME = 'project_db';

$conn = mysqli_connect(
    $DATABASE_HOST,
    $DATABASE_USER,
    $DATABASE_PASS,
    $DATABASE_NAME
);

if (!$conn) {
    die('Could not connect to the database: ' .
        mysqli_error($conn));
}


