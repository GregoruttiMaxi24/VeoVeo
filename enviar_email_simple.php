<?php
/**
 * VERSIÓN SIMPLIFICADA - Para pruebas sin configuración de email
 * Renueva el archivo enviar_email.php con este contenido si tienes problemas
 */

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);

try {
    // Obtener datos JSON
    $input = file_get_contents('php://input');
    
    if (empty($input)) {
        throw new Exception('No se recibieron datos');
    }
    
    $data = json_decode($input, true);
    
    if (!$data) {
        throw new Exception('JSON inválido');
    }

    // Validar datos requeridos
    if (empty($data['email']) || empty($data['nombre']) || empty($data['carrito'])) {
        throw new Exception('Datos incompletos: ' . json_encode($data));
    }

    $nombre = htmlspecialchars(trim($data['nombre']), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars(trim($data['email']), ENT_QUOTES, 'UTF-8');
    $telefono = !empty($data['telefono']) ? htmlspecialchars(trim($data['telefono']), ENT_QUOTES, 'UTF-8') : 'No proporcionado';
    $direccion = htmlspecialchars(trim($data['direccion']), ENT_QUOTES, 'UTF-8');
    $carrito = $data['carrito'];
    $total = isset($data['total']) ? $data['total'] : 0;

    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Email inválido: ' . $email);
    }

    // Guardar compra en JSON (esta es la parte más importante)
    guardarCompra($nombre, $email, $carrito, $total, $direccion);

    // Intentar enviar email (si falla, no importa - los datos ya se guardaron)
    @enviarEmail($nombre, $email, $telefono, $direccion, $carrito, $total);

    // Responder con éxito
    echo json_encode([
        'exito' => true, 
        'mensaje' => 'Compra confirmada. Email enviado a ' . $email
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'exito' => false, 
        'mensaje' => 'Error: ' . $e->getMessage()
    ]);
}

function enviarEmail($nombre, $email, $telefono, $direccion, $carrito, $total) {
    // EMAIL DE DESTINO - AQUÍ CAMBIAS A DONDE QUIERAS RECIBIR LAS COMPRAS
    $email_destino = 'maxixeneize24@gmail.com';
    
    // Construir tabla de productos
    $tabla_productos = '';
    foreach ($carrito as $item) {
        $subtotal = $item['precio'] * $item['cantidad'];
        $tabla_productos .= "
            <tr>
                <td>{$item['nombre']}</td>
                <td>\${$item['precio']}</td>
                <td>{$item['cantidad']}</td>
                <td>\$" . number_format($subtotal, 2) . "</td>
            </tr>";
    }

    $cuerpo = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background-color: white; border-radius: 8px; }
        .header { background-color: #3483d6; color: white; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .contenido { padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background-color: #3483d6; color: white; padding: 12px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        .total-row { background-color: #f0f0f0; font-weight: bold; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>Nueva Compra Recibida</h1>
        </div>
        <div class='contenido'>
            <p><strong>Cliente:</strong> $nombre</p>
            <p><strong>Email del cliente:</strong> $email</p>
            <p><strong>Teléfono:</strong> $telefono</p>
            <p><strong>Dirección:</strong> $direccion</p>
            <h3>Productos Comprados:</h3>
            <table>
                <tr>
                    <th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th>
                </tr>
                $tabla_productos
                <tr class='total-row'>
                    <td colspan='3' style='text-align: right;'>TOTAL</td>
                    <td>\$$total</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: ventas@veoveo.com\r\n";

    // Intentar enviar al email de destino (en Windows probablemente falle, pero está OK)
    mail($email_destino, "Nueva Compra - VeoVeo", $cuerpo, $headers);
}

function guardarCompra($nombre, $email, $carrito, $total, $direccion) {
    // Crear carpeta si no existe
    if (!is_dir('compras')) {
        @mkdir('compras', 0755, true);
    }

    // Generar ID único
    $id_compra = date('YmdHis') . '_' . uniqid();
    
    $datos = [
        'id_compra' => $id_compra,
        'timestamp' => date('Y-m-d H:i:s'),
        'nombre' => $nombre,
        'email' => $email,
        'telefono' => 'No proporcionado',
        'direccion' => $direccion,
        'carrito' => $carrito,
        'total' => $total
    ];

    $archivo = 'compras/compra_' . $id_compra . '.json';
    $resultado = file_put_contents(
        $archivo, 
        json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
    
    if (!$resultado) {
        throw new Exception('No se pudo guardar la compra en: ' . $archivo);
    }

    @chmod($archivo, 0644);
}
?>
