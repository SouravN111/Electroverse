<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="icon" href="img/favicon.jpg" type="image/x-icon">
    <style>
        /* CSS styles here */
        /* .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        } */

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }

        button {
            padding: 8px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .product {
            margin-bottom: 10px;
        }

        .product h3 {
            margin: 0;
        }

        .product button {
            margin-left: 10px;
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Product Management</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>
            <label for="image">Image URL:</label>
            <input type="text" id="image" name="image" required>
            <label for="page">Page:</label>
            <input type="text" id="page" name="page" required>
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="gaming">gamepad</option>
                <option value="tv">tv</option>
                <option value="tablets">tablet</option>
                <option value="tv">camera</option>
            </select>
            <button type="submit" name="add_product">Add Product</button>
        </form>
        <hr>
        <h2>Product List</h2>
        <div class="product-list">
            <?php
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "project1";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Delete product
            if(isset($_POST['delete_product'])) {
                $id = $_POST['product_id'];
                $sql = "DELETE FROM products WHERE id=$id";

                if ($conn->query($sql) === TRUE) {
                    echo "Product deleted successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }

            // Edit product
            if(isset($_POST['edit_product'])) {
                $id = $_POST['product_id'];
                $name = isset($_POST['name']) ? $_POST['name'] : '';
                $price = isset($_POST['price']) ? $_POST['price'] : 0;
                $image = isset($_POST['image']) ? $_POST['image'] : '';
                $page = isset($_POST['page']) ? $_POST['page'] : '';
                $category = isset($_POST['category']) ? $_POST['category'] : '';
            
                $sql = "UPDATE products SET ";
                $sql .= "product_name='" . $name . "', ";
                $sql .= "product_price=" . $price . ", ";
                $sql .= "product_image='" . $image . "', ";
                $sql .= "page='" . $page . "', ";
                $sql .= "category='" . $category . "' ";
                $sql .= "WHERE id=" . $id;
            
                if ($conn->query($sql) === TRUE) {
                    echo "Product updated successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        

            // Add product
            if(isset($_POST['add_product'])) {
                $name = $_POST['name'];
                $price = $_POST['price'];
                $image = $_POST['image'];
                $page = $_POST['page'];
                $category = $_POST['category'];

                $sql = "INSERT INTO products (product_name, product_price, product_image, page, category) 
                        VALUES ('$name', $price, '$image', '$page', '$category')";

                if ($conn->query($sql) === TRUE) {
                    echo "Product added successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }

            // List products
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='product'>";
                    echo "<img src='" . $row["product_image"] . "' alt='" . $row["product_name"] . "' style='max-width: 100px;'>";
                    echo "<h3>" . $row["product_name"] . " - ₹" . $row["product_price"] . "</h3>";
                    echo "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
                    echo "<input type='hidden' name='product_id' value='".$row['id']."'>";
                    // echo "<button type='submit' name='edit_product'>Edit</button>";
                    echo "<button type='submit' name='delete_product'>Delete</button>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "0 results";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
