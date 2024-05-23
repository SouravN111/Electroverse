<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="img/favicon.jpg" type="image/x-icon">
    <title>index</title>
</head>
<body >

<!-- Navbar 1 -->
<div class="navbar1">
    <a href="#" class="logo">Electroverse</a>
    <div class="categories">
        <!-- <a href="#">Categories</a> -->
        <div class="categories-content">
            <a href="#">Category 1</a>
            <a href="#">Category 2</a>
            <a href="#">Category 3</a>
        </div>
    </div>
    <div class="search">
    <input type="text" placeholder="Search..." id="searchInput">
    <div id="search-icon">&#128269;</div>
</div>
    <div class="contact">
        <div class="contact-icon">&#9742;</div>
        <div>+919913470609</div>
        <div class="contact-icon">&#9993;</div>
        <div>raketyler12@gmail.com</div>
    </div>
</div>

<!-- Navbar 2 -->
<div class="navbar2">
    <div class="nav-items">
        <a href="index.php" class="home">Home</a>
        <a href="index.php">Products</a>
    </div>
    <div class="nav-items">
        <a href="login.html" class="account">Account</a>
        <a href="#" class="cart">&#128722;</a>
    </div>
</div>
<div id="limited-offer-container">
        <div id="limited-offer-text">Limited <br>Weekely <br> Discount</div>
        <img id="product-image" src="img/zebronics_earphone_1.png" alt="Product Image">
</div>
<div class="icon-div">
    <i class="fa fa-gamepad"></i>
    <i class="fa fa-camera"></i>
    <i class="fa fa-tablet"></i>
    <i class="fa fa-phone"></i>
    <i class="fa fa-tv"></i>
    <i class="fa fa-headphones"></i>
    <!-- <i class="fa fa-microphone"></i>
    <i class="fa fa-home"></i> -->
</div>
<div class="product-grid" id="body">
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

    // Fetch products from the database
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<div class='product-item'>";
            echo "<img class='product-image' src='" . $row['product_image'] . "' alt='" . $row['product_name'] . "'>";
            echo "<div class='product-name'>" . $row['product_name'] . "</div>";
            echo "<div class='product-price'>₹" . $row['product_price'] . "</div>";
            echo "</div>";
        }
    } else {
        echo "No products found.";
    }
    $conn->close();
    ?>
</div>

<script src="img.js">
        // JavaScript code to change the product image every 5 seconds
         // Change image every 5 seconds
    </script>
    <script>
   document.addEventListener('DOMContentLoaded', function() {
    var productGrid = document.querySelector('.product-grid'); // Use class selector
    if (productGrid) {
        document.getElementById('search-icon').addEventListener('click', function() {
            var searchValue = document.getElementById('searchInput').value;
            if (searchValue.trim() !== '') {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == XMLHttpRequest.DONE) {
                        if (xhr.status == 200) {
                            productGrid.innerHTML = xhr.responseText;
                        } else {
                            console.error('Error:', xhr.statusText);
                        }
                    }
                };
                xhr.open('GET', 'search_products.php?search=' + encodeURIComponent(searchValue), true);
                xhr.send();
            }
        });
    } else {
        console.error('Product grid element not found.');
    }
});

    </script>
<script src="on.js"></script>
</body>
</html>
