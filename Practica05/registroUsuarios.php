<?php
declare(strict_types=1);

session_start();

const MSG_OBLIGATORIO = 'Este campo es obligatorio.';

function e(?string $valor): string
{
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function norm(?string $texto): string
{
    $texto = trim((string)($texto ?? ''));
    if ($texto === '') {
        return '';
    }
    $transliterado = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);
    $base = $transliterado !== false ? $transliterado : $texto;
    return strtolower(trim($base));
}

function calcularEdadDesdeFecha(?string $valorFecha): ?int
{
    if (!$valorFecha) {
        return null;
    }
    $fechaNacimiento = DateTimeImmutable::createFromFormat('Y-m-d', $valorFecha);
    if (!$fechaNacimiento || $fechaNacimiento->format('Y-m-d') !== $valorFecha) {
        return null;
    }
    $hoy = new DateTimeImmutable('today');
    if ($fechaNacimiento > $hoy) {
        return -1;
    }
    return $fechaNacimiento->diff($hoy)->y;
}

function validarFechaNacimiento(?string $valorFecha): bool|string
{
    if (!$valorFecha) {
        return true;
    }
    $edad = calcularEdadDesdeFecha($valorFecha);
    if ($edad === null) {
        return 'Escribe una fecha de nacimiento válida.';
    }
    if ($edad < 0) {
        return 'No puedes ingresar una fecha futura.';
    }
    if ($edad < 10) {
        return 'Eres menor de edad.';
    }
    return true;
}

function urlValida(string $valor, string $host): bool|string
{
    $partes = parse_url($valor);
    if ($partes === false || empty($partes['scheme']) || empty($partes['host'])) {
        return "Escribe una URL válida (ej. https://{$host}/usuario).";
    }
    $esquema = strtolower($partes['scheme']);
    $dominio = strtolower($partes['host']);
    $hostEsperado = strtolower($host);
    $coincide = $dominio === $hostEsperado || str_ends_with($dominio, '.' . $hostEsperado);

    if (!in_array($esquema, ['http', 'https'], true) || !$coincide) {
        return "Escribe una URL de {$host} válida.";
    }
    return true;
}

