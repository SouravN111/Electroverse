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
