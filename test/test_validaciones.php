<?php

require_once __DIR__ . '/../src/validaciones.php';



$casos = [
    ["Nombre valido = Teclado","Teclado",true],
    ["Nombre valido = Vacío","",false],
    ["Nombre valido = con espacios","     ",false],
    ["Nombre valido = Mouse","Mouse",true]
];

$casos2 = [
    ["Cantidad valida = 3","3",true],
    ["Cantidad valida = 0","0",false],
    ["Cantidad valida = -1","-1",false],
    ["Cantidad valida = 3.5","3.5",false]
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

foreach ($casos2 as [$descripcion2, $entrada2, $esperado2]) {
    $obtenido2 = validarCantidadProducto($entrada2);
    if ($obtenido2 === $esperado2) {
        echo " - descripcion\n"; 
    $correctas++;
    } else {

    echo "FAIL - $descripcion2\n";
    echo " Esperando: " .var_export($esperando2, true) . "\n";
    echo " Obtenido: " .var_export($obtenido2, true) . "\n";
    $fallidas++;
    }
}

echo "\n --- RESUMEN ---\n";
$total= count($casos) + count($casos2);
echo "Total ".$total."\n";
echo "Correctas: $correctas\n";
echo "Fallidas: $fallidas\n";
