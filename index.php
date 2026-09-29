<?php

require_once __DIR__ . '/config/conexion.php';

// Mostrar como máximo 7 productos por página.
$productosPorPagina = 7;

// Obtener y validar el número de página.
$paginaSolicitada = filter_input(
    INPUT_GET,
    'pagina',
    FILTER_VALIDATE_INT,
    [
        'options' => [
            'min_range' => 1
        ]
    ]
);

$paginaActual = $paginaSolicitada ?: 1;

// Obtener el total de productos de la base de datos.
$consultaTotal = $conexion->query(
    'SELECT COUNT(*) FROM productos'
);

$totalProductos = (int) $consultaTotal->fetchColumn();

// Mantener al menos una página, incluso sin productos.
$totalPaginas = max(
    1,
    (int) ceil($totalProductos / $productosPorPagina)
);

// Evitar consultar una página que no existe.
$paginaActual = min($paginaActual, $totalPaginas);

// Calcular desde qué registro comienza la página actual.
$desplazamiento = ($paginaActual - 1) * $productosPorPagina;

// Consultar solamente los productos de la página actual.
$consulta = $conexion->prepare(
    'SELECT
        id,
        nombre,
        cantidad,
        fecharegistro
    FROM productos
    ORDER BY id ASC
    LIMIT :limite OFFSET :desplazamiento'
);

$consulta->bindValue(
    ':limite',
    $productosPorPagina,
    PDO::PARAM_INT
);

$consulta->bindValue(
    ':desplazamiento',
    $desplazamiento,
    PDO::PARAM_INT
);

$consulta->execute();

$productos = $consulta->fetchAll(PDO::FETCH_ASSOC);

// Calcular el rango de productos que se está mostrando.
$productosEnPagina = count($productos);

$primerProducto = $productosEnPagina > 0
    ? $desplazamiento + 1
    : 0;

$ultimoProducto = $productosEnPagina > 0
    ? $desplazamiento + $productosEnPagina
    : 0;

// Mostrar hasta 5 números de página a la vez.
$primeraPaginaVisible = max(1, $paginaActual - 2);

$ultimaPaginaVisible = min(
    $totalPaginas,
    $primeraPaginaVisible + 4
);

$primeraPaginaVisible = max(
    1,
    $ultimaPaginaVisible - 4
);

// Estado del registro de productos.
$estado = $_GET['estado'] ?? '';

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>INVENTARIO</title>

    <link rel="stylesheet" href="css/estilos.css?v=3">
</head>

<body>
    <header class="encabezado">
        <div class="contenido-encabezado">
            <p class="etiqueta">GPDS</p>

            <h1>INVENTARIO</h1>

            <p>Registro y consulta de productos</p>
        </div>
    </header>

    <main class="contenedor">
        <!-- Formulario para registrar productos -->
        <section class="tarjeta formulario">
            <h2>Registrar producto</h2>

            <p class="descripcion">
                Escriba el nombre del producto y su cantidad.
            </p>

            <?php if ($estado === 'guardado'): ?>
                <div class="mensaje correcto" role="status">
                    Producto registrado correctamente.
                </div>
            <?php endif; ?>

            <?php if ($estado === 'incompleto'): ?>
                <div class="mensaje error" role="alert">
                    Debe completar todos los campos.
                </div>
            <?php endif; ?>

            <?php if ($estado === 'cantidad_invalida'): ?>
                <div class="mensaje error" role="alert">
                    La cantidad debe ser un número.
                </div>
            <?php endif; ?>

            <form action="guardar.php" method="POST">
                <div class="campo">
                    <label for="nombre">
                        Nombre del producto
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        maxlength="50"
                        minlength="3"
                        pattern="^(?:[^A-Za-záéíóúÁÉÍÓÚñÑ]*[A-Za-záéíóúÁÉÍÓÚñÑ]){3,}.*$"
                        title="Mínimo 3 letras, los símbolos no cuentan"
                        placeholder="Ejemplo: Café"
                        required>
                </div>

                <div class="campo">
                    <label for="cantidad">
                        Cantidad
                    </label>

                    <input
                        type="number"
                        id="cantidad"
                        name="cantidad"
                        placeholder="Ejemplo: 10"
                        required>
                </div>

                <button type="submit">
                    Registrar producto
                </button>
            </form>
        </section>

        <!-- Listado de productos -->
        <section class="tarjeta listado">
            <div class="titulo-listado">
                <h2>Productos registrados</h2>

                <p class="descripcion">
                    Total: <?php echo $totalProductos; ?>
                </p>
            </div>

            <div class="tabla-contenedor">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Producto</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Fecha</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($productosEnPagina === 0): ?>
                            <tr>
                                <td colspan="5" class="sin-registros">
                                    No hay productos registrados.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td>
                                    <?php echo (int) $producto['id']; ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        (string) $producto['nombre'],
                                        ENT_QUOTES | ENT_SUBSTITUTE,
                                        'UTF-8'
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        (string) $producto['cantidad'],
                                        ENT_QUOTES | ENT_SUBSTITUTE,
                                        'UTF-8'
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php if ($producto['cantidad'] > 0): ?>
                                        <span class="estado disponible">
                                            Disponible
                                        </span>
                                    <?php else: ?>
                                        <span class="estado agotado">
                                            Sin existencia
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        (string) $producto['fecharegistro'],
                                        ENT_QUOTES | ENT_SUBSTITUTE,
                                        'UTF-8'
                                    );
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación visible incluso cuando solo hay una página -->
            <div class="pie-tabla">

                <nav
                    class="paginacion"
                    aria-label="Paginación de productos"
                >
                    <!-- Página anterior -->
                    <?php if ($paginaActual > 1): ?>
                        <a
                            href="?pagina=<?php echo $paginaActual - 1; ?>"
                            class="pagina-enlace"
                            rel="prev"
                        >
                            Anterior
                        </a>
                    <?php else: ?>
                        <span
                            class="pagina-enlace deshabilitada"
                            aria-disabled="true"
                        >
                            Anterior
                        </span>
                    <?php endif; ?>

                    <!-- Números de página -->
                    <?php
                    for (
                        $numero = $primeraPaginaVisible;
                        $numero <= $ultimaPaginaVisible;
                        $numero++
                    ):
                    ?>
                        <?php if ($numero === $paginaActual): ?>
                            <span
                                class="pagina-enlace activa"
                                aria-current="page"
                            >
                                <?php echo $numero; ?>
                            </span>
                        <?php else: ?>
                            <a
                                href="?pagina=<?php echo $numero; ?>"
                                class="pagina-enlace"
                                aria-label="Ir a la página <?php echo $numero; ?>"
                            >
                                <?php echo $numero; ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <!-- Página siguiente -->
                    <?php if ($paginaActual < $totalPaginas): ?>
                        <a
                            href="?pagina=<?php echo $paginaActual + 1; ?>"
                            class="pagina-enlace"
                            rel="next"
                        >
                            Siguiente
                        </a>
                    <?php else: ?>
                        <span
                            class="pagina-enlace deshabilitada"
                            aria-disabled="true"
                        >
                            Siguiente
                        </span>
                    <?php endif; ?>
                </nav>
            </div>
        </section>
    </main>

    <footer>
        U1. Planeación del proceso de desarrollo de software
    </footer>
</body>

</html>