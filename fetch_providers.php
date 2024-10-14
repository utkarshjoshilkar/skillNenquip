<?php
$servername = "localhost";
$username = "root"; // use your MySQL username
$password = "Manthan@123890"; // leave it blank if there's no password
$dbname = "service-provider_db"; // replace with your database name
$port = 3307; // update with your port number

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get expertise from the request
$expertiseList = isset($_GET['services']) ? $_GET['services'] : '';
$expertiseArray = explode(',', $expertiseList);
$expertiseArray = array_map('trim', $expertiseArray);

// Prepare SQL statement
$placeholders = implode(',', array_fill(0, count($expertiseArray), '?'));
$sql = "SELECT name, email, phone, expertise FROM service_providers WHERE expertise IN ($placeholders)";

// Prepare and bind
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param(str_repeat('s', count($expertiseArray)), ...$expertiseArray);
    
    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    $providers = [];
    
    // Fetch all providers
    while ($row = $result->fetch_assoc()) {
        $providers[] = $row;
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($providers);
    
    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['error' => 'Error preparing statement.']);
}

// Close the connection
$conn->close();
?>
