// 📌 1. Variables globales
let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

// 📌 2. Añadir producto al carrito (desde producto.html)
document.addEventListener('DOMContentLoaded', () => {
    // Si estamos en la página de producto
    if (document.querySelector('.btn-add')) {
        const btnAdd = document.querySelector('.btn-add');
        btnAdd.addEventListener('click', añadirAlCarrito);
    }

    // Si estamos en la página del carrito
    if (document.querySelector('.carrito')) {
        mostrarCarrito();
    }
});

// 📌 3. Función para añadir productos
function añadirAlCarrito() {
    const producto = {
        id: new URLSearchParams(window.location.search).get('id'),
        nombre: document.querySelector('.detalle-producto h1').textContent,
        precio: parseFloat(document.querySelector('.precio').textContent.replace('€', '')),
        cantidad: parseInt(document.querySelector('.cantidad input').value),
        imagen: document.querySelector('.imagenes img').src
    };

    // Verificar si el producto ya está en el carrito
    const productoExistente = carrito.find(item => item.id === producto.id);
    if (productoExistente) {
        productoExistente.cantidad += producto.cantidad;
    } else {
        carrito.push(producto);
    }

    // Actualizar localStorage y mostrar notificación
    localStorage.setItem('carrito', JSON.stringify(carrito));
    alert(`${producto.nombre} añadido al carrito!`);
}

// 📌 4. Función para mostrar el carrito
function mostrarCarrito() {
    const contenedorItems = document.querySelector('.items');
    const contenedorTotal = document.querySelector('.total span');

    if (carrito.length === 0) {
        contenedorItems.innerHTML = '<p>Tu carrito está vacío</p>';
        contenedorTotal.textContent = '€0.00';
        return;
    }

    // Generar HTML para cada producto
    contenedorItems.innerHTML = carrito.map(item => `
        <div class="item" data-id="${item.id}">
            <img src="${item.imagen}" alt="${item.nombre}">
            <div class="detalle">
                <h3>${item.nombre}</h3>
                <p>€${item.precio.toFixed(2)} x <span class="cantidad">${item.cantidad}</span></p>
                <button class="btn-eliminar">Eliminar</button>
            </div>
        </div>
    `).join('');

    // Calcular total
    const total = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
    contenedorTotal.textContent = `€${total.toFixed(2)}`;

    // Añadir eventos a los botones de eliminar
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', eliminarDelCarrito);
    });
}

// 📌 5. Función para eliminar productos
function eliminarDelCarrito(e) {
    const itemId = e.target.closest('.item').dataset.id;
    carrito = carrito.filter(item => item.id !== itemId);
    localStorage.setItem('carrito', JSON.stringify(carrito));
    mostrarCarrito(); // Refrescar la vista
}   