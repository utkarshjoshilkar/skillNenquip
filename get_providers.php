<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "Manthan@123890"; // Change this if you have set a password
$database = "service_provider_db"; // Ensure this matches your database name
$port = 3307;
$conn = new mysqli($servername, $username, $password, $database, 3307); // Use port 3307

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get services from the URL
$services = json_decode($_GET['services'], true);
$placeholders = implode(',', array_fill(0, count($services), '?'));
$sql = "SELECT * FROM service_providers WHERE expertise IN ($placeholders)";

// Prepare statement
$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($services)), ...$services);
$stmt->execute();
$result = $stmt->get_result();

$providers = [];
while ($row = $result->fetch_assoc()) {
    $providers[] = $row;
}

$stmt->close();
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($providers);
