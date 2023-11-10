<?php
session_start();
error_reporting(E_ALL);

$conn = mysqli_connect(
    getenv("DATABASE_HOST"),
    getenv("DATABASE_USER"),
    getenv("DATABASE_PASS"),
    getenv("DATABASE_NAME"),
    getenv("DATABASE_PORT")
);

if (!$conn) {
    die('Could not connect to the database: ' .
        mysqli_error($conn));
}


