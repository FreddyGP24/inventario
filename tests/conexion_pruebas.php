<?php

$servidor = getenv('DB_HOST') ?: 'localhost';
$baseDatos = getenv('DB_NAME') ?: 'inventario_test';
$usuario = getenv('DB_USER') ?: 'root';

$contrasena = getenv('DB_PASSWORD');

if ($contrasena === false) {
    $contrasena = '';
}

$conexion = new PDO(
    "mysql:host=$servidor;dbname=$baseDatos;charset=utf8mb4",
    $usuario,
    $contrasena,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);