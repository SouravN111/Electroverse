<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_email"])) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

// Fetch total from the session variable
$total = isset($_SESSION['total']) ? $_SESSION['total'] : null;

// Validate total
if ($total === null) {
    header("HTTP/1.1 400 Bad Request");
    exit();
}

// Store the total in a session variable for future use
$_SESSION['order_total'] = $total;

// Redirect to placeorder.php
header("Location: placeorder.php");
exit();
?>
