<?php
echo"<link rel='icon' href='img/favicon.jpg' type='image/x-icon'>";
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["email"])) {
    header("Location: login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "project1";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current user's email
$email = $_SESSION["email"];

// Retrieve user data from the database
$query = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Fetch user data
    $row = $result->fetch_assoc();
    $userId = $row["id"];
    $email = $row["email"];
} else {
    // Redirect to login page if user data is not found
    header("Location: login.html");
    exit();
}

// Check if the form to change password is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["newPassword"]) && isset($_POST["confirmNewPassword"])) {
    $newPassword = $_POST["newPassword"];
    $confirmNewPassword = $_POST["confirmNewPassword"];

    // Validate new password and confirm new password
    if ($newPassword === $confirmNewPassword) {
        // Update user's password in the database
        $updateQuery = "UPDATE users SET password='$newPassword' WHERE id='$userId'";
        if ($conn->query($updateQuery) === TRUE) {
            $passwordChangeMessage = "Password changed successfully.";
        } else {
            $passwordChangeMessage = "Error updating password: " . $conn->error;
        }
    } else {
        $passwordChangeMessage = "New passwords do not match.";
    }
}

// Logout functionality
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <h1>Welcome to the Dashboard</h1>
    <p>You are logged in as: <?php echo $email; ?></p>
    <h2>Change Password</h2>
    <form action="" method="post">
        <label for="newPassword">New Password:</label>
        <input type="password" id="newPassword" name="newPassword" required><br>
        <label for="confirmNewPassword">Confirm New Password:</label>
        <input type="password" id="confirmNewPassword" name="confirmNewPassword" required><br>
        <button type="submit">Change Password</button>
        <p><?php echo isset($passwordChangeMessage) ? $passwordChangeMessage : ""; ?></p>
    </form>
    <form action="" method="post">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>
