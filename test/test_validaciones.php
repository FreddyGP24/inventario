<?php

require_once __DIR__ . '/../src/validaciones.php';

$casos = [
    ["Nombre valido = Teclado","Teclado",true],
    ["Nombre valido = Vacío","",false],
    ["Nombre valido = con espacios","     ",false],
    ["Nombre valido = Mouse","Mouse",true]
];

$correctas =0;
$fallidas = 0;

foreach ($casos as [$descripcion, $entrada, $esperado]) {
    $obtenido = validarNombreProducto($entrada);
    if ($obtenido === $esperado) {
        echo " - descripcion\n";
    $correctas++;
    } else {

    echo "FAIL - $descripcion\n";
    echo " Esperando: " .var_export($esperando, true) . "\n";
    echo " Obtenido: " .var_export($obtenido, true) . "\n";
    $fallidas++;
    }
}

echo "\n --- RESUMEN ---\n";
echo "Total ".count($casos) ."\n";
echo "Correctas: $correctas\n";
echo "Fallidas: $fallidas\n";