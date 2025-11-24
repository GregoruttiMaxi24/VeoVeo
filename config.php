<?php
/**
 * CONFIGURACIÓN DE VEOVEO
 * Archivo de configuración central para el sistema
 */

// ============================================
// CONFIGURACIÓN DE EMAILS
// ============================================

// OPCIÓN 1: USAR GMAIL
define('EMAIL_SERVICE', 'gmail'); // O 'smtp', 'sendgrid', 'phpmail'
define('GMAIL_ADDRESS', 'tu_email@gmail.com');
define('GMAIL_PASSWORD', 'tu_contraseña_app'); // Generar en myaccount.google.com/apppasswords

// OPCIÓN 2: USAR SMTP PERSONALIZADO
define('SMTP_HOST', 'smtp.tudominio.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'usuario@tudominio.com');
define('SMTP_PASSWORD', 'contraseña');

// OPCIÓN 3: USAR SENDGRID
define('SENDGRID_API_KEY', 'SG.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

// EMAIL DEL ADMINISTRADOR (donde se envían las copias)
define('ADMIN_EMAIL', 'admin@veoveo.com');

// NOMBRE DE LA TIENDA
define('STORE_NAME', 'VeoVeo');
define('STORE_EMAIL', 'ventas@veoveo.com');

// ============================================
// CONFIGURACIÓN GENERAL
// ============================================

define('DEBUG', true); // Cambiar a false en producción
define('BASE_URL', 'http://localhost/veoveo/');

// Zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

// ============================================
// CONFIGURACIÓN DE BASE DE DATOS (opcional)
// ============================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'veoveo_db');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

// ============================================
// RUTAS
// ============================================

define('PATH_COMPRAS', __DIR__ . '/compras/');
define('PATH_LOGS', __DIR__ . '/logs/');
define('PATH_UPLOADS', __DIR__ . '/uploads/');

// Crear carpetas si no existen
@mkdir(PATH_COMPRAS, 0755, true);
@mkdir(PATH_LOGS, 0755, true);
@mkdir(PATH_UPLOADS, 0755, true);

// ============================================
// FUNCIONES AUXILIARES
// ============================================

/**
 * Registrar actividad en logs
 */
function registrarLog($mensaje, $tipo = 'info') {
    $timestamp = date('Y-m-d H:i:s');
    $contenido = "[$timestamp] [$tipo] $mensaje\n";
    file_put_contents(PATH_LOGS . 'veoveo_' . date('Y-m-d') . '.log', $contenido, FILE_APPEND);
}

/**
 * Obtener configuración de email basada en servicio
 */
function obtenerConfigEmail() {
    $config = [];

    switch (EMAIL_SERVICE) {
        case 'gmail':
            $config = [
                'host' => 'smtp.gmail.com',
                'port' => 587,
                'secure' => 'tls',
                'username' => GMAIL_ADDRESS,
                'password' => GMAIL_PASSWORD,
                'from' => GMAIL_ADDRESS
            ];
            break;

        case 'smtp':
            $config = [
                'host' => SMTP_HOST,
                'port' => SMTP_PORT,
                'secure' => 'tls',
                'username' => SMTP_USER,
                'password' => SMTP_PASSWORD,
                'from' => SMTP_USER
            ];
            break;

        case 'sendgrid':
            $config = [
                'api_key' => SENDGRID_API_KEY,
                'from' => STORE_EMAIL
            ];
            break;

        default:
            $config = [
                'method' => 'mail'
            ];
    }

    return $config;
}

// Registrar inicio de sesión
if (DEBUG) {
    registrarLog('Sistema inicializado - Configuración cargada');
}
?>
