# VeoVeo - Sistema de Carrito de Compras

## Descripción
Sistema completo de carrito de compras con funcionalidad de envío de emails de confirmación.

## Características Implementadas

### 1. **Carrito de Compras Funcional**
- Agregar productos al carrito con un clic
- Visualizar todos los productos en el carrito
- Aumentar/disminuir cantidad de productos
- Eliminar productos individuales
- Contador de artículos en el botón del carrito
- Almacenamiento local (localStorage) para persistencia del carrito

### 2. **Modal del Carrito**
- Tabla clara con todos los productos
- Inputs para modificar cantidades
- Botón de eliminar para cada producto
- Total automático calculado
- Botón "Proceder a Comprar"

### 3. **Página de Confirmación**
- Muestra resumen completo de la compra
- Formulario para datos del cliente:
  - Nombre completo
  - Correo electrónico
  - Teléfono (opcional)
  - Dirección de entrega
- Aceptación de términos y condiciones
- Validación de datos

### 4. **Envío de Emails**
- Email HTML profesional al cliente
- Resumen detallado de productos
- Datos de envío
- Email de confirmación al administrador
- Registro de compras en archivos JSON

## Archivos Incluidos

```
VeoVeo/
├── index.html          # Página principal con productos y carrito
├── styles.css          # Estilos de la aplicación
├── carrito.js          # Lógica del carrito
├── confirmacion.html   # Página de confirmación de compra
├── confirmacion.js     # Lógica de confirmación
├── enviar_email.php    # Script para enviar emails
├── README.md          # Este archivo
└── img/               # Carpeta para imágenes
```

## Configuración Requerida

### 1. **PHP y Servidor Web**
El proyecto requiere un servidor con PHP instalado para funcionar correctamente.

**Opciones:**
- **XAMPP** (Windows) - https://www.apachefriends.org/
- **WAMP** (Windows) - http://www.wampserver.com/
- **MAMP** (Mac) - https://www.mamp.info/

### 2. **Configuración de Emails (IMPORTANTE)**

En el archivo `enviar_email.php`, línea 74-75, cambiar:

```php
$email_admin = "admin@veoveo.com"; // CAMBIAR POR EMAIL DEL ADMINISTRADOR
```

Por el correo de tu administrador.

**Opciones para enviar emails:**

#### Opción A: Usar SendGrid (Recomendado)
1. Registrarse en https://sendgrid.com/
2. Crear API Key
3. Usar librería SendGrid con PHP

#### Opción B: Usar Gmail SMTP
1. Usar PHPMailer o similar
2. Configurar credenciales de Gmail

#### Opción C: Usar servidor SMTP local
- Configurar Postfix o similar en el servidor

### 3. **Estructura de Carpetas**

Asegúrate de que el servidor tenga permisos de escritura en la carpeta raíz para crear la carpeta `compras/` donde se guardarán los registros.

```bash
chmod 755 /ruta/a/veoveo/
```

## Cómo Usar

### Para el Usuario:

1. **Agregar Productos al Carrito**
   - Hacer clic en "Comprar" en cualquier producto
   - El carrito se actualiza automáticamente
   - El badge muestra la cantidad total

2. **Ver y Modificar Carrito**
   - Hacer clic en el botón "Carrito" en la barra de navegación
   - Se abre un modal con todos los productos
   - Puedes cambiar cantidades o eliminar productos

3. **Confirmar Compra**
   - Hacer clic en "Proceder a Comprar"
   - Se abre una nueva ventana con el formulario de confirmación
   - Completar datos personales y dirección
   - Aceptar términos y condiciones
   - Hacer clic en "Confirmar Compra"

4. **Confirmación por Email**
   - Recibirás un email con el resumen de tu compra
   - El carrito se limpiará automáticamente

### Para el Administrador:

1. **Ver Compras Realizadas**
   - Revisar la carpeta `compras/` en el servidor
   - Cada compra está guardada en un archivo JSON

2. **Histórico de Compras**
   - Los archivos se nombran con timestamp
   - Formato: `compra_YYYYMMDDHHmmss.json`

## Personalización

### Cambiar Precios o Productos

En `index.html`, modificar atributos en los botones:

```html
<button class="btn btn-lg mt-auto btn-primary comprar-btn" 
        data-id="1" 
        data-nombre="Mi Producto" 
        data-precio="99.99">
    Comprar
</button>
```

### Cambiar Estilos

Editar `styles.css` para personalizar colores, tamaños, etc.

### Agregar Productos

1. Copiar una tarjeta de producto existente
2. Cambiar `data-id`, `data-nombre` y `data-precio`
3. Asignar una nueva columna Bootstrap (col-lg-3, col-lg-6, etc.)

## Seguridad

⚠️ **Importante:**

1. **Validación en el servidor**: El archivo PHP valida todos los datos
2. **Sanitización**: Se sanitizan todas las entradas
3. **Email de confirmación**: Se envía también al administrador
4. **Almacenamiento**: Las compras se guardan con timestamp único

## Pruebas Recomendadas

1. Agregar múltiples productos al carrito
2. Modificar cantidades
3. Eliminar productos
4. Completar formulario con datos válidos e inválidos
5. Verificar que se reciba el email
6. Verificar archivos en carpeta `compras/`

## Troubleshooting

### El email no se envía
- Verificar que PHP está correctamente configurado
- Revisar logs del servidor PHP
- Usar herramientas como Mailtrap para testeo

### El carrito no persiste
- Verificar que localStorage está habilitado en el navegador
- Limpiar caché del navegador

### Errores 404
- Asegúrate de que todos los archivos están en la misma carpeta
- Verifica que el servidor web está corriendo correctamente

## Soporte

Para reportar problemas o sugerencias, contactar al equipo de desarrollo.

## Licencia

Proyecto VeoVeo © 2025 - Todos los derechos reservados.
