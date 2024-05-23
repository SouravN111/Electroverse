<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "project1";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted and the user is logged in
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["email"])) {
    // Retrieve data from the form
    $productName = $_POST["productName"];
    $productPrice = $_POST["productPrice"];
    $productImage = $_POST["productImage"];
    $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 1; // Default quantity is 1
    
    // Retrieve the user's email from the session
    $userEmail = $_SESSION["email"];

    // Check if the product already exists in the cart
    $sql_check = "SELECT * FROM cart WHERE userEmail = ? AND productName = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $userEmail, $productName);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // Product already exists in the cart, update its quantity
        $sql_update = "UPDATE cart SET quantity = quantity + ? WHERE userEmail = ? AND productName = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("iss", $quantity, $userEmail, $productName);
        
        if ($stmt_update->execute()) {
            echo "Product quantity updated in the cart.";
            header("Location:cart.php");
        } else {
            echo "Error updating product quantity: " . $conn->error;
        }
        
        $stmt_update->close();
    } else {
        // Product does not exist in the cart, insert a new record
        $sql_insert = "INSERT INTO cart (userEmail, productName, productPrice, productImage, quantity) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssssi", $userEmail, $productName, $productPrice, $productImage, $quantity);
        
        if ($stmt_insert->execute()) {
            echo "Product added to cart successfully.";
        } else {
            echo "Error adding product to cart: " . $conn->error;
        }
        
        $stmt_insert->close();
    }
    
    $stmt_check->close();
} else {
    // If the form is not submitted or the user is not logged in
    echo "Invalid request method or user not logged in.";
}

// Close the database connection
$conn->close();

?>
