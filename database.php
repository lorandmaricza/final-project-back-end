<?php
session_start();
error_reporting(E_ALL);

$database = new PDO("mysql:host=".getenv('DATABASE_HOST').";dbname=".getenv('DATABASE_NAME'), getenv('DATABASE_USER'), getenv('DATABASE_PASS'));

