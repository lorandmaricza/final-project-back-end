<?php
session_start();
error_reporting(E_ALL);

$DATABASE_HOST = '35.194.48.24';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'root';
$DATABASE_NAME = 'project-db';

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

//$database = new PDO("mysql:host=".getenv('DATABASE_HOST').";dbname=".getenv('DATABASE_NAME'), getenv('DATABASE_USER'), getenv('DATABASE_PASS'));

