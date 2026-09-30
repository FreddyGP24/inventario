<?php
require_once __DIR__ . '/config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$accion = $_POST['accion'] ?? '';
$pagina = filter_input(INPUT_POST, 'pagina', FILTER_VALIDATE_INT) ?: 1;

if ($id && in_array($accion, ['sumar', 'restar'])) {
    // Definir la consulta dependiendo de la acción y evitar que baje de 0 o pase de 999
    if ($accion === 'sumar') {
        $sql = 'UPDATE productos SET cantidad = cantidad + 1 WHERE id = :id AND cantidad < 999';
    } else {
        $sql = 'UPDATE productos SET cantidad = cantidad - 1 WHERE id = :id AND cantidad > 0';
    }

    $sentencia = $conexion->prepare($sql);
    $sentencia->bindValue(':id', $id, PDO::PARAM_INT);
    $sentencia->execute();
}

// Redirigir de vuelta a la página correcta
header('Location: index.php?pagina=' . $pagina);
exit;