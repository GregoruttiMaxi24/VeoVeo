<?php
// Archivo para probar si PHP funciona correctamente
header('Content-Type: application/json; charset=utf-8');

// Test 1: Verificar PHP
$test_php = [
    'version' => phpversion(),
    'sapi' => php_sapi_name(),
    'directorio' => getcwd()
];

// Test 2: Verificar si se puede crear carpetas
$carpeta_test = 'test_carpeta_' . time();
$carpeta_creada = @mkdir($carpeta_test);
if ($carpeta_creada) {
    @rmdir($carpeta_test);
    $test_php['carpetas'] = 'OK';
} else {
    $test_php['carpetas'] = 'ERROR - No se pueden crear carpetas';
}

// Test 3: Verificar función mail
$test_php['mail_disponible'] = function_exists('mail') ? 'SI' : 'NO';

// Test 4: Verificar json_encode/decode
$test_json = json_encode(['prueba' => 'OK']);
$test_php['json'] = json_decode($test_json, true);

echo json_encode($test_php, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
