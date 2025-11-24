// Manejo de la confirmación de compra
let carritoConfirmacion = [];

document.addEventListener('DOMContentLoaded', () => {
    // Obtener carrito de sessionStorage
    carritoConfirmacion = JSON.parse(sessionStorage.getItem('carritoConfirmacion')) || [];
    
    if (carritoConfirmacion.length === 0) {
        document.getElementById('formularioConfirmacion').style.display = 'none';
        document.getElementById('btnConfirmar').style.display = 'none';
        document.getElementById('mensajeEstado').innerHTML = '<div class="alert alert-warning">No hay productos en el carrito</div>';
        return;
    }

    mostrarResumen();
});

function mostrarResumen() {
    const tbody = document.getElementById('resumenItems');
    let html = '';
    let total = 0;

    carritoConfirmacion.forEach(item => {
        const subtotal = item.precio * item.cantidad;
        total += subtotal;

        html += `
            <tr>
                <td>${item.nombre}</td>
                <td>$${item.precio.toFixed(2)}</td>
                <td>${item.cantidad}</td>
                <td>$${subtotal.toFixed(2)}</td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
    document.getElementById('totalFinal').textContent = total.toFixed(2);
}

async function confirmarCompra() {
    // Validar formulario
    const nombre = document.getElementById('nombre').value.trim();
    const email = document.getElementById('email').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const direccion = document.getElementById('direccion').value.trim();
    const terminos = document.getElementById('terminos').checked;

    if (!nombre || !email || !direccion) {
        mostrarMensaje('Por favor completa todos los campos requeridos', 'danger');
        return;
    }

    if (!terminos) {
        mostrarMensaje('Debes aceptar los términos y condiciones', 'danger');
        return;
    }

    // Validar email
    if (!validarEmail(email)) {
        mostrarMensaje('El correo electrónico no es válido', 'danger');
        return;
    }

    // Desactivar botón mientras se procesa
    const btnConfirmar = document.getElementById('btnConfirmar');
    btnConfirmar.disabled = true;
    btnConfirmar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Procesando...';

    try {
        // Enviar datos al servidor
        const response = await fetch('enviar_email.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nombre: nombre,
                email: email,
                telefono: telefono,
                direccion: direccion,
                carrito: carritoConfirmacion,
                total: calcularTotal()
            })
        });

        const resultado = await response.json();

        if (resultado.exito) {
            mostrarMensaje('¡Compra confirmada! Se ha enviado un email de confirmación a ' + email, 'success');
            
            // Limpiar carrito después de 2 segundos
            setTimeout(() => {
                sessionStorage.removeItem('carritoConfirmacion');
                localStorage.removeItem('carrito');
                // Redirigir a la página principal
                window.opener.location.reload();
                window.close();
            }, 2000);
        } else {
            mostrarMensaje('Error: ' + resultado.mensaje, 'danger');
            btnConfirmar.disabled = false;
            btnConfirmar.innerHTML = '<i class="fas fa-check"></i> Confirmar Compra y Enviar Email';
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarMensaje('Error al procesar la compra: ' + error.message, 'danger');
        btnConfirmar.disabled = false;
        btnConfirmar.innerHTML = '<i class="fas fa-check"></i> Confirmar Compra y Enviar Email';
    }
}

function mostrarMensaje(texto, tipo) {
    const div = document.getElementById('mensajeEstado');
    div.innerHTML = `<div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
        ${texto}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;
}

function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function calcularTotal() {
    return carritoConfirmacion.reduce((sum, item) => sum + (item.precio * item.cantidad), 0).toFixed(2);
}
