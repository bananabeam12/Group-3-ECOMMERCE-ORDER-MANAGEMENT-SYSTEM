<?php
// Database connection configuration
$host = "127.0.0.1"; 
$port = "3307";
$user = "root";
$password = ""; 
$database = "e_commerce_db";

$conn = mysqli_connect($host, $user, $password, $database, $port);

if (!$conn) {
    header('Content-Type: application/json');
    echo json_encode([
        "status" => false, 
        "message" => "Connection failed: " . mysqli_connect_error()
    ]);
    exit;
}

mysqli_set_charset($conn, "utf8mb4");
?>