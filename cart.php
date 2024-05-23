<?php
session_start();
echo"<link rel='icon' href='img/favicon.jpg' type='image/x-icon'>";
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

// Initialize total
$total = 0;

// Check if cart is empty
if ($result->num_rows == 0) {
    echo "<div class='empty-cart'>Your cart is empty.</div>";
} else {
    // Display cart items
    while ($row = $result->fetch_assoc()) {
        $productId = $row["id"];
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
        echo "<button class='quantity-btn decrease' data-product-id='$productId'>-</button>&nbsp;&nbsp;";
        echo "<input type='number' class='quantity' value='$quantity' data-price='$productPrice' data-product-id='$productId'>";
        echo "&nbsp;&nbsp;&nbsp;<button class='quantity-btn increase' data-product-id='$productId'>+</button>";
        echo "<p class='item-total'>Item Total: ₹$itemTotal</p>";
        echo "</div>";
        echo "</div>";
    }

    // Store total in session
    $_SESSION["total"] = $total;
}
?>

<p class="total">Total: ₹<?php echo $total; ?></p>
<link rel="stylesheet" href="cart.css">
<button id="clear-cart-btn">Clear Cart</button>
<button id="checkout-btn">Checkout</button> <!-- Added checkout button -->


<script>
    document.getElementById('checkout-btn').addEventListener('click', function () {
        window.location.href = 'address.html'; // Redirect to checkout.php
    });

// Function to update item total
function updateItemTotal(productId, quantity) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'updateCartItem.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // Update total after successful update
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    const total = parseFloat(response.total);
                    if (!isNaN(total)) {
                        document.querySelector('.total').textContent = `Total: ₹${total.toFixed(2)}`;
                    } else {
                        alert('Invalid total value received');
                    }

                    const itemTotalElement = document.querySelector(`[data-product-id='${productId}'] + .item-total`);
                    const itemTotal = parseFloat(response.itemTotal);
                    if (!isNaN(itemTotal)) {
                        itemTotalElement.textContent = `Item Total: ₹${itemTotal.toFixed(2)}`;
                    } else {
                        alert('Invalid item total value received');
                    }
                } else {
                    alert('Failed to update cart item');
                }
            } else {
                alert('Error updating cart item');
            }
        }
    };
    xhr.send(`productId=${productId}&quantity=${quantity}`); // Send productId and quantity
}


    // Add event listener for quantity increase and decrease buttons
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const productId = this.dataset.productId;
            const input = this.parentNode.querySelector('.quantity');
            let quantity = parseInt(input.value);

            if (this.classList.contains('decrease')) {
                // Ensure quantity does not go below zero
                quantity = Math.max(0, quantity - 1);
                window.location.reload();
            } else if (this.classList.contains('increase')) {
                quantity++;
            }

            input.value = quantity;
            updateItemTotal(productId, quantity); // Update item total
        });
    });

    // Add event listener for input change
    document.querySelectorAll('.quantity').forEach(input => {
        input.addEventListener('change', function () {
            const productId = this.dataset.productId;
            const quantity = parseInt(this.value);
            updateItemTotal(productId, quantity); // Update item total
        });
    });

    // Add event listener for clear cart button
    document.getElementById('clear-cart-btn').addEventListener('click', function () {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'clearCart.php', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        window.location.reload();
                    } else {
                        alert(response.message);
                    }
                } else {
                    alert('Failed to clear cart');
                }
            }
        };
        xhr.send();
    });
</script>
