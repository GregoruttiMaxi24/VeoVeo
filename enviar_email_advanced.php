<?php
/**
 * OPCIÓN ALTERNATIVA: Envío de emails con PHPMailer
 * Esta versión es más segura y confiable que usar mail()
 * 
 * INSTALACIÓN:
 * composer require phpmailer/phpmailer
 * 
 * O descarga manualmente desde: https://github.com/PHPMailer/PHPMailer
 */

header('Content-Type: application/json');

// Obtener datos JSON
$data = json_decode(file_get_contents('php://input'), true);

// Validar datos
if (!isset($data['email']) || !isset($data['nombre']) || !isset($data['carrito'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Datos incompletos']);
    exit;
}

$nombre = sanitizar($data['nombre']);
$email = sanitizar($data['email']);
$telefono = isset($data['telefono']) ? sanitizar($data['telefono']) : 'No proporcionado';
$direccion = sanitizar($data['direccion']);
$carrito = $data['carrito'];
$total = isset($data['total']) ? $data['total'] : 0;

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['exito' => false, 'mensaje' => 'Email inválido']);
    exit;
}

// CONFIGURACIÓN DE EMAIL

// Opción 1: Gmail SMTP
$smtp_host = 'smtp.gmail.com';
$smtp_port = 587;
$smtp_user = 'tu_email@gmail.com'; // CAMBIAR
$smtp_password = 'tu_contraseña_app'; // CAMBIAR (usar contraseña de app, no la contraseña normal)
$email_from = 'tu_email@gmail.com'; // CAMBIAR

// Opción 2: SendGrid
// $sendgrid_api_key = 'SG.xxxxxxxxxxxxx'; // CAMBIAR

// Opción 3: Servidor local Postfix/Exim
// Usar las variables por defecto de PHP

try {
    // Si tienes PHPMailer instalado, usar esto:
    if (file_exists('vendor/autoload.php')) {
        require 'vendor/autoload.php';
        $mail = enviarConPHPMailer($nombre, $email, $telefono, $direccion, $carrito, $total, $smtp_host, $smtp_port, $smtp_user, $smtp_password, $email_from);
    } else {
        // Fallback a mail() nativa
        $mail = enviarConMailNativa($nombre, $email, $telefono, $direccion, $carrito, $total);
    }

    if ($mail) {
        guardarCompra($nombre, $email, $carrito, $total, $direccion);
        echo json_encode(['exito' => true, 'mensaje' => 'Compra confirmada']);
    } else {
        echo json_encode(['exito' => false, 'mensaje' => 'Error al enviar el email']);
    }
} catch (Exception $e) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
}

function enviarConPHPMailer($nombre, $email, $telefono, $direccion, $carrito, $total, $host, $port, $user, $pass, $from) {
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = $host;
    $mail->SMTPAuth = true;
    $mail->Username = $user;
    $mail->Password = $pass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $port;

    $mail->setFrom($from, 'VeoVeo');
    $mail->addAddress($email, $nombre);
    $mail->addCC('admin@veoveo.com');

    $mail->isHTML(true);
    $mail->Subject = 'Confirmación de Compra - VeoVeo';
    $mail->Body = generarBodyEmail($nombre, $email, $telefono, $direccion, $carrito, $total);
    $mail->AltBody = generarBodyTexto($nombre, $carrito, $total);

    return $mail->send();
}

function enviarConMailNativa($nombre, $email, $telefono, $direccion, $carrito, $total) {
    $asunto = "Confirmación de Compra - VeoVeo";
    $cuerpo = generarBodyEmail($nombre, $email, $telefono, $direccion, $carrito, $total);

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: ventas@veoveo.com\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";

    mail("admin@veoveo.com", "Nuevo Pedido - $nombre", $cuerpo, $headers);
    return mail($email, $asunto, $cuerpo, $headers);
}

