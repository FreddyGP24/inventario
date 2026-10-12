
<?php

require_once __DIR__ . '/config/conexion.php';

// Función para validar el nombre del producto
function validarNombre($nombre) {
    $nombre = trim($nombre);

    // Contar solamente letras, incluyendo acentos y ñ
    preg_match_all('/\p{L}/u', $nombre, $coincidencias);

    return count($coincidencias[0]) >= 3
        && mb_strlen($nombre, 'UTF-8') <= 50;
}

// Función para validar la cantidad
function validarCantidad($cantidad) {
    $numero = filter_var($cantidad, FILTER_VALIDATE_INT);

    return $numero !== false
        && $numero > 0
        && $numero <= 999;
}

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Obtener los datos
$nombre = trim($_POST['nombre'] ?? '');
$cantidad = $_POST['cantidad'] ?? '';

// Verificar campos vacíos
if ($nombre === '' || $cantidad === '') {
    header('Location: index.php?estado=incompleto');
    exit;
}

// Validar nombre mediante función
if (!validarNombre($nombre)) {
    header('Location: index.php?estado=nombre_invalido');
    exit;
}

// Validar cantidad mediante función
if (!validarCantidad($cantidad)) {
    header('Location: index.php?estado=cantidad_invalida');
    exit;
}

// Guardar únicamente si ambas validaciones son correctas
$sentencia = $conexion->prepare(
    'INSERT INTO productos (nombre, cantidad)
     VALUES (:nombre, :cantidad)'
);

$sentencia->execute([
    'nombre' => $nombre,
    'cantidad' => (int) $cantidad
]);

header('Location: index.php?estado=guardado');
exit;
