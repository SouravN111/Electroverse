<?php
echo "<link rel='stylesheet' href='output.css'>";
echo"<link rel='icon' href='img/favicon.jpg' type='image/x-icon'>";
session_start();

// Include Dompdf autoload file
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

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

// Fetch total from session
$total = isset($_SESSION["total"]) ? $_SESSION["total"] : 0;

// Fetch cart items for the logged-in user
$userEmail = $_SESSION["email"];
$query = "SELECT * FROM orders WHERE userEmail = ? AND DATE(order_date) = CURDATE()";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

// Initialize PDF content
$pdfContent = "<h1>Order Details</h1>";
$pdfContent .= "<table border='1'><tr><th>Product Name</th><th>Quantity</th></tr>";

// Generate a random bill number
$billNumber = rand(0, 999999);

// Insert cart items into the order table and build PDF content
while ($row = $result->fetch_assoc()) {
    $productName = $row["product_name"];
    $quantity = $row["quantity"];

    $pdfContent .= "<tr><td>$productName</td><td>$quantity</td></tr>";
}

// Close the database connection
$stmt->close();
$conn->close();

// Complete the PDF content
$pdfContent .= "</table>";
$pdfContent .= "<p>Total: ₹$total</p>";

// Use Dompdf to generate PDF content
$dompdf = new Dompdf();
$dompdf->loadHtml($pdfContent);

// (Optional) Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render PDF content
$dompdf->render();

// Save the generated PDF content to a file
$pdfFilePath = "order_details_$billNumber.pdf";
file_put_contents($pdfFilePath, $dompdf->output());

// Provide a download link for the PDF
echo "<p class='success-message'>Order placed successfully! Your bill number is: $billNumber</p>";
echo "<a href='$pdfFilePath' download='Order_Details.pdf' class='download-link'>Download Order Details PDF</a>";
?>
