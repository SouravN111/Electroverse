<?php
// Connect to your database
$servername = "localhost";
$username = "root";
$password = "";
$database = "project1";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search value from the AJAX request
$searchValue = $_GET['search'];

// Query to retrieve products based on the search value
$sql = "SELECT * FROM products WHERE product_name LIKE '%$searchValue%'";

$result = $conn->query($sql);

// Check if there are any matching products
if ($result->num_rows > 0) {
    // Output product list as HTML
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product-item'>";
            echo "<img class='product-image' src='" . $row['product_image'] . "' alt='" . $row['product_name'] . "'>";
            echo "<div class='product-name'>" . $row['product_name'] . "</div>";
            echo "<div class='product-price'>₹" . $row['product_price'] . "</div>";
            echo "</div>";
    }
} else {
    echo "No products found.";
}

// Close the database connection
$conn->close();
?>
