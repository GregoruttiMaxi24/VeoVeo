# 🔧 SOLUCIÓN: Error "Unexpected token '<'"

## ¿Qué significa este error?

El servidor está devolviendo **HTML en lugar de JSON**. Esto suele ocurrir cuando:
- ❌ PHP tiene un error de sintaxis
- ❌ PHP no está corriendo
- ❌ Hay un error en la base de datos
- ❌ Hay un error de permisos

---

## 🧪 Paso 1: Probar PHP

1. Abre tu navegador y ve a:
   ```
   http://localhost:8000/test_php.php
   ```

2. Deberías ver algo como:
   ```json
   {
     "version": "8.0.0",
     "sapi": "cli-server",
     "carpetas": "OK",
     "mail_disponible": "SI",
     "json": {"prueba": "OK"}
   }
   ```

3. Si no ves esto, hay un problema con PHP.

---

## 🔍 Paso 2: Revisar el Error Real

1. Abre la consola del navegador (F12)
2. Ve a la pestaña "Network"
3. Haz clic en "Comprar" y luego "Proceder a Compra"
4. Busca la solicitud a `enviar_email.php`
5. Haz clic en ella y mira la pestaña "Response"
6. Verás el HTML del error

---

## ✅ Paso 3: Verificar el Archivo PHP

El archivo `enviar_email.php` ha sido actualizado. Verifica que:

1. La primera línea sea exactamente:
   ```php
   <?php
   ```
   (SIN ESPACIOS ANTES)

2. Las primeras líneas sean:
   ```php
   header('Content-Type: application/json; charset=utf-8');
   header('Access-Control-Allow-Origin: *');
   ```

3. Que NO haya HTML antes del `<?php`

---

## 🛠️ Paso 4: Crear Carpetas Necesarias

En PowerShell (Windows):
```powershell
mkdir compras
mkdir logs
```

O en Terminal (Linux/Mac):
```bash
mkdir compras logs
chmod 755 compras logs
```

---

## 💾 Paso 5: Verificar Permisos

El servidor web necesita permiso para:
1. Leer `enviar_email.php`
2. Escribir en la carpeta `compras/`

En Windows (normalmente está OK)
En Linux/Mac:
```bash
chmod 755 enviar_email.php
chmod 755 compras
chmod 755 logs
```

---

## 🔌 Paso 6: Verificar que PHP Pueda Enviar Emails

En Windows, sin configuración especial, `mail()` NO funcionará.

### Opción A: Usar un servicio externo

Edita `index.html` y busca la función `confirmarCompra()`:

```javascript
const response = await fetch('enviar_email.php', {
```

Puedes cambiar esto para usar un servicio como:
- **Mailgun API**
- **SendGrid API**
- **EmailJS**

### Opción B: Usar un servidor Linux/Mac

En Linux, instala:
```bash
sudo apt-get install postfix
```

### Opción C: Usar XAMPP con Postfix (Windows)

Descarga XAMPP que incluye un servidor SMTP simulado.

---

## 📝 Paso 7: Solución Rápida (Para Probar)

Si solo quieres probar sin enviar emails reales:

1. Modifica `index.html`
2. En la función `confirmarCompra()`, busca:
   ```javascript
   const response = await fetch('enviar_email.php', {
   ```

3. Reemplaza por:
   ```javascript
   // Para pruebas: simulamos la respuesta
   const resultado = {exito: true, mensaje: 'Email enviado (simulado)'};
   
   // Comentar el fetch real:
   /*
   const response = await fetch('enviar_email.php', {
   ```

4. Al final de la función, busca:
   ```javascript
   const resultado = await response.json();
   ```

5. Reemplaza por:
   ```javascript
   // ya está definido arriba
   ```

---

## 🚀 Paso 8: Configuración Correcta (Recomendado)

Para que funcione de verdad, usa **Gmail SMTP**:

### A. Habilita en Gmail:
1. Ve a: https://myaccount.google.com/apppasswords
2. Genera una contraseña de app
3. Cópiala

### B. Instala PHPMailer:
```bash
composer require phpmailer/phpmailer
```

### C. Usa `enviar_email_advanced.php`:
Cambia en `index.html`:
```javascript
const response = await fetch('enviar_email_advanced.php', {
```

### D. Configura credenciales:
En `enviar_email_advanced.php`, línea ~30:
```php
$smtp_host = 'smtp.gmail.com';
$smtp_port = 587;
$smtp_user = 'tu_email@gmail.com';
$smtp_password = 'contraseña_app';
```

---

## ❓ FAQ

**P: ¿Por qué aparece el error?**
R: PHP está devolviendo un error HTML. Necesitas ver exactamente qué dice.

**P: ¿Cómo veo el error real?**
R: Abre F12 → Network → Haz clic en enviar_email.php → Response

**P: ¿Funciona sin emails?**
R: Sí, puedes simular la respuesta para probar.

**P: ¿Cómo lo hago funcionar?**
R: Instala XAMPP o usa Gmail SMTP con PHPMailer.

---

## 📋 Checklist de Solución

- [ ] Ejecuté `test_php.php` y funcionó
- [ ] Creé carpetas `compras/` y `logs/`
- [ ] Verifiqué permisos de carpetas
- [ ] Revisé el error real en F12
- [ ] Decidí usar simulado o Gmail SMTP
- [ ] Configuré las credenciales correctas
- [ ] Ahora funciona!

---

## 🆘 Si Nada Funciona

1. Copia el contenido exacto del error en F12
2. Verifica que `enviar_email.php` comience con `<?php` sin espacios
3. Verifica que NO hay HTML antes del PHP
4. Intenta usar la versión simulada primero
5. Luego configura Gmail SMTP

**¡El sistema está bien, solo necesitas configurar correctamente el envío de emails!** ✅
