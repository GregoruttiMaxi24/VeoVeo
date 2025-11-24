<?php
/**
 * VERIFICADOR DEL SISTEMA - VeoVeo
 * Este archivo verifica que todo está configurado correctamente
 */

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificador del Sistema - VeoVeo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #1a1a1a; color: #fff; padding: 20px; }
        .check-item { padding: 15px; margin: 10px 0; border-radius: 8px; }
        .check-ok { background-color: #28a745; }
        .check-warning { background-color: #ffc107; color: #000; }
        .check-error { background-color: #dc3545; }
        .icon { font-size: 20px; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4"><i class="fas fa-heartbeat"></i> Verificador de Sistema - VeoVeo</h1>

        <div class="row">
            <div class="col-md-6">
                <h3>Verificación de PHP</h3>
                
                <div class="check-item check-ok">
                    <span class="icon">✓</span>
                    <strong>PHP Versión:</strong> <?php echo phpversion(); ?>
                </div>

                <?php if (phpversion() >= 7.0): ?>
                <div class="check-item check-ok">
                    <span class="icon">✓</span>
                    <strong>Versión Compatible:</strong> PHP <?php echo phpversion(); ?> es soportado
                </div>
                <?php else: ?>
                <div class="check-item check-error">
                    <span class="icon">✗</span>
                    <strong>Versión Incompatible:</strong> Se requiere PHP 7.0 o superior
                </div>
                <?php endif; ?>

                <div class="check-item <?php echo extension_loaded('json') ? 'check-ok' : 'check-error'; ?>">
                    <span class="icon"><?php echo extension_loaded('json') ? '✓' : '✗'; ?></span>
                    <strong>Extensión JSON:</strong> <?php echo extension_loaded('json') ? 'Habilitada' : 'No encontrada'; ?>
                </div>

                <div class="check-item <?php echo extension_loaded('curl') ? 'check-ok' : 'check-warning'; ?>">
                    <span class="icon"><?php echo extension_loaded('curl') ? '✓' : '⚠'; ?></span>
                    <strong>Extensión CURL:</strong> <?php echo extension_loaded('curl') ? 'Habilitada' : 'No encontrada (recomendada)'; ?>
                </div>

                <div class="check-item <?php echo ini_get('allow_url_fopen') ? 'check-ok' : 'check-warning'; ?>">
                    <span class="icon"><?php echo ini_get('allow_url_fopen') ? '✓' : '⚠'; ?></span>
                    <strong>URL Fopen:</strong> <?php echo ini_get('allow_url_fopen') ? 'Habilitada' : 'Deshabilitada'; ?>
                </div>
            </div>

            <div class="col-md-6">
                <h3>Verificación de Archivos</h3>

                <?php
                $files = [
                    'index.html' => 'Página Principal',
                    'confirmacion.html' => 'Página de Confirmación',
                    'carrito.js' => 'Lógica del Carrito',
                    'confirmacion.js' => 'Lógica de Confirmación',
                    'enviar_email.php' => 'Envío de Emails',
                    'config.php' => 'Configuración',
                    '.env.example' => 'Ejemplo de Variables',
                    'styles.css' => 'Estilos'
                ];

                foreach ($files as $file => $name) {
                    $exists = file_exists($file);
                    $class = $exists ? 'check-ok' : 'check-error';
                    $icon = $exists ? '✓' : '✗';
                    echo "<div class='check-item $class'><span class='icon'>$icon</span><strong>$name:</strong> $file</div>";
                }
                ?>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <h3>Verificación de Carpetas</h3>

                <?php
                $folders = ['compras' => 'Almacén de Compras', 'logs' => 'Logs', 'img' => 'Imágenes'];

                foreach ($folders as $folder => $name) {
                    if (!is_dir($folder)) {
                        echo "<div class='check-item check-warning'><span class='icon'>⚠</span><strong>$name:</strong> No existe. Crear con: mkdir $folder</div>";
                    } else {
                        $writable = is_writable($folder) ? 'Escribible' : 'No escribible';
                        $class = is_writable($folder) ? 'check-ok' : 'check-warning';
                        echo "<div class='check-item $class'><span class='icon'>✓</span><strong>$name:</strong> Existe - $writable</div>";
                    }
                }
                ?>
            </div>

            <div class="col-md-6">
                <h3>Verificación de Configuración</h3>

                <?php
                if (file_exists('.env')) {
                    echo "<div class='check-item check-ok'><span class='icon'>✓</span><strong>.env Existe</strong></div>";
                } else {
                    echo "<div class='check-item check-warning'><span class='icon'>⚠</span><strong>.env No encontrado:</strong> Copiar .env.example a .env</div>";
                }

                // Verificar que los datos del email están configurados
                if (file_exists('enviar_email.php')) {
                    $content = file_get_contents('enviar_email.php');
                    if (strpos($content, 'tu_email@gmail.com') !== false) {
                        echo "<div class='check-item check-warning'><span class='icon'>⚠</span><strong>Email Sin Configurar:</strong> Edita enviar_email.php</div>";
                    }
                }
                ?>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <h3>Próximos Pasos</h3>
                <div class="alert alert-info">
                    <ol>
                        <li>Crear carpetas: <code>mkdir compras logs</code></li>
                        <li>Copiar archivo: <code>cp .env.example .env</code></li>
                        <li>Editar <code>.env</code> con credenciales de email</li>
                        <li>Editar <code>enviar_email.php</code> con email y contraseña</li>
                        <li>Probar en navegador: <code>http://localhost:8000</code></li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <h3>Información del Servidor</h3>
                <table class="table table-dark">
                    <tr>
                        <td><strong>Sistema Operativo:</strong></td>
                        <td><?php echo PHP_OS; ?></td>
                    </tr>
                    <tr>
                        <td><strong>SAPI:</strong></td>
                        <td><?php echo php_sapi_name(); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Memory Limit:</strong></td>
                        <td><?php echo ini_get('memory_limit'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Max Upload Size:</strong></td>
                        <td><?php echo ini_get('upload_max_filesize'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Post Max Size:</strong></td>
                        <td><?php echo ini_get('post_max_size'); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row mt-4 mb-4">
            <div class="col-12">
                <a href="index.html" class="btn btn-success btn-lg">
                    <i class="fas fa-arrow-right"></i> Ir a index.html
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
