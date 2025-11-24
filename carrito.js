// Carrito de compras - Sistema completo
class Carrito {
    constructor() {
        this.items = JSON.parse(localStorage.getItem('carrito')) || [];
        this.inicializar();
    }

    inicializar() {
        this.agregarEventosComprar();
        this.actualizarCarrito();
    }

    agregarEventosComprar() {
        const botonesComprar = document.querySelectorAll('.comprar-btn');
        botonesComprar.forEach(boton => {
            boton.addEventListener('click', (e) => {
                const id = e.target.getAttribute('data-id');
                const nombre = e.target.getAttribute('data-nombre');
                const precio = parseFloat(e.target.getAttribute('data-precio'));
                // Buscar select de talle dentro del mismo contenedor
                const container = e.target.closest('.guitarraVenta');
                let talle = 'N/A';
                if (container) {
                    const talleSelect = container.querySelector('.talle-select');
                    if (talleSelect) talle = talleSelect.value || 'N/A';
                }
                const uid = `${id}_${talle}`;
                this.agregarAlCarrito({ id, uid, nombre, precio, cantidad: 1, talle });
            });
        });
    }

    agregarAlCarrito(producto) {
        // Buscar si el producto ya existe (mismo uid incluye talle)
        const productoExistente = this.items.find(item => item.uid === producto.uid);

        if (productoExistente) {
            productoExistente.cantidad += 1;
        } else {
            this.items.push(producto);
        }

        this.guardarCarrito();
        this.actualizarCarrito();
        this.mostrarNotificacion(`${producto.nombre} (Talle: ${producto.talle}) agregado al carrito`);
    }

    eliminarDelCarrito(uid) {
        this.items = this.items.filter(item => item.uid !== uid);
        this.guardarCarrito();
        this.actualizarCarrito();
    }

    actualizarCantidad(uid, cantidad) {
        const item = this.items.find(item => item.uid === uid);
        if (item) {
            item.cantidad = parseInt(cantidad);
            if (item.cantidad <= 0) {
                this.eliminarDelCarrito(uid);
            } else {
                this.guardarCarrito();
                this.actualizarCarrito();
            }
        }
    }

    guardarCarrito() {
        localStorage.setItem('carrito', JSON.stringify(this.items));
    }

    actualizarCarrito() {
        this.actualizarBadgeCarrito();
        this.mostrarItems();
        this.calcularTotal();
    }

    actualizarBadgeCarrito() {
        const cartCount = document.getElementById('cartCount');
        const totalItems = this.items.reduce((sum, item) => sum + item.cantidad, 0);
        
        if (totalItems > 0) {
            cartCount.textContent = totalItems;
            cartCount.style.display = 'block';
        } else {
            cartCount.style.display = 'none';
        }
    }

    mostrarItems() {
        const cartItemsDiv = document.getElementById('cartItems');
        const emptyMessage = document.getElementById('emptyCartMessage');
        const checkoutBtn = document.getElementById('checkoutBtn');

        if (this.items.length === 0) {
            cartItemsDiv.innerHTML = '';
            emptyMessage.style.display = 'block';
            checkoutBtn.style.display = 'none';
            return;
        }

        emptyMessage.style.display = 'none';
        checkoutBtn.style.display = 'block';

        let html = '<div class="table-responsive"><table class="table table-dark table-hover"><thead><tr><th>Producto</th><th>Precio</th><th>Talle</th><th>Cantidad</th><th>Total</th><th>Acción</th></tr></thead><tbody>';

        this.items.forEach(item => {
            const total = (item.precio * item.cantidad).toFixed(2);
            const talle = item.talle ? item.talle : 'N/A';
            html += `
                <tr>
                    <td>${item.nombre}</td>
                    <td>$${item.precio.toFixed(2)}</td>
                    <td>${talle}</td>
                    <td>
                        <input type="number" min="1" value="${item.cantidad}" class="form-control form-control-sm" style="width: 60px;" data-uid="${item.uid}" onchange="carrito.actualizarCantidad('${item.uid}', this.value)">
                    </td>
                    <td>$${total}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="carrito.eliminarDelCarrito('${item.uid}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table></div>';
        cartItemsDiv.innerHTML = html;
    }

    calcularTotal() {
        const total = this.items.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
        document.getElementById('cartTotal').textContent = total.toFixed(2);
    }

    mostrarNotificacion(mensaje) {
        // Crear notificación temporal
        const alerta = document.createElement('div');
        alerta.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
        alerta.style.zIndex = '9999';
        alerta.textContent = mensaje;
        document.body.appendChild(alerta);

        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }

    procederACompra() {
        if (this.items.length === 0) {
            alert('El carrito está vacío');
            return;
        }

        // Guardar carrito temporalmente en sessionStorage para la página de confirmación
        sessionStorage.setItem('carritoConfirmacion', JSON.stringify(this.items));
        
        // Mostrar modal de confirmación en la misma página
        mostrarConfirmacion(this.items);
        
        // Cerrar modal del carrito
        const modalCarrito = bootstrap.Modal.getInstance(document.getElementById('cartModal'));
        if (modalCarrito) {
            modalCarrito.hide();
        }
    }
}

// Instancia global del carrito
const carrito = new Carrito();

// Event listener para el botón de proceder a compra
document.addEventListener('DOMContentLoaded', () => {
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            carrito.procederACompra();
        });
    }
});
