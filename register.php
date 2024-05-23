<?php
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $address = $_POST["address"];
    $postcode = $_POST["postcode"];
    $city = $_POST["city"];
    $telephone = $_POST["telephone"];
    $country = $_POST["country"];
    $state = $_POST["state"];

    // Get the session email
    session_start();
    $email = $_SESSION["email"];

    // Check if the email exists in the users table
    $checkQuery = "SELECT * FROM users WHERE email = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Update the address if the email already exists
        $updateQuery = "UPDATE users SET address = ?, postcode = ?, city = ?, telephone = ?, country = ?, state = ? WHERE email = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("sssssss", $address, $postcode, $city, $telephone, $country, $state, $email);
        if ($updateStmt->execute()) {
            echo "Address updated successfully.";
            header("Location: placeorder.php");
        } else {
            echo "Error updating address: " . $conn->error;
        }
    } else {
        // Insert new record if the email doesn't exist
        $insertQuery = "INSERT INTO users (address, postcode, city, telephone, country, state, email) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("sssssss", $address, $postcode, $city, $telephone, $country, $state, $email);
        if ($insertStmt->execute()) {
            echo "Address registered successfully.";
            header("Location: placeorder.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close the prepared statements
    $checkStmt->close();
    $insertStmt->close();
    $updateStmt->close();
} else {
    // If the form is not submitted
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
?>