function validarPaso(int $paso, array $datos, array $archivos): array
{
    $errores = [];

    if ($paso === 0) {
        if ($datos['nombre'] === '') {
            $errores['nombre'] = MSG_OBLIGATORIO;
        }
        if ($datos['apellidoP'] === '') {
            $errores['apellidoP'] = MSG_OBLIGATORIO;
        }
        if ($datos['fechaNacimiento'] !== '') {
            $resEdad = validarFechaNacimiento($datos['fechaNacimiento']);
            if ($resEdad !== true) {
                $errores['fechaNacimiento'] = $resEdad;
            }
        }
    }

    if ($paso === 1) {
        if ($datos['correo'] === '') {
            $errores['correo'] = MSG_OBLIGATORIO;
        } elseif (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
            $errores['correo'] = 'Escribe un correo válido: usuario@dominio.ext.';
        }

        if ($datos['telefono'] === '') {
            $errores['telefono'] = MSG_OBLIGATORIO;
        } elseif (!preg_match('/^\d{10,13}$/', $datos['telefono'])) {
            $errores['telefono'] = 'Solo se aceptan números (10 a 13 dígitos), sin letras ni símbolos.';
        }

        if ($datos['horarioContactoDesde'] !== '' && $datos['horarioContactoHasta'] !== '') {
            if ($datos['horarioContactoDesde'] >= $datos['horarioContactoHasta']) {
                $errores['horarioContactoHasta'] = 'La hora final debe ser posterior a la inicial.';
            }
        }

        if ($datos['socialFb'] && $datos['urlFacebook'] !== '') {
            $resFb = urlValida($datos['urlFacebook'], 'facebook.com');
            if ($resFb !== true) {
                $errores['urlFacebook'] = $resFb;
            }
        }

        if ($datos['socialLk'] && $datos['urlLinkedin'] !== '') {
            $resLk = urlValida($datos['urlLinkedin'], 'linkedin.com');
            if ($resLk !== true) {
                $errores['urlLinkedin'] = $resLk;
            }
        }

        if ($datos['socialWa'] && !empty($archivos['whatsAppQr']['name'] ?? '')) {
            $tmp = $archivos['whatsAppQr']['tmp_name'] ?? '';
            $mime = ($tmp && is_uploaded_file($tmp)) ? (mime_content_type($tmp) ?: '') : '';
            if (!str_starts_with($mime, 'image/')) {
                $errores['whatsAppQr'] = 'No se puede mostrar el archivo cargado.';
            }
        }
    }

    if ($paso === 2) {
        if ($datos['usuario'] === '') {
            $errores['usuario'] = MSG_OBLIGATORIO;
        } elseif (mb_strlen($datos['usuario']) < 4 || preg_match('/\s/u', $datos['usuario'])) {
            $errores['usuario'] = 'Utiliza al menos 4 caracteres, sin espacios.';
        }

        if ($datos['contrasena'] === '') {
            $errores['contrasena'] = MSG_OBLIGATORIO;
        } elseif (preg_match('/^(?=.*[A-Z])(?=.*[^A-Za-z0-9\s])\S{8,}$/u', $datos['contrasena']) !== 1) {
            $errores['contrasena'] = 'Mínimo 8 caracteres, una mayúscula y un símbolo especial, sin espacios.';
        }

        if ($datos['confirmarContrasena'] === '') {
            $errores['confirmarContrasena'] = MSG_OBLIGATORIO;
        } elseif ($datos['confirmarContrasena'] !== $datos['contrasena']) {
            $errores['confirmarContrasena'] = 'Las contraseñas no coinciden.';
        }
    }

    if ($paso === 3) {
        if ($datos['direccion'] === '') {
            $errores['direccion'] = MSG_OBLIGATORIO;
        }
        if ($datos['pais'] === '') {
            $errores['pais'] = MSG_OBLIGATORIO;
        }
        if ($datos['estado'] === '') {
            $errores['estado'] = MSG_OBLIGATORIO;
        }
        if ($datos['ciudad'] === '') {
            $errores['ciudad'] = MSG_OBLIGATORIO;
        }
        if ($datos['codigoPostal'] !== '' && norm($datos['pais']) === 'mexico') {
            if (!preg_match('/^\d{5}$/', $datos['codigoPostal'])) {
                $errores['codigoPostal'] = 'El código postal debe tener 5 dígitos.';
            }
        }
    }

    if ($paso === 4) {
        if ($datos['quiereFactura']) {
            if ($datos['rfc'] === '') {
                $errores['rfc'] = MSG_OBLIGATORIO;
            } elseif (!preg_match('/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/u', $datos['rfc'])) {
                $errores['rfc'] = 'Escribe un RFC válido (ej. ABCD010203XYZ).';
            }

            if ($datos['cpFiscal'] !== '' && !preg_match('/^\d{5}$/', $datos['cpFiscal'])) {
                $errores['cpFiscal'] = 'El código postal fiscal debe tener 5 dígitos.';
            }

            if (!empty($archivos['fiscalSituacion']['name'] ?? '')) {
                if (!preg_match('/\.pdf$/i', (string)$archivos['fiscalSituacion']['name'])) {
                    $errores['fiscalSituacion'] = 'Solo se permiten archivos PDF.';
                }
            }
        }

        if (!$datos['terminos']) {
            $errores['terminos'] = 'Debes aceptar los términos y condiciones.';
        }
    }

    return $errores;
}

$valoresDefecto = [    'tituloCortesia'       => '',    'nombre'               => '',    'apellidoP'            => '',    'apellidoM'            => '',    'genero'               => '',    'fechaNacimiento'      => '',    'correo'               => '',    'telefono'             => '',    'horarioContactoDesde' => '09:00',    'horarioContactoHasta' => '18:00',    'socialFb'             => false,    'urlFacebook'          => '',    'socialLk'             => false,    'urlLinkedin'          => '',    'socialWa'             => false,    'usuario'              => '',    'contrasena'           => '',    'confirmarContrasena'  => '',    'direccion'            => '',    'pais'                 => 'México',    'estado'               => '',    'ciudad'               => '',    'codigoPostal'         => '',    'quiereFactura'        => false,    'rfc'                  => '',    'razonSocial'          => '',    'regimenFiscal'        => '',    'usoCfdi'              => '',    'cpFiscal'             => '',    'userIdFiscal'         => '250910',    'terminos'             => false,];

