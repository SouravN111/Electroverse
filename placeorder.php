<?php
echo "<link rel='stylesheet' href='placeorder.css'>";
echo"<link rel='icon' href='img/favicon.jpg' type='image/x-icon'>";
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

// Fetch cart items for the logged-in user
$userEmail = $_SESSION["email"];
$query = "SELECT * FROM cart WHERE userEmail = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

// Initialize total variable
$total = 0;

// Check if cart is empty
if ($result->num_rows == 0) {
    echo "<div class='empty-cart'>Your cart is empty.</div>";
} else {
    // Display cart items
    while ($row = $result->fetch_assoc()) {
        $productName = $row["productName"];
        $productPrice = $row["productPrice"];
        $productImage = $row["productImage"];
        $quantity = $row["quantity"];

        // Calculate total for each item
        $itemTotal = $productPrice * $quantity;
        $total += $itemTotal;

        // Display cart item with appropriate HTML
        echo "<div class='cart-item'>";
        echo "<img src='$productImage' alt='$productName' class='product-image'>";
        echo "<div class='product-details'>";
        echo "<p class='product-name'>$productName</p>";
        echo "<p class='product-price'>Price: ₹$productPrice</p>";
        echo "<p class='quantity'>Quantity: $quantity</p>";
        echo "<p class='item-total'>Item Total: ₹$itemTotal</p>";
        echo "</div>";
        echo "</div>";
    }

    // Display total
    echo "<p class='total'>Total: ₹$total</p>";
    $_SESSION["total"] = $total;
    // Checkout button
    echo "<form action='' method='POST'>";
    echo "<input type='hidden' name='checkout' value='true'>";
    echo "<button type='submit' class='checkout-btn'>Checkout</button>";
    echo "</form>";
}

$stmt->close();

// Process checkout if checkout button is clicked
if (isset($_POST['checkout']) && $_POST['checkout'] == 'true') {
    // Insert order data into the orders table
    $billNumber = uniqid(); // Generate a unique bill number
    $currentDateTime = date('Y-m-d H:i:s'); // Get the current date and time in MySQL format
    
    $insertOrderQuery = "INSERT INTO `orders` (product_name, quantity, total, userEmail, productPrice, billNumber, order_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $insertOrderStmt = $conn->prepare($insertOrderQuery);
    $insertOrderStmt->bind_param("siisdss", $productName, $quantity, $total, $userEmail, $productPrice, $billNumber, $currentDateTime);
    
    // Loop through cart items to insert each item separately into the orders table
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $productName = $row["productName"];
        $quantity = $row["quantity"];
        $total = $row["productPrice"] * $quantity;
        $productPrice = $row["productPrice"];
        $insertOrderStmt->execute();
    }
    $insertOrderStmt->close();

    // Clear cart
    $clearCartQuery = "DELETE FROM cart WHERE userEmail = ?";
    $clearCartStmt = $conn->prepare($clearCartQuery);
    $clearCartStmt->bind_param("s", $userEmail);
    $clearCartStmt->execute();
    $clearCartStmt->close();

    $conn->close();
    header("Location: output.php");
    exit();
}

$conn->close();
?>
