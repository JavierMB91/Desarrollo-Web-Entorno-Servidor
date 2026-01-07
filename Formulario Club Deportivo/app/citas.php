<?php
session_start();
require_once 'conexion.php';

// ---------- Parámetros ----------
$mes  = isset($_GET['mes'])  ? intval($_GET['mes'])  : intval(date('n'));
$anio = isset($_GET['anio']) ? intval($_GET['anio']) : intval(date('Y'));
$diaSeleccionado = isset($_GET['dia']) ? intval($_GET['dia']) : null;
$busqueda = isset($_GET['q']) ? trim($_GET['q']) : '';

// Normalizar mes/año
while ($mes < 1)  { $mes += 12; $anio -= 1; }
while ($mes > 12) { $mes -= 12; $anio += 1; }

// Calcular primer día y días del mes
$primerDiaTimestamp = strtotime(sprintf('%04d-%02d-01', $anio, $mes));
$primerDiaSemana = intval(date('N', $primerDiaTimestamp)); // 1..7 (Lun..Dom)
$diasMes = intval(date('t', $primerDiaTimestamp));

// Prev / Next mes
$dt = new DateTime(sprintf('%04d-%02d-01', $anio, $mes));
$dtPrev = (clone $dt)->modify('-1 month');
$dtNext = (clone $dt)->modify('+1 month');
$prevMes = intval($dtPrev->format('n'));
$prevAnio = intval($dtPrev->format('Y'));
$nextMes = intval($dtNext->format('n'));
$nextAnio = intval($dtNext->format('Y'));

// Manejo de borrado
$msg = '';
if (isset($_GET['borrar'])) {
    $idBorrar = intval($_GET['borrar']);
    $stmtCheck = $pdo->prepare("SELECT fecha FROM cita WHERE id = ?");
    $stmtCheck->execute([$idBorrar]);
    $fecha = $stmtCheck->fetchColumn();

    if ($fecha && $fecha > date('Y-m-d')) {
        $stmtDel = $pdo->prepare("DELETE FROM cita WHERE id = ?");
        $stmtDel->execute([$idBorrar]);
        header('Location: citas.php?mes=' . $mes . '&anio=' . $anio);
        exit;
    } else {
        $msg = "No se puede borrar una cita pasada o la del día actual.";
    }
}

// Obtener citas del mes
$stmtMes = $pdo->prepare("
    SELECT c.id, c.fecha, c.hora, u.nombre AS socio, u.telefono, s.nombre AS servicio, s.duracion, s.precio
    FROM cita c
    JOIN usuario u ON c.socio_id = u.id
    JOIN servicio s ON c.servicio_id = s.id
    WHERE MONTH(c.fecha) = :mes AND YEAR(c.fecha) = :anio
");
$stmtMes->execute([':mes' => $mes, ':anio' => $anio]);
$citasMes = $stmtMes->fetchAll(PDO::FETCH_ASSOC);

$citasPorDia = [];
foreach ($citasMes as $c) {
    $d = intval(date('j', strtotime($c['fecha'])));
    $citasPorDia[$d][] = $c;
}

// Búsqueda
$resultadosBusqueda = [];
if ($busqueda !== '') {
    $sql = "
        SELECT c.id, c.fecha, c.hora, u.nombre AS socio, u.telefono, s.nombre AS servicio, s.duracion, s.precio
        FROM cita c
        JOIN usuario u ON c.socio_id = u.id
        JOIN servicio s ON c.servicio_id = s.id
        WHERE u.nombre LIKE :likeQ
           OR u.telefono LIKE :likeQ
           OR s.nombre LIKE :likeQ
           OR c.fecha = :fechaQ
        ORDER BY c.fecha, c.hora
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':likeQ'  => "%{$busqueda}%",
        ':fechaQ' => $busqueda
    ]);
    $resultadosBusqueda = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Helper seguro para salida
function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="shortcut icon" href="favicon/favicon.ico">
    <title>Sección Citas</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body class="socios-body">

<?php if (isset($_SESSION['mensaje_exito'])): ?>
    <div class="mensaje-exito">
        <?= $_SESSION['mensaje_exito']; unset($_SESSION['mensaje_exito']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['mensaje_error'])): ?>
    <div class="mensaje-error">
        <?= $_SESSION['mensaje_error']; unset($_SESSION['mensaje_error']); ?>
    </div>