$datos = $valoresDefecto;
$pasoActual = 0;
$errores = [];
$alertaGlobal = false;
$registroExitoso = false;
$resumenRegistro = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = (string)($_POST['accion'] ?? '');
    $pasoActual = max(0, min(4, (int)($_POST['pasoActual'] ?? 0)));

    if ($accion === 'limpiar') {
        $datos = $valoresDefecto;
    } else {
        $datos = [            'tituloCortesia'       => trim((string)($_POST['tituloCortesia'] ?? '')),
            'nombre'               => trim((string)($_POST['nombre'] ?? '')),
            'apellidoP'            => trim((string)($_POST['apellidoP'] ?? '')),
            'apellidoM'            => trim((string)($_POST['apellidoM'] ?? '')),
            'genero'               => trim((string)($_POST['genero'] ?? '')),
            'fechaNacimiento'      => trim((string)($_POST['fechaNacimiento'] ?? '')),
            'correo'               => trim((string)($_POST['correo'] ?? '')),
            'telefono'             => preg_replace('/\D+/', '', (string)($_POST['telefono'] ?? '')),
            'horarioContactoDesde' => trim((string)($_POST['horarioContactoDesde'] ?? '09:00')),
            'horarioContactoHasta' => trim((string)($_POST['horarioContactoHasta'] ?? '18:00')),
            'socialFb'             => isset($_POST['socialFb']),
            'urlFacebook'          => trim((string)($_POST['urlFacebook'] ?? '')),
            'socialLk'             => isset($_POST['socialLk']),
            'urlLinkedin'          => trim((string)($_POST['urlLinkedin'] ?? '')),
            'socialWa'             => isset($_POST['socialWa']),
            'usuario'              => trim((string)($_POST['usuario'] ?? '')),
            'contrasena'           => (string)($_POST['contrasena'] ?? ''),
            'confirmarContrasena'  => (string)($_POST['confirmarContrasena'] ?? ''),
            'direccion'            => trim((string)($_POST['direccion'] ?? '')),
            'pais'                 => trim((string)($_POST['pais'] ?? 'México')),
            'estado'               => trim((string)($_POST['estado'] ?? '')),
            'ciudad'               => trim((string)($_POST['ciudad'] ?? '')),
            'codigoPostal'         => trim((string)($_POST['codigoPostal'] ?? '')),
            'quiereFactura'        => isset($_POST['quiereFactura']),
            'rfc'                  => strtoupper(trim((string)($_POST['rfc'] ?? ''))),
            'razonSocial'          => trim((string)($_POST['razonSocial'] ?? '')),
            'regimenFiscal'        => trim((string)($_POST['regimenFiscal'] ?? '')),
            'usoCfdi'              => trim((string)($_POST['usoCfdi'] ?? '')),
            'cpFiscal'             => trim((string)($_POST['cpFiscal'] ?? '')),
            'userIdFiscal'         => trim((string)($_POST['userIdFiscal'] ?? '250910')),
            'terminos'             => isset($_POST['terminos']),
        ];

        if ($accion === 'anterior') {
            $pasoActual = max(0, $pasoActual - 1);
        } elseif ($accion === 'siguiente') {
            $errores = validarPaso($pasoActual, $datos, $_FILES);
            if (empty($errores)) {
                $pasoActual = min(4, $pasoActual + 1);
            } else {
                $alertaGlobal = true;
            }
        } elseif ($accion === 'enviar') {
            $primerPasoConError = null;
            for ($i = 0; $i <= 4; $i++) {
                $errPaso = validarPaso($i, $datos, $_FILES);
                if (!empty($errPaso)) {
                    $errores = $errPaso;
                    $primerPasoConError = $i;
                    break;
                }
            }

            if ($primerPasoConError !== null) {
                $pasoActual = $primerPasoConError;
                $alertaGlobal = true;
            } else {
                $registroExitoso = true;
                $resumenRegistro = [
                    'nombreCompleto' => trim($datos['tituloCortesia'] . ' ' . $datos['nombre'] . ' ' . $datos['apellidoP'] . ' ' . $datos['apellidoM']),
                    'correo'         => $datos['correo'],
                    'telefono'       => $datos['telefono'],
                    'usuario'        => $datos['usuario'],
                    'edad'           => calcularEdadDesdeFecha($datos['fechaNacimiento']),
                    'ubicacion'      => trim($datos['direccion'] . ', ' . $datos['ciudad'] . ', ' . $datos['estado'] . ', ' . $datos['pais']),
                ];
            }
        }
    }
}

$textoEdad = 'Selecciona una fecha para calcular tu edad.';
$claseEdad = '';
if ($datos['fechaNacimiento'] !== '') {
    $validacionEdad = validarFechaNacimiento($datos['fechaNacimiento']);
    $edadCalc = calcularEdadDesdeFecha($datos['fechaNacimiento']);
    if ($validacionEdad !== true) {
        $textoEdad = "No permitido: {$validacionEdad}";
        $claseEdad = 'edad-estado--error';
    } elseif ($edadCalc !== null && $edadCalc >= 18) {
        $textoEdad = "Eres mayor de edad. Tienes {$edadCalc} años.";
        $claseEdad = 'edad-estado--mayor';
    } elseif ($edadCalc !== null) {
        $textoEdad = "Eres menor de edad (10 a 17 años). Tienes {$edadCalc} años.";
        $claseEdad = 'edad-estado--menor';
    }
}

function claseCampo(string $campo, array $datos, array $errores): string
{
    if (isset($errores[$campo])) {
        return 'campo-error';
    }
    if (!empty($datos[$campo])) {
        return 'campo-lleno';
    }
    return '';
}

