<?php
declare(strict_types=1);

session_start();

function e(?string $valor): string
{
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

$catalogo = [
    'bujia' => [
        'nombre'    => 'Bujía',
        'tipo'      => 'Refaccion',
        'categoria' => 'Piezas de Motor',
        'unidad'    => 'Piezas',
    ],
    'filtro-aceite' => [
        'nombre'    => 'Filtro de aceite',
        'tipo'      => 'Refaccion',
        'categoria' => 'Piezas de Motor',
        'unidad'    => 'Piezas',
    ],
    'amortiguador' => [
        'nombre'    => 'Amortiguador',
        'tipo'      => 'Refaccion',
        'categoria' => 'Piezas de Suspensión',
        'unidad'    => 'Piezas',
    ],
    'llave-combinada' => [
        'nombre'    => 'Llave combinada',
        'tipo'      => 'Herramienta',
        'categoria' => 'Herramienta manual',
        'unidad'    => 'Piezas',
    ],
    'llave-dinamometrica' => [
        'nombre'    => 'Llave dinamométrica',
        'tipo'      => 'Herramienta',
        'categoria' => 'Herramienta especializada',
        'unidad'    => 'Piezas',
    ],
    'extractor-poleas' => [
        'nombre'    => 'Extractor de Poleas',
        'tipo'      => 'Herramienta',
        'categoria' => 'Herramienta especializada',
        'unidad'    => 'Juego',
    ],
    'llave-torx' => [
        'nombre'    => 'Llave Torx',
        'tipo'      => 'Herramienta',
        'categoria' => 'Herramienta manual',
        'unidad'    => 'Juegos',
    ],
];

if (!isset($_SESSION['mecagest_salidas'])) {
    $_SESSION['mecagest_salidas'] = [
        ['herramienta' => 'Llave combinada', 'categoria' => 'Herramienta manual', 'cantidad' => 4, 'unidad' => 'Piezas'],
        ['herramienta' => 'Llave dinamométrica', 'categoria' => 'Herramienta especializada', 'cantidad' => 2, 'unidad' => 'Piezas'],
        ['herramienta' => 'Extractor de Poleas', 'categoria' => 'Herramienta especializada', 'cantidad' => 1, 'unidad' => 'Juego'],
        ['herramienta' => 'Llave Torx', 'categoria' => 'Herramienta manual', 'cantidad' => 3, 'unidad' => 'Juegos'],
    ];
}

$categoriaSeleccionada = (string)($_POST['categoriaRecurso'] ?? 'Refaccion');
$errorSolicitud = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $claveItem = trim((string)($_POST['toolSelect'] ?? ''));
    $cantidad = (int)($_POST['toolQuantity'] ?? 0);

    if (!isset($catalogo[$claveItem])) {
        $errorSolicitud = 'Selecciona una opción válida del inventario.';
    } elseif ($cantidad < 1 || $cantidad > 10) {
        $errorSolicitud = 'La cantidad debe estar entre 1 y 10.';
    } else {
        $item = $catalogo[$claveItem];
        $_SESSION['mecagest_salidas'][] = [
            'herramienta' => $item['nombre'],
            'categoria'  => $item['categoria'],
            'cantidad'   => $cantidad,
            'unidad'     => $item['unidad'],
        ];
    }
}

$filas = $_SESSION['mecagest_salidas'];
$totalUnidades = array_sum(array_column($filas, 'cantidad'));
$colorPrincipal = $_SESSION['mecagest_config']['primaryColor'] ?? '#2EC4B6';
$colorFondo = $_SESSION['mecagest_config']['backgroundColor'] ?? '#F4F8FC';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MecaGest: Salidas de almacén</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --azul-principal: <?= e($colorPrincipal) ?>;
            --fondo: <?= e($colorFondo) ?>;
        }
    </style>
</head>
<body>
    <header>
        <h1>MecaGest</h1>
        <h2>Salidas de almacén</h2>
        <p>Solicitud de herramientas y refacciones asociadas a órdenes de servicio; datos de práctica.</p>
    </header>

    <nav class="menu-nav" aria-label="Módulos">
        <a href="registroUsuarios.php">Registro de usuarios</a>
        <a href="proximas_citas.php">Próximas citas</a>
        <a href="configuracion.php">Configuración</a>
        <a href="salidas_almacen.php" class="activo">Salidas de almacén</a>
    </nav>

    <main>
        <form action="salidas_almacen.php" method="post">
            <fieldset>
                <legend>Solicitud de herramientas y refacciones</legend>
                <p><strong>Categoría de recurso:</strong></p>

                <div style="display: flex; gap: 25px; align-items: center; margin: 12px 0 20px 0;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" value="Refaccion" name="categoriaRecurso" id="catRefaccion" <?= $categoriaSeleccionada === 'Refaccion' ? 'checked' : '' ?> style="width: auto; margin: 0;">
                        <label for="catRefaccion" style="display: inline-block; margin: 0; cursor: pointer; font-weight: normal;">Refacción</label>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" value="Herramienta" name="categoriaRecurso" id="catHerramienta" <?= $categoriaSeleccionada === 'Herramienta' ? 'checked' : '' ?> style="width: auto; margin: 0;">
                        <label for="catHerramienta" style="display: inline-block; margin: 0; cursor: pointer; font-weight: normal;">Herramienta</label>
                    </div>
                </div>

                <div class="input-group">
                    <label for="toolSelect">Elige la pieza o herramienta:</label>
                    <select id="toolSelect" name="toolSelect">
                        <option value="" disabled selected>Selecciona una opción del inventario...</option>
                        <?php foreach ($catalogo as $clave => $item): ?>
                            <option value="<?= e($clave) ?>"><?= e($item['nombre']) ?> (<?= e($item['categoria']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="input-group" style="margin-top: 15px;">
                    <label for="toolQuantity">Cantidad:</label>
                    <input type="number" id="toolQuantity" name="toolQuantity" min="1" max="10" value="1">
                </div>

                <?php if ($errorSolicitud !== ''): ?>
                    <div class="alerta" style="margin-top: 12px;"><?= e($errorSolicitud) ?></div>
                <?php endif; ?>

                <button type="submit" id="requestTool" class="btn-primary" style="margin-top: 15px;">Solicitar herramienta</button>

                <h4 style="margin-top: 25px;">Herramientas para la orden de servicio <span class="etiqueta-tipo"><?= count($filas) ?> conceptos</span></h4>
                <p class="ayuda">Inventario gestionado en sesión PHP sin base de datos.</p>
                <table>
                    <tr>
                        <th>No.</th>
                        <th>Herramienta</th>
                        <th>Categoría</th>
                        <th>Cantidad</th>
                        <th>Unidad</th>
                    </tr>
                    <?php foreach ($filas as $indice => $fila): ?>
                        <tr>
                            <td><?= $indice + 1 ?>.</td>
                            <td><?= e($fila['herramienta']) ?></td>
                            <td><?= e($fila['categoria']) ?></td>
                            <td><?= (int)$fila['cantidad'] ?></td>
                            <td><?= e($fila['unidad']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="white-space: nowrap;">
                        <td></td>
                        <td></td>
                        <td><strong>Total de unidades</strong></td>
                        <td><strong><?= (int)$totalUnidades ?></strong></td>
                        <td>-</td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </main>
</body>
</html>