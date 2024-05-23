var images = ["img/zebronics_earphone_1.png", "img/zebronics_mouse_1.png", "img/zebronics_mouse_2.png"]; // List of product images
        var currentIndex = 0;
        var productImage = document.getElementById('product-image');

        setInterval(function() {
            currentIndex = (currentIndex + 1) % images.length; // Circular rotation of images
            productImage.src = images[currentIndex]; // Change the product image
        }, 5000);