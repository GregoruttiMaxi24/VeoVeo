# 📦 SISTEMA DE CARRITO COMPLETO - VeoVeo

## ✅ Lo Que Se Ha Creado

### 1. **Frontend (HTML/CSS/JavaScript)**
- ✅ **index.html** - Página principal con:
  - Navbar con botón de carrito
  - 5 productos de ejemplo con precios
  - Modal para visualizar el carrito
  - Integración con Bootstrap 5

- ✅ **carrito.js** - Lógica del carrito:
  - Agregar productos con un clic
  - Modificar cantidades
  - Eliminar productos
  - Almacenamiento en localStorage
  - Contador automático de items

- ✅ **confirmacion.html** - Página de confirmación:
  - Resumen del pedido
  - Formulario de datos del cliente
  - Validación de entrada
  - Aceptación de términos

- ✅ **confirmacion.js** - Lógica de confirmación:
  - Validación de datos
  - Integración con servidor PHP
  - Envío de información de compra

- ✅ **styles.css** - Estilos mejorados:
  - Tema oscuro profesional
  - Animaciones suaves
  - Responsive design
  - Hover effects

### 2. **Backend (PHP)**
- ✅ **enviar_email.php** - Envío básico de emails
  - Validación de datos
  - Construcción de email HTML
  - Envío al cliente y administrador
  - Almacenamiento de compras

- ✅ **enviar_email_advanced.php** - Versión avanzada con:
  - Soporte para Gmail, SMTP personalizado, SendGrid
  - PHPMailer integration
  - Emails HTML profesionales
  - Mejor manejo de errores

- ✅ **config.php** - Configuración centralizada:
  - Variables de email
  - Funciones auxiliares
  - Manejo de rutas
  - Sistema de logs

### 3. **Configuración**
- ✅ **.env.example** - Template de variables de entorno
- ✅ **.gitignore** - Archivos a ignorar en Git
- ✅ **composer.json** - Dependencias PHP
- ✅ **install.sh** - Script de instalación Linux/Mac
- ✅ **install.bat** - Script de instalación Windows

### 4. **Documentación**
- ✅ **README.md** - Guía general de uso
- ✅ **CONFIGURACION_EMAILS.md** - Guía detallada de emails
- ✅ **INSTALACION.md** - Este archivo

---

## 🚀 Flujo Completo del Sistema

```
1. Usuario visita index.html
   ↓
2. Usuario hace clic en "Comprar" en un producto
   ├→ Producto agregado al carrito (localStorage)
   ├→ Contador de carrito se actualiza
   └→ Notificación de confirmación

3. Usuario abre el modal del carrito
   ├→ Ve todos los productos
   ├→ Puede modificar cantidades
   └→ Puede eliminar productos

4. Usuario hace clic en "Proceder a Comprar"
   └→ Se abre confirmacion.html en nueva ventana

5. Usuario completa formulario de confirmación
   ├→ Nombre, email, teléfono, dirección
   └→ Acepta términos y condiciones

6. Usuario hace clic en "Confirmar Compra"
   ├→ Datos se envían a enviar_email.php
   ├→ Email HTML se genera
   ├→ Email se envía al cliente
   ├→ Email de confirmación se envía al admin
   ├→ Datos se guardan en carpeta compras/
   └→ Carrito se limpia automáticamente
```

---

## 📋 Checklist de Instalación

### Requisitos Previos
- [ ] PHP 7.0 o superior instalado
- [ ] Servidor web corriendo (Apache, Nginx, etc.)
- [ ] Git (opcional, para control de versiones)

### Instalación Paso a Paso

1. **Descargar/Clonar el proyecto**
   ```bash
   # O simplemente copia todos los archivos a tu servidor
   ```

2. **Ejecutar script de instalación**
   ```bash
   # En Linux/Mac
   chmod +x install.sh
   ./install.sh

   # En Windows
   install.bat
   ```

3. **Configurar emails**
   ```bash
   # Copiar archivo de ejemplo
   cp .env.example .env

   # Editar .env con tus credenciales
   nano .env
   ```

4. **Crear carpetas necesarias**
   ```bash
   mkdir -p compras logs uploads
   chmod 755 compras logs uploads
   ```

5. **Instalar dependencias (opcional pero recomendado)**
   ```bash
   composer install
   ```

### Configuración de Email (IMPORTANTE)

**Opción 1: Gmail (Recomendado para pruebas)**
1. Habilitar verificación en dos pasos
2. Generar contraseña de app
3. Copiar credenciales a `.env`
4. Editar `enviar_email.php` líneas 30-35

**Opción 2: SMTP Personalizado**
1. Obtener credenciales del hosting
2. Configurar en `config.php`
3. Actualizar `enviar_email.php`

**Opción 3: SendGrid (Producción)**
1. Crear cuenta en sendgrid.com
2. Generar API Key
3. Usar `enviar_email_advanced.php`

Ver **CONFIGURACION_EMAILS.md** para detalles completos.

---

## 🧪 Pruebas

