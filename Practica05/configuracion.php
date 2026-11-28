<?php
declare(strict_types=1);

session_start();

function e(?string $valor): string
{
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

if (!isset($_SESSION['mecagest_config'])) {
    $_SESSION['mecagest_config'] = [
        'primaryColor'    => '#2EC4B6',
        'backgroundColor' => '#F4F8FC',
        'soundsVolume'    => 80,
        'muted'           => false,
        'userId'          => '250910',
        'avatarDataUrl'   => '',
    ];
}

$mensajeGuardado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = (string)($_POST['accion'] ?? 'guardar');

    if ($accion === 'toggle_mute') {
        $_SESSION['mecagest_config']['muted'] = !$_SESSION['mecagest_config']['muted'];
        if ($_SESSION['mecagest_config']['muted']) {
            $_SESSION['mecagest_config']['soundsVolume'] = 0;
        } elseif ((int)$_SESSION['mecagest_config']['soundsVolume'] === 0) {
            $_SESSION['mecagest_config']['soundsVolume'] = 50;
        }
        $mensajeGuardado = 'Estado de audio actualizado.';
    } else {
        $primary = trim((string)($_POST['primaryColor'] ?? '#2EC4B6'));
        $bg = trim((string)($_POST['backgroundColor'] ?? '#F4F8FC'));
        $vol = max(0, min(100, (int)($_POST['soundsVolume'] ?? 80)));

        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $primary)) {
            $_SESSION['mecagest_config']['primaryColor'] = strtoupper($primary);
        }
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $bg)) {
            $_SESSION['mecagest_config']['backgroundColor'] = strtoupper($bg);
        }

        $_SESSION['mecagest_config']['soundsVolume'] = $vol;
        $_SESSION['mecagest_config']['muted'] = ($vol === 0);

        if (!empty($_FILES['userPhoto']['name'] ?? '') && ($_FILES['userPhoto']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $tmp = $_FILES['userPhoto']['tmp_name'];
            $mime = mime_content_type($tmp) ?: '';
            if (str_starts_with($mime, 'image/')) {
                $contenido = file_get_contents($tmp);
                if ($contenido !== false) {
                    $_SESSION['mecagest_config']['avatarDataUrl'] = 'data:' . $mime . ';base64,' . base64_encode($contenido);
                }
            }
        }

        $mensajeGuardado = 'Configuración guardada y aplicada en todas las páginas.';
    }
}

$cfg = $_SESSION['mecagest_config'];
$volumenActual = (int)$cfg['soundsVolume'];
$estaSilenciado = (bool)$cfg['muted'] || $volumenActual === 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MecaGest: Configuración</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --azul-principal: <?= e($cfg['primaryColor']) ?>;
            --fondo: <?= e($cfg['backgroundColor']) ?>;
        }
    </style>
