<?php
require_once __DIR__ . '/../src/validaciones.php';

$casos = [
    ["Nombre valido: Teclado", "Teclado", True],
    ["Nombre vacio", "", false],
    ["Nombre con espacios", "   ", false],
    ["Nombre valido: Mouse", "Mouse", true]
];

$correctas = 0;
$fallidas = 0;

foreach ($casos as [$descripcion, $entrada, $esperado]) {
    $obtenido = validarNombreProducto($entrada);

    if ($obtenido === $esperado) {
        echo "Prueba correcta: $descripcion\n";
        $correctas++;
    } else {
        echo "Prueba fallida: $descripcion\n";
        echo "Esperado: " . var_export($esperado, true) . "\n";
        echo "Obtenido: " . var_export($obtenido, true) . "\n";
        $fallidas++;
    }
}

echo "\n--- RESUMEN ---\n";
echo "Total: " . count($casos) . "\n";
echo "Correctas:  $correctas\n";
echo "Fallidas: $fallidas\n";

exit($fallidas > 0 ? 1 : 0);