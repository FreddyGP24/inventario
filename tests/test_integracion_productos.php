<?php
$conexion = null;
$codigoSalida = 0;
try {
    require __DIR__ . '/conexion_pruebas.php';
    echo "Conectado: Se conectó a la base inventarioPruebas." . PHP_EOL;
    $conexion->beginTransaction();
    $nombre = 'Prueba';
    $cantidad = 5;
    $consulta = $conexion->prepare(
        'INSERT INTO productos (nombre, cantidad)
         VALUES (:nombre, :cantidad)'
    );
    $consulta->execute([
        'nombre' => $nombre,
        'cantidad' => $cantidad
    ]);
    $id = $conexion->lastInsertId();
    echo "Correcto: inserción realizada. ID: $id" . PHP_EOL;
    
    
    $consulta = $conexion->prepare(
    'SELECT nombre, cantidad
     FROM productos
     WHERE id = :id'
    );

    $consulta->execute(['id' => $id]);
    $producto = $consulta->fetch();

    if (
        $producto !== false
        && $producto['nombre'] === $nombre
        && (int) $producto['cantidad'] === $cantidad
    ) {
    echo "CORRECTO: nombre y cantidad coinciden." . PHP_EOL;
    } else {
        echo "ERROR: producto no encontrado o datos diferentes."
            . PHP_EOL;
        $codigoSalida = 1;
    }
} catch (PDOException $error) {
    echo "ERROR: " . $error->getMessage() . PHP_EOL;
    $codigoSalida = 1;
} finally {
    if ($conexion !== null && $conexion->inTransaction()) {
        $conexion->rollBack();
        echo "Limpieza: inserción eliminada." . PHP_EOL;
    }
}
exit($codigoSalida);
