<?php
// config.php
$host = "127.0.0.1"; 
$port = "3306"; // Using your custom port
$user = "root";
$password = ""; 
$database = "e_commerce_db";

// Leader's Syntax: mysqli_connect
$conn = mysqli_connect($host, $user, $password, $database, $port);

// Check connection
if (!$conn) {
    header('Content-Type: application/json');
    echo json_encode([
        "status" => false, 
        "message" => "Connection failed: " . mysqli_connect_error()
    ]);
    exit;
}

// Set charset to avoid special character issues (like the ₱ symbol)
mysqli_set_charset($conn, "utf8mb4");
?>