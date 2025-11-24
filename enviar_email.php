<?php
// Asegurar que solo se devuelva JSON
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Desactivar display de errores y usarlos internamente
ini_set('display_errors', 0);
error_reporting(E_ALL);

try {
    // Obtener datos JSON
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validar que se recibieron datos
    if (!$data) {
        throw new Exception('No se recibieron datos válidos');
    }

    // Validar datos requeridos
    if (empty($data['email']) || empty($data['nombre']) || empty($data['carrito'])) {
        throw new Exception('Datos incompletos');
    }

    $nombre = sanitizar($data['nombre']);
    $email = sanitizar($data['email']);
    $telefono = !empty($data['telefono']) ? sanitizar($data['telefono']) : 'No proporcionado';
    $direccion = sanitizar($data['direccion']);
    $carrito = $data['carrito'];
    $total = isset($data['total']) ? $data['total'] : 0;

    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Email inválido');
    }

    // Construir tabla de productos
    $tabla_productos = '';
    foreach ($carrito as $item) {
        $subtotal = $item['precio'] * $item['cantidad'];
        $tabla_productos .= "
            <tr>
                <td style='padding: 10px; border-bottom: 1px solid #ddd;'>{$item['nombre']}</td>
                <td style='padding: 10px; border-bottom: 1px solid #ddd; text-align: right;'>\${$item['precio']}</td>
                <td style='padding: 10px; border-bottom: 1px solid #ddd; text-align: right;'>{$item['cantidad']}</td>
                <td style='padding: 10px; border-bottom: 1px solid #ddd; text-align: right;'>\$" . number_format($subtotal, 2) . "</td>
            </tr>";
    }

    // Construir el contenido del email
    $asunto = "Confirmación de Compra - VeoVeo";

    $cuerpo = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: white; border-radius: 8px; }
        .header { background-color: #3483d6; color: white; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .contenido { padding: 20px; }
        .table-container { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #3483d6; color: white; padding: 12px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        .total-row { background-color: #f0f0f0; font-weight: bold; font-size: 16px; }
        .datos-cliente { background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin-top: 20px; }
        .footer { background-color: #f5f5f5; padding: 20px; text-align: center; color: #666; font-size: 12px; border-top: 1px solid #ddd; margin-top: 30px; }
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
            <p>Tu compra ha sido procesada correctamente. Aquí está el resumen de tu pedido:</p>
            
            <div class='table-container'>
                <h3>Productos Comprados:</h3>
                <table>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                    $tabla_productos
                    <tr class='total-row'>
                        <td colspan='3' style='text-align: right;'>TOTAL</td>
                        <td style='text-align: right;'>\$$total</td>
                    </tr>
                </table>
            </div>
            
            <div class='datos-cliente'>
                <h3>Datos de Envío:</h3>
                <p><strong>Nombre:</strong> $nombre</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Teléfono:</strong> $telefono</p>
                <p><strong>Dirección:</strong> $direccion</p>
            </div>
            
            <p style='margin-top: 20px; color: #666; line-height: 1.6;'>
                Nos pondremos en contacto contigo pronto con los detalles del envío. 
                Si tienes alguna pregunta, no dudes en contactarnos.
            </p>
        </div>
        
        <div class='footer'>
            <p>&copy; 2025 VeoVeo. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>";

    // Headers del email
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: ventas@veoveo.com\r\n";

    // EMAIL DE DESTINO - AQUÍ CAMBIAS A DONDE QUIERAS RECIBIR LAS COMPRAS
    $email_destino = 'maxigregorutti24@gmail.com';
    
    // Enviar email al administrador
    $mail_enviado = mail($email_destino, "Nueva Compra de $nombre", $cuerpo, $headers);

    // Guardar registro de compra
    guardarCompra($nombre, $email, $carrito, $total, $direccion);

    // Responder con éxito
    echo json_encode(['exito' => true, 'mensaje' => 'Compra confirmada']);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['exito' => false, 'mensaje' => $e->getMessage()]);
}

function sanitizar($texto) {
    return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
}

function guardarCompra($nombre, $email, $carrito, $total, $direccion) {
    // Crear carpeta si no existe
    $carpeta = 'compras';
    if (!is_dir($carpeta)) {
        @mkdir($carpeta, 0755, true);
    }

    // Crear datos de compra
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

    // Guardar en archivo JSON
    $archivo = $carpeta . '/compra_' . $id_compra . '.json';
    file_put_contents($archivo, json_encode($datos_compra, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    @chmod($archivo, 0644);
}
?>
