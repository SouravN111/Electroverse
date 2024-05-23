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

// Initialize alert message
$alertMessage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email, password, and confirmPassword are provided
    if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirmPassword"])) {
        // Validate email and password
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirmPassword"];

        // Check if passwords match
        if ($password === $confirmPassword) {
            // Check if email is already registered
            $checkQuery = "SELECT * FROM users WHERE email='$email'";
            $result = $conn->query($checkQuery);
            if ($result->num_rows > 0) {
                // Email already registered
                $alertMessage = "This email is already registered. Please use a different email.";
            } else {
                // Insert user into the database
                $insertQuery = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
                if ($conn->query($insertQuery) === TRUE) {
                    // Set success message
                    $alertMessage = "Signup successful!";
                } else {
                    $alertMessage = "Error: " . $insertQuery . "<br>" . $conn->error;
                }
            }
        } else {
            // Passwords do not match
            $alertMessage = "Passwords do not match. Please enter the same password in both fields.";
        }
    } else {
        // Email, password, or confirmPassword not provided
        $alertMessage = "Email, password, and confirmPassword are required.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup Page</title>
    <!-- Add your CSS and other head content here -->
    <link rel="stylesheet" href="signin.css">
    <link rel="icon" href="img/favicon.jpg" type="image/x-icon">
</head>
<body>
    <div class="background"></div>
    <center>
        <div class="login-container">
            <form action="" method="post">
                <h2 style="color: white;">Signup</h2>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                <button type="submit">Signup</button>
                <br><br>
                <?php echo $alertMessage; header("Location: login.html");?>
                <br>
                <br>
                <a href="login.html" style="text-decoration: none; color: white;">Already have an account? Login</a>
            </form>
        </div>
    </center>
</body>
</html>
