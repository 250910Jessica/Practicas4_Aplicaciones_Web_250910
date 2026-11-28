<?php
declare(strict_types=1);

session_start();

function e(?string $valor): string
{
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

$citas = [
    [
        'no'          => 1,
        'cliente_uni' => 'Cita de entrega de unidad reparada con el cliente: Mauricio Mejía, Unidad: Volkswagen, Jetta 2020',
        'descripcion' => 'Se realizó un cambio de bujías, la unidad está lista para entrega',
        'fecha'       => '10 de Octubre de 2026',
        'mes'         => '2026-10',
        'semana'      => '2026-W41',
        'estatus'     => 'Programada',
        'clase_est'   => 'estatus--programada',
    ],
    [
        'no'          => 2,
        'cliente_uni' => 'Cita de valoración con el cliente: Carlos Hernández, Unidad: Nissan, Versa 2021',
        'descripcion' => 'El cliente solicita una revisión del sistema de frenos debido a un ruido al momento de frenar.',
        'fecha'       => '12 de Octubre de 2026',
        'mes'         => '2026-10',
        'semana'      => '2026-W42',
        'estatus'     => 'Atendida',
        'clase_est'   => 'estatus--atendida',
    ],
    [
        'no'          => 3,
        'cliente_uni' => 'Cita de valoración con el cliente: Mauricio Mejía, Unidad: Volkswagen, Jetta 2020',
        'descripcion' => 'A la unidad se le hicieron cambio de bujías, pero el cliente reporta un sonido extraño.',
        'fecha'       => '17 de Octubre de 2026',
        'mes'         => '2026-10',
        'semana'      => '2026-W42',
        'estatus'     => 'Programada',
        'clase_est'   => 'estatus--programada',
    ],
    [
        'no'          => 4,
        'cliente_uni' => 'Cita de valoración con la cliente: Alejandra Romero, Unidad: Toyota, Hilux 2012',
        'descripcion' => 'El cliente solicita una revisión del cuerpo de aceleración de la unidad para posible mantenimiento correctivo.',
        'fecha'       => '16 de Octubre de 2026',
        'mes'         => '2026-10',
        'semana'      => '2026-W42',
        'estatus'     => 'Cancelada',
        'clase_est'   => 'estatus--cancelada',
    ],
    [
        'no'          => 5,
        'cliente_uni' => 'Cita de valoración con la cliente: Alejandra Romero, Unidad: Toyota, Hilux 2012',
        'descripcion' => 'El cliente solicita una revisión del cuerpo de aceleración de la unidad para posible mantenimiento correctivo.',
        'fecha'       => '20 de Octubre de 2026',
        'mes'         => '2026-10',
        'semana'      => '2026-W43',
        'estatus'     => 'Reprogramada',
        'clase_est'   => 'estatus--reprogramada',
    ],
];

$filtroTipo = (string)($_GET['appoimentFilter'] ?? 'Month');
if (!in_array($filtroTipo, ['Month', 'Week'], true)) {
    $filtroTipo = 'Month';
}

$filtroMes = trim((string)($_GET['filtroMes'] ?? '2026-10'));
$filtroSemana = trim((string)($_GET['filtroSemana'] ?? ''));
$busqueda = trim((string)($_GET['busquedaCitas'] ?? ''));

$citasFiltradas = array_filter($citas, function (array $cita) use ($filtroTipo, $filtroMes, $filtroSemana, $busqueda): bool {
    if ($filtroTipo === 'Month' && $filtroMes !== '' && $cita['mes'] !== $filtroMes) {
        return false;
    }
    if ($filtroTipo === 'Week' && $filtroSemana !== '' && $cita['semana'] !== $filtroSemana) {
        return false;
    }
    if ($busqueda !== '') {
        $texto = mb_strtolower($cita['cliente_uni'] . ' ' . $cita['descripcion']);
        if (!str_contains($texto, mb_strtolower($busqueda))) {
            return false;
        }
    }
    return true;
});

$colorPrincipal = $_SESSION['mecagest_config']['primaryColor'] ?? '#2EC4B6';
$colorFondo = $_SESSION['mecagest_config']['backgroundColor'] ?? '#F4F8FC';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MecaGest: Próximas citas</title>
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
        <h2>Próximas citas</h2>
        <p>Consulta de citas de ejemplo. Información de demostración sin base de datos.</p>
    </header>

    <nav class="menu-nav" aria-label="Módulos">
        <a href="registroUsuarios.php">Registro de usuarios</a>
        <a href="proximas_citas.php" class="activo">Próximas citas</a>
        <a href="configuracion.php">Configuración</a>
        <a href="salidas_almacen.php">Salidas de almacén</a>
    </nav>

    <main>
        <form action="proximas_citas.php" method="get">
            <fieldset>
                <legend>Visualización de Citas</legend>
                <p><strong>Filtrar citas por:</strong></p>

                <div style="display: flex; gap: 25px; align-items: center; margin: 12px 0 20px 0;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" value="Month" name="appoimentFilter" id="appoimentByMonth" <?= $filtroTipo === 'Month' ? 'checked' : '' ?> style="width: auto; margin: 0;">
                        <label for="appoimentByMonth" style="display: inline-block; margin: 0; cursor: pointer; font-weight: normal;">Mes</label>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" value="Week" name="appoimentFilter" id="appoimentByWeek" <?= $filtroTipo === 'Week' ? 'checked' : '' ?> style="width: auto; margin: 0;">
                        <label for="appoimentByWeek" style="display: inline-block; margin: 0; cursor: pointer; font-weight: normal;">Semana</label>
                    </div>
                </div>

                <div class="input-group" id="grupoMes">
                    <label for="filtroMes">Seleccionar mes</label>
                    <input type="month" id="filtroMes" name="filtroMes" value="<?= e($filtroMes) ?>">
                </div>

                <div class="input-group" id="grupoSemana" style="margin-top: 10px;">
                    <label for="filtroSemana">Seleccionar semana</label>
                    <input type="week" id="filtroSemana" name="filtroSemana" value="<?= e($filtroSemana) ?>">
                </div>

                <button type="submit" id="verCitas" class="btn-primary" style="margin-top: 15px;">Ver citas</button>

                <h4>Citas programadas <span class="etiqueta-tipo"><?= count($citasFiltradas) ?> registros</span></h4>
                <p class="ayuda">Datos de ejemplo del mes de octubre de 2026.</p>
                <table>
                    <tr>
                        <th>No.</th>
                        <th>Nombre y unidad</th>
                        <th>Descripción</th>
                        <th>Fecha programada</th>
                        <th>Estatus actual</th>
                    </tr>
                    <?php if (empty($citasFiltradas)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No se encontraron citas con ese filtro.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($citasFiltradas as $cita): ?>
                            <tr>
                                <td><?= (int)$cita['no'] ?>.</td>
                                <td><?= e($cita['cliente_uni']) ?></td>
                                <td><?= e($cita['descripcion']) ?></td>
                                <td><?= e($cita['fecha']) ?></td>
                                <td><span class="estatus <?= e($cita['clase_est']) ?>"><?= e($cita['estatus']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>

                <h4>Búsqueda rápida <span class="etiqueta-tipo">input: search</span></h4>
                <input type="search" id="busquedaCitas" name="busquedaCitas" value="<?= e($busqueda) ?>" placeholder="Buscar por cliente o vehículo...">
                <button type="submit" id="consultarRegistros" class="btn-primary" style="margin-top: 15px;">Consultar registros</button>
                <small class="ayuda">Filtro y búsqueda procesados directamente en PHP.</small>
            </fieldset>
        </form>
    </main>
</body>
</html>