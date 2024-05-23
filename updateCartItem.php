<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["email"])) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "project1";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    header("HTTP/1.1 500 Internal Server Error");
    exit();
}

// Get product ID and quantity from POST data
$productId = isset($_POST["productId"]) ? intval($_POST["productId"]) : null;
$quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : null;

// Validate product ID and quantity
if ($productId === null || $quantity === null || $quantity <= 0) {
    header("HTTP/1.1 400 Bad Request");
    exit();
}

// Update quantity in the database
$userEmail = $_SESSION["email"];
$stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND userEmail = ?");
$stmt->bind_param("iis", $quantity, $productId, $userEmail);

if ($stmt->execute()) {
    // Calculate total and item total after updating quantity
    $total = calculateTotal($conn, $userEmail); // Function to calculate total
    $itemTotal = calculateItemTotal($conn, $productId, $userEmail); // Function to calculate item total for the given productId
    
    // Return success response with updated total and item total
    header("Content-Type: application/json");
    echo json_encode(["success" => true, "total" => $total, "itemTotal" => $itemTotal]);
} else {
    // Failed to update quantity
    header("HTTP/1.1 500 Internal Server Error");
}

$stmt->close();
$conn->close();

// Function to calculate total
function calculateTotal($conn, $userEmail) {
    $query = "SELECT SUM(productPrice * quantity) AS total FROM cart WHERE userEmail = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row["total"] ?? 0;
}

// Function to calculate item total for the given productId
function calculateItemTotal($conn, $productId, $userEmail) {
    $query = "SELECT productPrice * quantity AS itemTotal FROM cart WHERE id = ? AND userEmail = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $productId, $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row["itemTotal"] ?? 0;
}
?>
