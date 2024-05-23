<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "project1";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION["email"])) {
    header("Location: login.html");
    exit();
}

// Fetch user's cart items
$userEmail = $_SESSION["email"];
$query = "DELETE FROM cart WHERE userEmail = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userEmail);

$response = array();

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $response["success"] = true;
        $response["message"] = "Cart cleared successfully";
    } else {
        $response["success"] = false;
        $response["message"] = "Cart is already empty";
    }
} else {
    $response["success"] = false;
    $response["message"] = "Failed to clear cart: " . $conn->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