<?php endif; ?>

<header>
    <div class="titulo-con-logo">
        <h1 class="titulo-club">Sección Citas</h1>
    </div>
    <div id="nav"></div>
</header>
    <!-- ======================== -->
    <!-- MENSAJE DE ESTADO -->
    <!-- ======================== -->
    <?php if (!empty($msg)): ?>
        <div id="connection-status">
            <p><?= h($msg) ?></p>
        </div>
    <?php endif; ?>

    <!-- ======================== -->
    <!-- BUSCADOR -->
    <!-- ======================== -->
    <main>
        <form method="get" action="citas.php">
            <input type="hidden" name="mes" value="<?= $mes ?>">
            <input type="hidden" name="anio" value="<?= $anio ?>">
            <input type="text" name="q" placeholder="Buscar por socio, teléfono, fecha o servicio" value="<?= h($busqueda) ?>">
            <div class="contenedor-botones">
                <button type="submit"><span>Buscar</span></button>
                <a href="cita.php" class="btn-atras"><span>Nueva cita</span></a>
                <a href="citas.php" class="btn-atras"><span>Mostrar Calendario</span></a>
            </div>
        </form>
    </main>

    <!-- ======================== -->
<!-- RESULTADOS DE BÚSQUEDA -->
<!-- ======================== -->
<?php if ($busqueda !== ''): ?>
    <h2 class="titulo-club">Resultados de búsqueda para "<?= h($busqueda) ?>"</h2>
    <div class="resultados-busqueda">
        <?php if (!empty($resultadosBusqueda)): ?>
            <?php foreach ($resultadosBusqueda as $c): ?>
                <div class="socio-card cita-card">
                    <p><strong>Fecha:</strong> <?= h(date('d/m/Y', strtotime($c['fecha']))) ?></p>
                    <?php
                        // Mostrar hora como HH:MM seguido de am/pm (ej. 18:00 pm)
                        $horaParaMostrar = h(date('H:i', strtotime($c['hora'])));
                        try {
                            $dtTmp = new DateTime($c['hora']);
                        } catch (Exception $e) {
                            $dtTmp = false;
                        }
                        if ($dtTmp) {
                            $horaParaMostrar .= ' ' . $dtTmp->format('a');
                        }
                    ?>
                    <p><strong>Hora:</strong> <?= $horaParaMostrar ?></p>
                    <p><strong>Socio:</strong> <?= h($c['socio']) ?></p>
                    <p><strong>Servicio:</strong> <?= h($c['servicio']) ?> (<?= h($c['duracion']) ?> min)</p>
                    <?php
                        $precioMostrar = (floatval($c['precio']) == 0) ? 'Gratuito' : number_format(floatval($c['precio']), 2, ',', '.') . ' €';
                    ?>
                    <p><strong>Precio:</strong> <?= h($precioMostrar) ?></p>
                    <p><strong>Teléfono:</strong> <?= h($c['telefono']) ?></p>
                    
                    <?php if ($c['fecha'] > date('Y-m-d')): ?>
                        <button type="button" class="btn-atras btn-cancelar" 
                                data-url="citas.php?borrar=<?= intval($c['id']) ?>&mes=<?= $mes ?>&anio=<?= $anio ?>">
                            Cancelar
                        </button>
                    <?php else: ?>
                        <p>(no se puede cancelar)</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="resultados-busqueda">    
            <p>No se encontraron citas.</p>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>


    
