# Guía de Configuración de Emails - VeoVeo

## Tabla de Contenidos
1. [Requisitos Previos](#requisitos-previos)
2. [Opción 1: Gmail (Recomendado para principiantes)](#opción-1-gmail)
3. [Opción 2: SMTP Personalizado](#opción-2-smtp-personalizado)
4. [Opción 3: SendGrid (Recomendado para producción)](#opción-3-sendgrid)
5. [Pruebas y Troubleshooting](#pruebas-y-troubleshooting)

---

## Requisitos Previos

- ✅ PHP 7.0 o superior
- ✅ Servidor web (Apache, Nginx, etc.)
- ✅ Acceso a un proveedor de email
- ✅ Navegador web para pruebas

---

## Opción 1: Gmail

### Paso 1: Habilitar Verificación en Dos Pasos

1. Ir a https://myaccount.google.com/
2. En el panel izquierdo, hacer clic en "Seguridad"
3. Buscar "Verificación en dos pasos" y habilitarla

### Paso 2: Generar Contraseña de Aplicación

1. Ir a https://myaccount.google.com/apppasswords
2. Seleccionar:
   - **Aplicación:** Mail
   - **Dispositivo:** Windows/Mac/Linux
3. Se generará una contraseña de 16 caracteres
4. **Copiar esta contraseña** (sin espacios)

### Paso 3: Configurar en `config.php`

```php
define('EMAIL_SERVICE', 'gmail');
define('GMAIL_ADDRESS', 'tu_email@gmail.com');
define('GMAIL_PASSWORD', 'lacontraseña16caracteres'); // Sin espacios
define('ADMIN_EMAIL', 'tu_email@gmail.com');
```

### Paso 4: Actualizar `enviar_email.php`

Reemplazar en la línea ~30:

```php
// Configuración original
$smtp_host = 'smtp.gmail.com';
$smtp_port = 587;
$smtp_user = 'tu_email@gmail.com'; // ← Cambiar aquí
$smtp_password = 'la_contraseña_app'; // ← Cambiar aquí
$email_from = 'tu_email@gmail.com'; // ← Cambiar aquí
```

### ✅ Prueba

```bash
# En la carpeta del proyecto, crear test_email.php
php test_email.php
```

**Ventajas:**
- ✅ Gratis
- ✅ Fácil de configurar
- ✅ No requiere servidor SMTP propio

**Desventajas:**
- ❌ Límite de 500 emails/día
- ❌ Puede marcar como spam

---

## Opción 2: SMTP Personalizado

Útil si tienes un dominio propio con hosting.

### Paso 1: Obtener Credenciales SMTP

Contacta a tu proveedor de hosting y solicita:
- **Host SMTP:** smtp.tudominio.com
- **Puerto:** 587 o 465
- **Usuario:** usuario@tudominio.com
- **Contraseña:** tu_contraseña

### Paso 2: Configurar en `config.php`

```php
define('EMAIL_SERVICE', 'smtp');
define('SMTP_HOST', 'smtp.tudominio.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'usuario@tudominio.com');
define('SMTP_PASSWORD', 'tu_contraseña');
define('ADMIN_EMAIL', 'admin@tudominio.com');
```

### Paso 3: Actualizar `enviar_email.php`

```php
$smtp_host = 'smtp.tudominio.com';
$smtp_port = 587;
$smtp_user = 'usuario@tudominio.com';
$smtp_password = 'tu_contraseña';
$email_from = 'usuario@tudominio.com';
```

**Ventajas:**
- ✅ Control total
- ✅ Sin límites de envío
- ✅ Mejor reputación de email

**Desventajas:**
- ❌ Requiere hosting con soporte SMTP
- ❌ Más complejo de configurar

---

## Opción 3: SendGrid (Recomendado para Producción)

Mejor opción para aplicaciones en producción.

### Paso 1: Crear Cuenta en SendGrid

1. Ir a https://sendgrid.com/
2. Crear cuenta gratuita
3. Verificar email

### Paso 2: Generar API Key

1. En el panel de SendGrid, ir a Settings → API Keys
2. Crear nueva API Key
3. Copiar la clave completa

### Paso 3: Instalar PHPMailer

```bash
# En la carpeta del proyecto
composer require phpmailer/phpmailer

# O descargar manualmente desde:
# https://github.com/PHPMailer/PHPMailer/releases
```

### Paso 4: Crear `enviar_email_sendgrid.php`

```php
<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.sendgrid.net';
    $mail->SMTPAuth = true;
    $mail->Username = 'apikey';
    $mail->Password = 'SG.tu_api_key_aqui';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('tu_email@tudominio.com', 'VeoVeo');
    $mail->addAddress($email);
    
    $mail->isHTML(true);
    $mail->Subject = 'Confirmación de Compra';
    $mail->Body = $cuerpo_html;

    $mail->send();
    echo json_encode(['exito' => true]);
} catch (Exception $e) {
    echo json_encode(['exito' => false, 'error' => $mail->ErrorInfo]);
}
?>
```

**Ventajas:**
- ✅ Muy confiable
- ✅ Buena entregabilidad
- ✅ Plan gratuito generoso (100 emails/día)
- ✅ Estadísticas detalladas

**Desventajas:**
- ❌ Requiere cuenta
- ❌ Requiere Composer para PHPMailer

---

## Pruebas y Troubleshooting

### Test 1: Verificar Conectividad SMTP

```php
<?php
$host = 'smtp.gmail.com';
$port = 587;

$socket = @fsockopen($host, $port, $errno, $errstr, 5);

if (!$socket) {
    echo "Error: $errstr ($errno)";
} else {
    echo "Conectado correctamente a $host:$port";
    fclose($socket);
}
?>
```

### Test 2: Enviar Email de Prueba

Crear archivo `test_email.php`:

```php
<?php
require 'vendor/autoload.php';
require 'config.php';

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);

try {
    $config = obtenerConfigEmail();

    $mail->isSMTP();
    $mail->Host = $config['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['username'];
    $mail->Password = $config['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $config['port'];

    $mail->setFrom($config['from'], 'VeoVeo Test');
    $mail->addAddress('tu_email@gmail.com'); // ← Cambiar

    $mail->isHTML(true);
    $mail->Subject = 'Email de Prueba';
    $mail->Body = '<h1>¡Funciona!</h1><p>Este es un email de prueba.</p>';

    if ($mail->send()) {
        echo "✅ Email enviado correctamente";
    } else {
        echo "❌ Error: " . $mail->ErrorInfo;
    }
} catch (Exception $e) {
    echo "❌ Excepción: " . $e->getMessage();
}
?>
```

Ejecutar:
```bash
php test_email.php
```

### Errores Comunes

| Error | Causa | Solución |
|-------|-------|----------|
| "Could not connect" | Servidor SMTP no accesible | Verificar host y puerto |
| "Authentication failed" | Credenciales incorrectas | Revisar usuario/contraseña |
| "Email marked as spam" | Configuración SPF/DKIM | Configurar registros DNS |
| "Connection timed out" | Firewall bloqueando puerto | Usar puerto 465 en lugar de 587 |

### Verificación de Registros DNS (para dominios propios)

Para mejorar entregabilidad, configura:

1. **SPF Record** (TXT):
   ```
   v=spf1 include:sendgrid.net ~all
   ```

2. **DKIM** (proporcionado por SendGrid/tu proveedor)

3. **DMARC** (TXT):
   ```
   v=DMARC1; p=none; rua=mailto:admin@tudominio.com
   ```

---

## Checklist Final

Antes de lanzar a producción:

- [ ] Email de prueba enviado exitosamente
- [ ] Email recibido en bandeja (no spam)
- [ ] Datos del cliente guardados correctamente
- [ ] Contraseñas no están en GitHub (usar .env)
- [ ] HTTPS habilitado en el servidor
- [ ] Validación de datos en servidor (PHP)
- [ ] Logs configurados correctamente
- [ ] Copias de seguridad de datos habilitadas

---

## Recursos Adicionales

- **PHPMailer:** https://github.com/PHPMailer/PHPMailer
- **Gmail App Passwords:** https://support.google.com/accounts/answer/185833
- **SendGrid:** https://sendgrid.com/
- **SPF/DKIM/DMARC:** https://mxtoolbox.com/

---

**¿Necesitas más ayuda?**

Revisa el archivo `config.php` y `enviar_email.php` para ejemplos completos.