</head>
<body>
    <header>
        <h1>MecaGest</h1>
        <h2>Configuración</h2>
        <p>Personalización de interfaz y cuenta. Los cambios se aplican en todas las páginas.</p>
    </header>

    <nav class="menu-nav" aria-label="Módulos">
        <a href="registroUsuarios.php">Registro de usuarios</a>
        <a href="proximas_citas.php">Próximas citas</a>
        <a href="configuracion.php" class="activo">Configuración</a>
        <a href="salidas_almacen.php">Salidas de almacén</a>
    </nav>

    <main>
        <?php if ($mensajeGuardado !== ''): ?>
            <div class="mensaje-simulacion">
                <i class="fas fa-check-circle"></i> <?= e($mensajeGuardado) ?>
            </div>
        <?php endif; ?>

        <form action="configuracion.php" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Configuración de la Interfaz</legend>
                <h4>Paleta MecaGest (Colores del Sistema)</h4>

                <div class="paleta" style="display: flex; gap: 10px; margin: 12px 0 15px 0;" aria-hidden="true">
                    <span style="width: 30px; height: 30px; border-radius: 50%; background:#011627; display: inline-block; border: 1px solid var(--borde);" title="Azul Oscuro"></span>
                    <span style="width: 30px; height: 30px; border-radius: 50%; background:#FDFFFC; display: inline-block; border: 1px solid var(--borde);" title="Blanco Suave"></span>
                    <span style="width: 30px; height: 30px; border-radius: 50%; background:#2EC4B6; display: inline-block; border: 1px solid var(--borde);" title="Turquesa Vibrante"></span>
                    <span style="width: 30px; height: 30px; border-radius: 50%; background:#E71D36; display: inline-block; border: 1px solid var(--borde);" title="Rojo Intenso"></span>
                    <span style="width: 30px; height: 30px; border-radius: 50%; background:#FF9F1C; display: inline-block; border: 1px solid var(--borde);" title="Naranja Alerta"></span>
                </div>

                <label for="primaryColor">Color Principal (Turquesa MecaGest)</label>
                <input type="color" id="primaryColor" name="primaryColor" value="<?= e($cfg['primaryColor']) ?>">
                <br>

                <label for="backgroundColor">Color de Fondo General</label>
                <input type="color" id="backgroundColor" name="backgroundColor" value="<?= e($cfg['backgroundColor']) ?>">
                <br>

                <label for="soundsVolume">Volumen de reproducción de Sonidos</label>
                <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                    <input type="range" id="soundsVolume" name="soundsVolume" min="0" max="100" value="<?= $volumenActual ?>" style="flex: 1; cursor: pointer;">
                    <span id="volumenTexto" style="font-weight: 600; min-width: 45px; text-align: right; color: var(--texto-secundario);"><?= $volumenActual ?></span>
                </div>

                <button type="submit" name="accion" value="toggle_mute" id="muteSounds" class="btn-secondary <?= $estaSilenciado ? 'activo-mute' : '' ?>" style="margin-top: 18px; display: inline-flex; align-items: center; gap: 8px;">
                    <i id="iconoBotonAudio" class="fas <?= $estaSilenciado ? 'fa-volume-xmark' : 'fa-volume-high' ?>"></i>
                    <span id="textoBotonAudio"><?= $estaSilenciado ? 'Activar sonidos' : 'Silenciar sonidos' ?></span>
                </button>
                <p class="ayuda" style="margin-top: 12px;">Modifica los controles y haz clic en Guardar para aplicar los colores en todo el sistema.</p>
            </fieldset>

            <fieldset>
                <legend>Configuración de Mi cuenta/perfil</legend>
                <h4>Perfil de MecaGest</h4>
                <label for="userPhoto">Foto de Perfil</label>

                <div style="display: flex; align-items: center; gap: 20px; margin-top: 10px;">
                    <div style="width: 70px; height: 70px; border-radius: 50%; border: 2px dashed var(--borde); background: var(--fondo); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;" id="avatarContainer">
                        <?php if ($cfg['avatarDataUrl'] !== ''): ?>
                            <img id="avatarPreview" src="<?= e($cfg['avatarDataUrl']) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <i id="avatarPlaceholder" class="fas fa-user" style="color: var(--texto-secundario); font-size: 1.5rem;"></i>
                        <?php endif; ?>
                    </div>
                    <div style="flex: 1;">
                        <input type="file" id="userPhoto" name="userPhoto" accept="image/*">
                        <small class="ayuda" style="display: block; margin-top: 6px;">Formatos de imagen admitidos por el navegador.</small>
                    </div>
                </div>

                <br>
                <label>Botón gráfico <span class="etiqueta-tipo">input: image</span></label><br>
                <input type="image" id="buttonNext" src="imagenes/next.gif" alt="Siguiente" style="width: 15%; height: 100px; object-fit: cover; display: block;" disabled>
                <small class="ayuda">Control deshabilitado: el envío gráfico se conectará en clases posteriores.</small>

                <input type="hidden" id="userId" name="userId" value="<?= e($cfg['userId']) ?>">
                <small class="ayuda"><strong>input: hidden</strong> — identificador presente en el código PHP, no visible en pantalla.</small>

                <label>Botón de envío <span class="etiqueta-tipo">input: submit</span></label><br>
                <button type="submit" name="accion" value="guardar" id="guardarPerfil">Guardar configuración</button>
            </fieldset>
        </form>
    </main>
</body>
</html>