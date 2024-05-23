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
        <a href="" class="home" onclick="location.reload();">Home</a>
        <a href="">Products</a>
    </div>
    <div class="nav-items">
        <a href="dasboard.php" class="account">Dasboard</a>
        <a href="cart.php" class="cart">&#128722;</a>
    </div>
</div>
<div id="limited-offer-container">
        <div id="limited-offer-text">Limited <br>Weekely <br> Discount</div>
        <img id="product-image" src="img/zebronics_earphone_1.png" alt="Product Image">
</div>
<div class="icon-div">
    <i class="fa fa-gamepad" id="gamepad"></i>
    <i class="fa fa-camera" id="camera"></i>
    <i class="fa fa-tablet" id="tablet"></i>
    <!-- <i class="fa fa-phone" id="phone"></i> -->
    <i class="fa fa-tv" id="tv"></i>
    <i class="fa fa-headphones" id="headphones"></i>
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
            echo "<div class='product-name'><b><a href='" . $row['page'] . "'>" . $row['product_name'] . "</a></b></div>";
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
document.addEventListener("DOMContentLoaded", function() {
    // Add click event listeners to icons
    document.getElementById("gamepad").addEventListener("click", function() {
        loadCategoryProducts("gamepad");
    });

    document.getElementById("camera").addEventListener("click", function() {
        loadCategoryProducts("camera");
    });

    document.getElementById("tablet").addEventListener("click", function() {
        loadCategoryProducts("tablet");
    });

    document.getElementById("tv").addEventListener("click", function() {
        loadCategoryProducts("tv");
    });

    document.getElementById("headphones").addEventListener("click", function() {
        loadCategoryProducts("headphones");
    });
});

function loadCategoryProducts(category) {
    // Send AJAX request to fetch products for the selected category
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "get_products.php?category=" + category, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var products = JSON.parse(xhr.responseText);
            // Process the products and display them on the page
            displayProducts(products); // Call the displayProducts function with the retrieved products
        }
    };
    xhr.send();
}

function displayProducts(products) {
    var productGrid = document.getElementById("body");
    productGrid.innerHTML = ""; // Clear the product grid before displaying new products

    products.forEach(function(product) {
        var productItem = document.createElement("div");
        productItem.className = "product-item";

        var productImage = document.createElement("img");
        productImage.className = "product-image";
        productImage.src = product.product_image;
        productImage.alt = product.product_name;
        productItem.appendChild(productImage);

        var productName = document.createElement("div");
        productName.className = "product-name";
        var productNameLink = document.createElement("a");
        productNameLink.href = product.page;
        productNameLink.textContent = product.product_name;
        productName.appendChild(productNameLink);
        productItem.appendChild(productName);

        var productPrice = document.createElement("div");
        productPrice.className = "product-price";
        productPrice.textContent = "₹" + product.product_price;
        productItem.appendChild(productPrice);

        productGrid.appendChild(productItem);
    });
}
    </script>
<!-- <script src="on.js"></script> -->
</body>
</html>
