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

// Define admin credentials
$adminEmail = "admin@example.com";
$adminPassword = "admin123";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email and password are provided
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        // Validate email and password
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Check if the credentials match the admin credentials
        if ($email === $adminEmail && $password === $adminPassword) {
            // Set session variable for admin
            $_SESSION["user_email"] = $email;
            // Redirect to admin.html
            header("Location: admin.html");
            exit();
        } else {
            // Query to check email and password against database
            $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                // Valid credentials, set email as session variable
                $_SESSION["email"] = $email; // Change session variable name to "user_email"
                
                // Redirect to index.php
                header("Location: index2.php");
                exit();
            } else {
                // Invalid credentials, display error message or handle as needed
                echo "Invalid email or password.";
            }
        }
    } else {
        // Email or password not provided, display error message or handle as needed
        echo "Email and password are required.";
    }
}

$conn->close();
?>