function generarBodyEmail($nombre, $email, $telefono, $direccion, $carrito, $total) {
    $carrito_html = '';
    foreach ($carrito as $item) {
        $subtotal = $item['precio'] * $item['cantidad'];
        $carrito_html .= "
            <tr>
                <td style='padding: 10px; border-bottom: 1px solid #ddd;'>{$item['nombre']}</td>
                <td style='padding: 10px; border-bottom: 1px solid #ddd; text-align: right;'>\${$item['precio']}</td>
                <td style='padding: 10px; border-bottom: 1px solid #ddd; text-align: right;'>{$item['cantidad']}</td>
                <td style='padding: 10px; border-bottom: 1px solid #ddd; text-align: right;'>\$" . number_format($subtotal, 2) . "</td>
            </tr>";
    }

    return "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #3483d6 0%, #2670c4 100%); color: white; padding: 30px; border-radius: 8px 8px 0 0; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .header p { margin: 10px 0 0 0; opacity: 0.9; }
        .contenido { padding: 30px; }
        .section-title { font-size: 18px; font-weight: bold; color: #333; margin-top: 30px; margin-bottom: 15px; border-bottom: 2px solid #3483d6; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #3483d6; color: white; padding: 12px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        .total-row { background-color: #f0f0f0; font-weight: bold; font-size: 16px; }
        .datos-cliente { background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin-top: 15px; }
        .datos-cliente p { margin: 8px 0; }
        .datos-cliente strong { color: #3483d6; }
        .footer { background-color: #f5f5f5; padding: 20px; text-align: center; color: #666; font-size: 12px; border-top: 1px solid #ddd; margin-top: 30px; border-radius: 0 0 8px 8px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>¡Compra Confirmada!</h1>
            <p>Gracias por tu compra en VeoVeo</p>
        </div>
        
        <div class='contenido'>
            <p>Hola <strong>$nombre</strong>,</p>
            <p>Tu compra ha sido procesada correctamente. Aquí está el resumen detallado de tu pedido:</p>
            
            <div class='section-title'>Productos Comprados</div>
            <table>
                <tr>
                    <th>Producto</th>
                    <th style='text-align: right;'>Precio</th>
                    <th style='text-align: right;'>Cantidad</th>
                    <th style='text-align: right;'>Subtotal</th>
                </tr>
                $carrito_html
                <tr class='total-row'>
                    <td colspan='3' style='text-align: right;'>TOTAL A PAGAR:</td>
                    <td style='text-align: right;'>\$$total</td>
                </tr>
            </table>
            
            <div class='section-title'>Datos de Envío</div>
            <div class='datos-cliente'>
                <p><strong>Nombre:</strong> $nombre</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Teléfono:</strong> $telefono</p>
                <p><strong>Dirección:</strong> $direccion</p>
            </div>
            
            <p style='margin-top: 30px; line-height: 1.8; color: #555;'>
                Nos pondremos en contacto contigo pronto con los detalles del envío. 
                <br><br>
                Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos respondiendo a este email.
                <br><br>
                ¡Gracias por elegir VeoVeo!
            </p>
        </div>
        
        <div class='footer'>
            <p>&copy; 2025 VeoVeo Store. Todos los derechos reservados.</p>
            <p>Este es un email de confirmación automático. Por favor no respondas con información sensible.</p>
        </div>
    </div>
</body>
</html>";
}

function generarBodyTexto($nombre, $carrito, $total) {
    $texto = "Confirmación de Compra - VeoVeo\n\n";
    $texto .= "Hola $nombre,\n\n";
    $texto .= "Tu compra ha sido confirmada. Aquí está el resumen:\n\n";

    foreach ($carrito as $item) {
        $subtotal = $item['precio'] * $item['cantidad'];
        $texto .= "{$item['nombre']} - ${$item['precio']} x {$item['cantidad']} = \$" . number_format($subtotal, 2) . "\n";
    }

    $texto .= "\nTOTAL: \$$total\n\n";
    $texto .= "Pronto recibirás los detalles del envío.\n";
    $texto .= "¡Gracias por tu compra!\n";

    return $texto;
}

function sanitizar($texto) {
    return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
}

function guardarCompra($nombre, $email, $carrito, $total, $direccion) {
    if (!is_dir('compras')) {
        mkdir('compras', 0755, true);
    }

    $timestamp = date('Y-m-d H:i:s');
    $id_compra = date('YmdHis') . '_' . uniqid();

    $datos_compra = [
        'id_compra' => $id_compra,
        'timestamp' => $timestamp,
        'nombre' => $nombre,
        'email' => $email,
        'direccion' => $direccion,
        'carrito' => $carrito,
        'total' => $total,
        'estado' => 'pendiente'
    ];

    $archivo = 'compras/compra_' . $id_compra . '.json';
    file_put_contents($archivo, json_encode($datos_compra, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    chmod($archivo, 0644);
}
?>
