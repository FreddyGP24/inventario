<?php

$servidor = 'localhost';
$baseDatos = 'inventario';
$usuario = 'root';
$contrasena = '';

try {
    $conexion = new PDO(
        "mysql:host=$servidor;dbname=$baseDatos;charset=utf8mb4",
        $usuario,
        $contrasena
    );

    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );
} catch (PDOException $error) {
    die(
        'No fue posible conectarse con la base de datos: '
        . $error->getMessage()
    );
}