function errorCampo(string $campo, array $errores): string
{
    if (!isset($errores[$campo])) {
        return '';
    }
    return '<span class="mensaje-error-campo">' . e($errores[$campo]) . '</span>';
}

$hoyISO = (new DateTimeImmutable('today'))->format('Y-m-d');
$iconosWizard = ['fa-user', 'fa-address-book', 'fa-lock', 'fa-map-marker-alt', 'fa-file-invoice'];
$colorPrincipal = $_SESSION['mecagest_config']['primaryColor'] ?? '#2EC4B6';
$colorFondo = $_SESSION['mecagest_config']['backgroundColor'] ?? '#F4F8FC';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MecaGest: Formulario de Registro de Nuevos Usuarios - 250910</title>
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
        <h2>Registro de Nuevos Usuarios</h2>
        <p>Complete los campos para solicitar una cuenta en el sistema.</p>
    </header>

    <nav class="menu-nav" aria-label="Módulos">
        <a href="registroUsuarios.php" class="activo">Registro de usuarios</a>
        <a href="proximas_citas.php">Próximas citas</a>
        <a href="configuracion.php">Configuración</a>
        <a href="salidas_almacen.php">Salidas de almacén</a>
    </nav>

    <main>
        <?php if ($alertaGlobal): ?>
            <div class="alerta">
                <i class="fas fa-exclamation-circle"></i> Revisa los campos señalados antes de continuar.
            </div>
        <?php endif; ?>

        <div class="wizard-bar">
            <?php foreach ($iconosWizard as $idx => $icono): ?>
                <?php
                    $estadoPaso = '';
                    if ($idx === $pasoActual) {
                        $estadoPaso = 'activo';
                    } elseif ($idx < $pasoActual) {
                        $estadoPaso = 'completado';
                    }
                ?>
                <div class="wizard-paso-item <?= $estadoPaso ?>"><i class="fas <?= $icono ?>"></i></div>
                <?php if ($idx < count($iconosWizard) - 1): ?>
                    <div class="line"></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <?php if ($registroExitoso): ?>
            <div class="mensaje-simulacion">
                <strong>¡Registro completado y validado en PHP!</strong>
                <ul style="margin-top: 10px; margin-left: 20px;">
                    <li><strong>Nombre:</strong> <?= e($resumenRegistro['nombreCompleto']) ?></li>
                    <li><strong>Usuario:</strong> <?= e($resumenRegistro['usuario']) ?></li>
                    <li><strong>Correo:</strong> <?= e($resumenRegistro['correo']) ?></li>
                    <li><strong>Teléfono:</strong> <?= e($resumenRegistro['telefono']) ?></li>
                    <?php if ($resumenRegistro['edad'] !== null): ?>
                        <li><strong>Edad:</strong> <?= (int)$resumenRegistro['edad'] ?> años</li>
                    <?php endif; ?>
                    <li><strong>Ubicación:</strong> <?= e($resumenRegistro['ubicacion']) ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <form action="registroUsuarios.php" method="post" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="pasoActual" value="<?= $pasoActual ?>">

            <?php if ($pasoActual !== 0): ?>
                <input type="hidden" name="tituloCortesia" value="<?= e($datos['tituloCortesia']) ?>">
                <input type="hidden" name="nombre" value="<?= e($datos['nombre']) ?>">
                <input type="hidden" name="apellidoP" value="<?= e($datos['apellidoP']) ?>">
                <input type="hidden" name="apellidoM" value="<?= e($datos['apellidoM']) ?>">
                <input type="hidden" name="genero" value="<?= e($datos['genero']) ?>">
                <input type="hidden" name="fechaNacimiento" value="<?= e($datos['fechaNacimiento']) ?>">
            <?php endif; ?>

            <?php if ($pasoActual !== 1): ?>
                <input type="hidden" name="correo" value="<?= e($datos['correo']) ?>">
                <input type="hidden" name="telefono" value="<?= e($datos['telefono']) ?>">
                <input type="hidden" name="horarioContactoDesde" value="<?= e($datos['horarioContactoDesde']) ?>">
                <input type="hidden" name="horarioContactoHasta" value="<?= e($datos['horarioContactoHasta']) ?>">
                <?php if ($datos['socialFb']): ?><input type="hidden" name="socialFb" value="1"><?php endif; ?>
                <input type="hidden" name="urlFacebook" value="<?= e($datos['urlFacebook']) ?>">
                <?php if ($datos['socialLk']): ?><input type="hidden" name="socialLk" value="1"><?php endif; ?>
                <input type="hidden" name="urlLinkedin" value="<?= e($datos['urlLinkedin']) ?>">
                <?php if ($datos['socialWa']): ?><input type="hidden" name="socialWa" value="1"><?php endif; ?>
            <?php endif; ?>

            <?php if ($pasoActual !== 2): ?>
                <input type="hidden" name="usuario" value="<?= e($datos['usuario']) ?>">
                <input type="hidden" name="contrasena" value="<?= e($datos['contrasena']) ?>">
                <input type="hidden" name="confirmarContrasena" value="<?= e($datos['confirmarContrasena']) ?>">
            <?php endif; ?>

            <?php if ($pasoActual !== 3): ?>
                <input type="hidden" name="direccion" value="<?= e($datos['direccion']) ?>">
                <input type="hidden" name="pais" value="<?= e($datos['pais']) ?>">
                <input type="hidden" name="estado" value="<?= e($datos['estado']) ?>">
                <input type="hidden" name="ciudad" value="<?= e($datos['ciudad']) ?>">
                <input type="hidden" name="codigoPostal" value="<?= e($datos['codigoPostal']) ?>">
            <?php endif; ?>

            <?php if ($pasoActual !== 4): ?>
                <?php if ($datos['quiereFactura']): ?><input type="hidden" name="quiereFactura" value="1"><?php endif; ?>
                <input type="hidden" name="rfc" value="<?= e($datos['rfc']) ?>">
                <input type="hidden" name="razonSocial" value="<?= e($datos['razonSocial']) ?>">
                <input type="hidden" name="regimenFiscal" value="<?= e($datos['regimenFiscal']) ?>">
                <input type="hidden" name="usoCfdi" value="<?= e($datos['usoCfdi']) ?>">
                <input type="hidden" name="cpFiscal" value="<?= e($datos['cpFiscal']) ?>">
                <input type="hidden" name="userIdFiscal" value="<?= e($datos['userIdFiscal']) ?>">
                <?php if ($datos['terminos']): ?><input type="hidden" name="terminos" value="validado"><?php endif; ?>
            <?php endif; ?>

            <!-- PASO 1 -->
            <fieldset class="wizard-paso <?= $pasoActual === 0 ? 'paso-activo' : '' ?>">
                <legend>Datos Personales</legend>
                <div class="grid-2-col">
                    <div class="input-group">
                        <label for="tituloCortesia">Título de Cortesía:</label>
                        <select id="tituloCortesia" name="tituloCortesia" class="<?= claseCampo('tituloCortesia', $datos, $errores) ?>">
                            <option value="">Selecciona una opción</option>
                            <?php foreach (['Sr.', 'Sra.', 'Srita.', 'Ing.', 'Dr.', 'Mtro.', 'Ninguno'] as $tit): ?>
                                <option value="<?= e($tit) ?>" <?= $datos['tituloCortesia'] === $tit ? 'selected' : '' ?>><?= e($tit) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="nombre">Nombre: *</label>
                        <input type="text" id="nombre" name="nombre" value="<?= e($datos['nombre']) ?>" class="<?= claseCampo('nombre', $datos, $errores) ?>" placeholder="Jessica">
                        <?= errorCampo('nombre', $errores) ?>
                    </div>
                    <div class="input-group">
                        <label for="apellidoP">Apellido Paterno: *</label>
                        <input type="text" id="apellidoP" name="apellidoP" value="<?= e($datos['apellidoP']) ?>" class="<?= claseCampo('apellidoP', $datos, $errores) ?>" placeholder="Gutierrez">
                        <?= errorCampo('apellidoP', $errores) ?>
                    </div>
                    <div class="input-group">
                        <label for="apellidoM">Apellido Materno:</label>
                        <input type="text" id="apellidoM" name="apellidoM" value="<?= e($datos['apellidoM']) ?>" class="<?= claseCampo('apellidoM', $datos, $errores) ?>" placeholder="Lecona">
                    </div>
                    <div class="input-group">
                        <label for="genero">Género:</label>
                        <select id="genero" name="genero" class="<?= claseCampo('genero', $datos, $errores) ?>">
                            <option value="">Selecciona una opción</option>
                            <option value="F" <?= $datos['genero'] === 'F' ? 'selected' : '' ?>>Femenino</option>
                            <option value="M" <?= $datos['genero'] === 'M' ? 'selected' : '' ?>>Masculino</option>
                            <option value="X" <?= $datos['genero'] === 'X' ? 'selected' : '' ?>>Prefiero no decirlo</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="fechaNacimiento">Fecha de Nacimiento:</label>
                        <input type="date" id="fechaNacimiento" name="fechaNacimiento" max="<?= $hoyISO ?>" value="<?= e($datos['fechaNacimiento']) ?>" class="<?= claseCampo('fechaNacimiento', $datos, $errores) ?>">
                        <?= errorCampo('fechaNacimiento', $errores) ?>
                    </div>
                </div>
                <p id="resultadoEdad" class="<?= $claseEdad ?>"><?= e($textoEdad) ?></p>
                <div class="btn-group right">
                    <button type="submit" name="accion" value="limpiar" class="btn-secondary">Limpiar</button>
                    <button type="submit" name="accion" value="siguiente" class="btn-primary">Siguiente paso &raquo;</button>
                </div>
            </fieldset>

            <!-- PASO 2 -->
            <fieldset class="wizard-paso <?= $pasoActual === 1 ? 'paso-activo' : '' ?>">
                <legend>Datos de Contacto</legend>
                <div class="grid-2-col">
                    <div class="input-group">
                        <label for="correo">Correo Electrónico: *</label>
                        <input type="email" id="correo" name="correo" value="<?= e($datos['correo']) ?>" class="<?= claseCampo('correo', $datos, $errores) ?>" placeholder="jessica.gutierrez@example.com">
                        <?= errorCampo('correo', $errores) ?>
                    </div>
                    <div class="input-group">
                        <label for="telefono">Número Telefónico: *</label>
                        <input type="tel" id="telefono" name="telefono" inputmode="numeric" maxlength="13" value="<?= e($datos['telefono']) ?>" class="<?= claseCampo('telefono', $datos, $errores) ?>" placeholder="7761234567">
                        <small class="ayuda aviso-solo-numeros">Solo se aceptan números (10 a 13 dígitos).</small>
                        <?= errorCampo('telefono', $errores) ?>
                    </div>
                </div>

                <h4>Horario preferido de contacto</h4>
                <div class="grid-2-col">
                    <div class="input-group">
                        <label for="horarioContactoDesde">Desde</label>
                        <input type="time" id="horarioContactoDesde" name="horarioContactoDesde" value="<?= e($datos['horarioContactoDesde']) ?>" class="<?= claseCampo('horarioContactoDesde', $datos, $errores) ?>">
                    </div>
                    <div class="input-group">
                        <label for="horarioContactoHasta">Hasta</label>
                        <input type="time" id="horarioContactoHasta" name="horarioContactoHasta" value="<?= e($datos['horarioContactoHasta']) ?>" class="<?= claseCampo('horarioContactoHasta', $datos, $errores) ?>">
                        <?= errorCampo('horarioContactoHasta', $errores) ?>
                    </div>
                </div>

                <h4>Redes sociales y mensajería</h4>
                <div class="grid-2-col">
                    <div class="input-group">
                        <div class="checkbox-inline">
                            <input type="checkbox" id="socialFb" name="socialFb" value="1" <?= $datos['socialFb'] ? 'checked' : '' ?>>
                            <label for="socialFb">Facebook</label>
                        </div>
                        <input type="url" id="urlFacebook" name="urlFacebook" value="<?= e($datos['urlFacebook']) ?>" class="<?= claseCampo('urlFacebook', $datos, $errores) ?>" placeholder="https://facebook.com/usuario">
                        <?= errorCampo('urlFacebook', $errores) ?>
                    </div>
                    <div class="input-group">
                        <div class="checkbox-inline">
                            <input type="checkbox" id="socialLk" name="socialLk" value="1" <?= $datos['socialLk'] ? 'checked' : '' ?>>
                            <label for="socialLk">LinkedIn</label>
                        </div>
                        <input type="url" id="urlLinkedin" name="urlLinkedin" value="<?= e($datos['urlLinkedin']) ?>" class="<?= claseCampo('urlLinkedin', $datos, $errores) ?>" placeholder="https://linkedin.com/in/usuario">
                        <?= errorCampo('urlLinkedin', $errores) ?>
                    </div>
                    <div class="input-group span-2">
                        <div class="checkbox-inline">
                            <input type="checkbox" id="socialWa" name="socialWa" value="1" <?= $datos['socialWa'] ? 'checked' : '' ?>>
                            <label for="socialWa">WhatsApp: Adjuntar QR</label>
                        </div>
                        <input type="file" id="whatsAppQr" name="whatsAppQr" accept="image/*" style="margin-top: 10px;">
                        <?= errorCampo('whatsAppQr', $errores) ?>
                    </div>
                </div>

                <div class="btn-group between">
                    <button type="submit" name="accion" value="anterior" class="btn-secondary">&laquo; Anterior</button>
                    <div style="display:flex; gap:10px;">
                        <button type="submit" name="accion" value="limpiar" class="btn-secondary">Limpiar</button>
                        <button type="submit" name="accion" value="siguiente" class="btn-primary">Siguiente paso &raquo;</button>
                    </div>
                </div>
            </fieldset>

            <!-- PASO 3 -->
            <fieldset class="wizard-paso <?= $pasoActual === 2 ? 'paso-activo' : '' ?>">
                <legend>Datos de Acceso</legend>
                <div class="grid-2-col">
                    <div class="input-group span-2">
                        <label for="usuario">Nombre de usuario: *</label>
                        <input type="text" id="usuario" name="usuario" value="<?= e($datos['usuario']) ?>" class="<?= claseCampo('usuario', $datos, $errores) ?>" placeholder="jessica123">
                        <small class="ayuda">Utiliza al menos 4 caracteres.</small>
                        <?= errorCampo('usuario', $errores) ?>
                    </div>
                    <div class="input-group">
                        <label for="contrasena">Contraseña: *</label>
                        <input type="password" id="contrasena" name="contrasena" value="<?= e($datos['contrasena']) ?>" class="<?= claseCampo('contrasena', $datos, $errores) ?>" placeholder="********">
                        <?= errorCampo('contrasena', $errores) ?>
                    </div>
                    <div class="input-group">
                        <label for="confirmarContrasena">Confirmar contraseña: *</label>
                        <input type="password" id="confirmarContrasena" name="confirmarContrasena" value="<?= e($datos['confirmarContrasena']) ?>" class="<?= claseCampo('confirmarContrasena', $datos, $errores) ?>" placeholder="********">
                        <?= errorCampo('confirmarContrasena', $errores) ?>
                    </div>
                </div>
                <small class="ayuda">Requisitos: mínimo 8 caracteres, al menos una mayúscula y un símbolo especial. Ambas contraseñas deben coincidir.</small>
                <div class="btn-group between">
                    <button type="submit" name="accion" value="anterior" class="btn-secondary">&laquo; Anterior</button>
                    <div style="display:flex; gap:10px;">
                        <button type="submit" name="accion" value="limpiar" class="btn-secondary">Limpiar</button>
                        <button type="submit" name="accion" value="siguiente" class="btn-primary">Siguiente paso &raquo;</button>
                    </div>
                </div>
            </fieldset>

            <!-- PASO 4 -->
            <fieldset class="wizard-paso <?= $pasoActual === 3 ? 'paso-activo' : '' ?>">
                <legend>Datos de Ubicación</legend>
                <div class="grid-2-col">
                    <div class="input-group span-2">
                        <label for="direccion">Calle y número: *</label>
                        <input type="text" id="direccion" name="direccion" value="<?= e($datos['direccion']) ?>" class="<?= claseCampo('direccion', $datos, $errores) ?>" placeholder="Ej. Av. Juárez 125, Col. Centro">
                        <?= errorCampo('direccion', $errores) ?>
                    </div>
                    <div class="input-group">
                        <label for="pais">País: *</label>
                        <input type="text" id="pais" name="pais" value="<?= e($datos['pais']) ?>" class="<?= claseCampo('pais', $datos, $errores) ?>">
                        <?= errorCampo('pais', $errores) ?>
                    </div>
                    <div class="input-group">
                        <label for="estado">Estado / entidad federativa: *</label>
                        <input type="text" id="estado" name="estado" value="<?= e($datos['estado']) ?>" class="<?= claseCampo('estado', $datos, $errores) ?>" placeholder="Ej. Puebla">
                        <?= errorCampo('estado', $errores) ?>
                    </div>
                    <div class="input-group">
                        <label for="ciudad">Ciudad / municipio: *</label>
                        <input type="text" id="ciudad" name="ciudad" value="<?= e($datos['ciudad']) ?>" class="<?= claseCampo('ciudad', $datos, $errores) ?>" placeholder="Ej. Xicotepec">
                        <?= errorCampo('ciudad', $errores) ?>
                    </div>
                    <div class="input-group">
                        <label for="codigoPostal">Código postal:</label>
                        <input type="text" id="codigoPostal" name="codigoPostal" inputmode="numeric" maxlength="10" value="<?= e($datos['codigoPostal']) ?>" class="<?= claseCampo('codigoPostal', $datos, $errores) ?>" placeholder="73080">
                        <?= errorCampo('codigoPostal', $errores) ?>
                    </div>
                </div>
                <div class="btn-group between">
                    <button type="submit" name="accion" value="anterior" class="btn-secondary">&laquo; Anterior</button>
                    <div style="display:flex; gap:10px;">
                        <button type="submit" name="accion" value="limpiar" class="btn-secondary">Limpiar</button>
                        <button type="submit" name="accion" value="siguiente" class="btn-primary">Siguiente paso &raquo;</button>
                    </div>
                </div>
            </fieldset>

            <!-- PASO 5 -->
            <fieldset class="wizard-paso <?= $pasoActual === 4 ? 'paso-activo' : '' ?>">
                <legend>Facturación (opcional)</legend>
                <div class="checkbox-linea <?= $datos['quiereFactura'] ? 'factura-activa' : '' ?>">
                    <input type="checkbox" id="quiereFactura" name="quiereFactura" value="1" <?= $datos['quiereFactura'] ? 'checked' : '' ?>>
                    <label for="quiereFactura">Deseo proporcionar datos de facturación</label>
                </div>

                <div class="grid-2-col">
                    <div class="input-group">
                        <label for="rfc">RFC</label>
                        <input type="text" id="rfc" name="rfc" maxlength="13" value="<?= e($datos['rfc']) ?>" class="<?= claseCampo('rfc', $datos, $errores) ?>" placeholder="Ej. ABCD010203XYZ">
                        <?= errorCampo('rfc', $errores) ?>
                    </div>
                    <div class="input-group">
                        <label for="razonSocial">Nombre o razón social</label>
                        <input type="text" id="razonSocial" name="razonSocial" value="<?= e($datos['razonSocial']) ?>" class="<?= claseCampo('razonSocial', $datos, $errores) ?>" placeholder="Nombre para facturación">
                    </div>
                    <div class="input-group">
                        <label for="regimenFiscal">Régimen fiscal</label>
                        <select id="regimenFiscal" name="regimenFiscal" class="<?= claseCampo('regimenFiscal', $datos, $errores) ?>">
                            <option value="">Seleccionar régimen</option>
                            <option value="601" <?= $datos['regimenFiscal'] === '601' ? 'selected' : '' ?>>601 · General de Ley Personas Morales</option>
                            <option value="605" <?= $datos['regimenFiscal'] === '605' ? 'selected' : '' ?>>605 · Sueldos y Salarios</option>
                            <option value="612" <?= $datos['regimenFiscal'] === '612' ? 'selected' : '' ?>>612 · Personas Físicas con Actividades Empresariales y Profesionales</option>
                            <option value="621" <?= $datos['regimenFiscal'] === '621' ? 'selected' : '' ?>>621 · Incorporación Fiscal</option>
                            <option value="626" <?= $datos['regimenFiscal'] === '626' ? 'selected' : '' ?>>626 · Régimen Simplificado de Confianza</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="usoCfdi">Uso del CFDI</label>
                        <select id="usoCfdi" name="usoCfdi" class="<?= claseCampo('usoCfdi', $datos, $errores) ?>">
                            <option value="">Seleccionar uso</option>
                            <option value="G01" <?= $datos['usoCfdi'] === 'G01' ? 'selected' : '' ?>>G01 · Adquisición de mercancías</option>
                            <option value="G03" <?= $datos['usoCfdi'] === 'G03' ? 'selected' : '' ?>>G03 · Gastos en general</option>
                            <option value="P01" <?= $datos['usoCfdi'] === 'P01' ? 'selected' : '' ?>>P01 · Por definir</option>
                            <option value="S01" <?= $datos['usoCfdi'] === 'S01' ? 'selected' : '' ?>>S01 · Sin efectos fiscales</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="cpFiscal">Código postal fiscal</label>
                        <input type="text" id="cpFiscal" name="cpFiscal" inputmode="numeric" maxlength="5" value="<?= e($datos['cpFiscal']) ?>" class="<?= claseCampo('cpFiscal', $datos, $errores) ?>" placeholder="5 dígitos">
                        <?= errorCampo('cpFiscal', $errores) ?>
                    </div>
                    <div class="input-group">
                        <label for="fiscalSituacion">Constancia de situación fiscal (PDF)</label>
                        <input type="file" id="fiscalSituacion" name="fiscalSituacion" accept=".pdf">
                        <?= errorCampo('fiscalSituacion', $errores) ?>
                    </div>
                </div>
                <input type="hidden" name="userIdFiscal" value="<?= e($datos['userIdFiscal']) ?>">

                <div class="checkbox-linea">
                    <input type="checkbox" id="terminos" name="terminos" value="validado" <?= $datos['terminos'] ? 'checked' : '' ?>>
                    <label for="terminos">Acepto los términos y condiciones de uso de la plataforma MecaGest. *</label>
                </div>
                <?= errorCampo('terminos', $errores) ?>

                <div class="btn-group between">
                    <button type="submit" name="accion" value="anterior" class="btn-secondary">&laquo; Anterior</button>
                    <div style="display:flex; gap:10px;">
                        <button type="submit" name="accion" value="limpiar" class="btn-secondary">Limpiar</button>
                        <button type="submit" name="accion" value="enviar" class="btn-success">Enviar Solicitud</button>
                    </div>
                </div>
            </fieldset>
        </form>
    </main>
</body>
</html>