<!-- ======================== -->
<!-- CITAS DEL DÍA SELECCIONADO -->
<!-- ======================== -->
<?php if ($diaSeleccionado): ?>
    <h2 class="titulo-club">Citas del día <?= h($diaSeleccionado) ?>/<?= h($mes) ?>/<?= h($anio) ?></h2>
    <div class="citas-dia">
        <?php
        if (!empty($citasPorDia[$diaSeleccionado])) {
            foreach ($citasPorDia[$diaSeleccionado] as $c) {
                echo '<div class="socio-card cita-card">'; ?>
                <?php
                    // Mostrar hora como HH:MM seguido de am/pm (ej. 18:00 pm)
                    $horaParaMostrar = h(date('H:i', strtotime($c['hora'])));
                    try {
                        $dtTmp = new DateTime($c['hora']);
                    } catch (Exception $e) {
                        $dtTmp = false;
                    }
                    if ($dtTmp) {
                        $horaParaMostrar .= ' ' . $dtTmp->format('a');
                    }
                ?>
                <p><strong>Hora:</strong> <?= $horaParaMostrar ?></p>
                <p><strong>Socio:</strong> <?= h($c['socio']) ?></p>
                <p><strong>Servicio:</strong> <?= h($c['servicio']) ?> (<?= h($c['duracion']) ?> min)</p>
                <?php
                    $precioMostrar = (floatval($c['precio']) == 0) ? 'Gratuito' : number_format(floatval($c['precio']), 2, ',', '.') . ' €';
                ?>
                <p><strong>Precio:</strong> <?= h($precioMostrar) ?></p>
                <p><strong>Teléfono:</strong> <?= h($c['telefono']) ?></p>
                <?php
                
                // Botón cancelar solo para citas futuras (no incluir hoy)
                if ($c['fecha'] > date('Y-m-d')) {
                    echo ' <button type="button" class="btn-atras btn-cancelar" 
                           data-url="citas.php?borrar=' . intval($c['id']) . '&mes=' . $mes . '&anio=' . $anio . '&dia=' . $diaSeleccionado . '">
                           Cancelar</button>';
                } else {
                    echo ' <p>(no se puede cancelar)</p>';
                }

                echo '</div>';
            }
        } else {
            echo '<div class="resultados-busqueda">
                    <p>No se encontraron citas</p>
                 </div>';
        }
        ?>
    </div>
<?php endif; ?>

    <!-- ======================== -->
    <!-- NAVEGACIÓN DE MESES -->
    <!-- ======================== -->
    <div class="contenedor-botones">
        <a href="citas.php?mes=<?= $prevMes ?>&anio=<?= $prevAnio ?>" class="btn-atras, btn-mes">◀ Mes anterior</a>
        <h2><?= h($mes) ?>/<?= h($anio) ?></h2>
        <a href="citas.php?mes=<?= $nextMes ?>&anio=<?= $nextAnio ?>" class="btn-atras, btn-mes">Mes siguiente ▶</a>
    </div>

    <!-- ======================== -->
    <!-- CALENDARIO -->
    <!-- ======================== -->
    <div class="calendario">
        <table>
            <thead>
                <tr>
                    <th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <?php
                for ($i = 1; $i < $primerDiaSemana; $i++) echo '<td></td>';
                $posSemana = $primerDiaSemana;
                for ($d = 1; $d <= $diasMes; $d++) {
                    $tiene = !empty($citasPorDia[$d]);
                    echo '<td class="clickable" onclick="location.href=\'citas.php?dia='.$d.'&mes='.$mes.'&anio='.$anio.'\';">';
                    echo '<div>' . h($d) . '</div>';
                    if ($tiene) echo '<div><small>(' . count($citasPorDia[$d]) . ')</small></div>';
                    echo '</td>';
                    if ($posSemana == 7) { echo '</tr>'; if ($d != $diasMes) echo '<tr>'; $posSemana = 0; }
                    $posSemana++;
                }
                if ($posSemana != 1) { for ($k = $posSemana; $k <= 7; $k++) echo '<td></td>'; echo '</tr>'; }
                ?>
            </tbody>
        </table>
    </div>

    <div class="contenedor-botones">
        <a href="index.php" class="btn-atras"><span>Volver</span></a>   
    </div>

</div>

<div id="footer"></div>

<div id="modalCancelar" class="modal">
    <div class="modal-content">
        <p>¿Deseas cancelar esta cita?</p>
        <button id="confirmarCancelar" class="btn-atras btn-modal">Sí, cancelar</button>
        <button id="cerrarModal" class="btn-atras btn-modal">No, mantener</button>
    </div>
</div>

<script src="js/nav.js"></script>
<script src="js/footer.js"></script>
<script src="js/transiciones.js"></script>
<script src="js/modal.js"></script>

</body>
</html>