### Prueba 1: Funcionalidad del Carrito
1. Abrir `http://localhost:8000`
2. Hacer clic en "Comprar" en varios productos
3. Verificar que:
   - [ ] Contador aumenta correctamente
   - [ ] Modal muestra todos los productos
   - [ ] Cambiar cantidad funciona
   - [ ] Eliminar producto funciona
   - [ ] Total se calcula correctamente

### Prueba 2: Confirmación de Compra
1. En el modal del carrito, hacer clic en "Proceder a Comprar"
2. Llenar formulario con:
   - [ ] Nombre completo
   - [ ] Email válido
   - [ ] Dirección
3. Marcar "Acepto términos"
4. Hacer clic en "Confirmar Compra"

### Prueba 3: Email
1. Revisar bandeja de entrada
2. Verificar que:
   - [ ] Email llega correctamente
   - [ ] Contiene todos los productos
   - [ ] Muestra total correcto
   - [ ] Datos del cliente son correctos
   - [ ] No está en spam

### Prueba 4: Base de Datos de Compras
1. Revisar carpeta `/compras/`
2. Verificar:
   - [ ] Archivo JSON creado
   - [ ] Contiene datos de la compra
   - [ ] Nombre es único (timestamp)

---

## 📁 Estructura de Archivos Final

```
veoveo/
├── index.html                    # Página principal
├── confirmacion.html             # Confirmación de compra
├── styles.css                    # Estilos
├── carrito.js                    # Lógica carrito
├── confirmacion.js               # Lógica confirmación
├── enviar_email.php              # Envío de emails (versión 1)
├── enviar_email_advanced.php     # Envío de emails (versión 2)
├── config.php                    # Configuración
├── composer.json                 # Dependencias PHP
├── README.md                     # Documentación general
├── CONFIGURACION_EMAILS.md       # Guía de emails
├── INSTALACION.md                # Este archivo
├── install.sh                    # Script instalación Linux/Mac
├── install.bat                   # Script instalación Windows
├── .env.example                  # Ejemplo de variables
├── .gitignore                    # Archivos a ignorar
├── compras/                      # Almacén de compras
│   ├── compra_20250101120000.json
│   └── compra_20250101120030.json
├── logs/                         # Logs del sistema
│   └── veoveo_2025-01-01.log
├── uploads/                      # Imágenes/archivos
└── img/                          # Imágenes de productos
    ├── vestido1.jpg
    └── ...
```

---

## 🔒 Seguridad

### Medidas Implementadas
- ✅ Validación en servidor (PHP)
- ✅ Sanitización de datos HTML
- ✅ Validación de emails
- ✅ Protección de credenciales (variables de entorno)
- ✅ Archivos sensibles en .gitignore
- ✅ Almacenamiento seguro de compras

### Recomendaciones Adicionales
- 🔐 Usar HTTPS en producción
- 🔐 Cambiar permisos de carpetas sensibles
- 🔐 No compartir `.env` en repositorios públicos
- 🔐 Implementar rate limiting en PHP
- 🔐 Validar CSRF tokens
- 🔐 Encriptar datos sensibles en base de datos

---

## 🚨 Solución de Problemas

### El carrito no persiste
**Problema:** Los productos desaparecen al recargar
- Verificar: localStorage está habilitado en navegador
- Solución: Limpiar caché del navegador

### El email no se envía
**Problema:** No llegan emails de confirmación
- Verificar: Credenciales en `.env` son correctas
- Verificar: PHP mail() está habilitado
- Verificar: Servidor SMTP es accesible
- Ver: `logs/` para mensajes de error

### Error 404 en confirmacion.html
**Problema:** No encuentra la página de confirmación
- Verificar: Todos los archivos están en la misma carpeta
- Verificar: Nombre del archivo es exacto: `confirmacion.html`

### Archivo carrito.js no carga
**Problema:** Error en consola: "carrito.js not found"
- Verificar: Archivo existe en la carpeta raíz
- Verificar: Ruta en index.html es correcta

---

## 📞 Soporte

Para problemas específicos:
1. Revisar `logs/` para mensajes de error
2. Abrir consola del navegador (F12) para ver errores
3. Verificar que PHP está corriendo: `php -v`

---

## 🎯 Próximas Mejoras (Opcional)

Funcionalidades que podrías agregar:

1. **Base de Datos MySQL**
   - Guardar compras en BD en lugar de archivos JSON
   - Agregar tabla de usuarios
   - Historial de compras por usuario

2. **Panel de Administrador**
   - Ver todas las compras
   - Cambiar estado de pedidos
   - Estadísticas de ventas

3. **Pasarelas de Pago**
   - Integrar MercadoPago
   - Integrar Stripe
   - Integrar PayPal

4. **Autenticación**
   - Login de usuarios
   - Perfiles personalizados
   - Carrito sincronizado en la nube

5. **Mejoras UX**
   - Filtros de productos
   - Búsqueda
   - Reviews y calificaciones
   - Wishlist

---

## 📝 Licencia

Este proyecto es propiedad de VeoVeo © 2025

---

**¡Listo para usar!** 🎉

Ahora puedes comenzar a recibir pedidos y emails de confirmación automáticamente.
