<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "project1";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the category from the AJAX request
$category = $_GET["category"];

// Prepare SQL query to fetch products based on category
$query = "SELECT * FROM products WHERE category = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("s", $category);

// Execute the query and fetch results
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

$result = $stmt->get_result();

// Fetch products from the result set
$products = array();
while ($row = $result->fetch_assoc()) {
    // Process each row and add to the products array
    $products[] = $row;
}

// Close prepared statement
$stmt->close();

// Send products as JSON response
header("Content-Type: application/json");
echo json_encode($products);

?>
