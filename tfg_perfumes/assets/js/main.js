document.addEventListener('DOMContentLoaded', function() {
    // Manejar añadir al carrito
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            
            fetch(`${BASE_URL}process/add_to_cart.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({product_id: productId})
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Producto añadido al carrito');
                    updateCartCount(data.cart_count);
                }
            });
        });
    });
});

function updateCartCount(count) {
    const cartCountElement = document.querySelector('.cart-count');
    if(cartCountElement) {
        cartCountElement.textContent = count;
    }
}