<?php
session_start();
require_once 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

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

// Array de nombres de meses
$nombresMeses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];

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
        $msg = "No se puede borrar una reserva pasada o la del día actual.";
    }
}

// Obtener citas del mes
// Si es administrador ve todas, si es socio solo las suyas
$sqlMes = "
    SELECT c.id, c.fecha, c.hora, u.nombre AS socio, u.telefono, s.nombre AS servicio, s.duracion, s.precio, c.socio_id
    FROM cita c
    JOIN usuario u ON c.socio_id = u.id
    JOIN servicio s ON c.servicio_id = s.id
    WHERE MONTH(c.fecha) = :mes AND YEAR(c.fecha) = :anio
";

$paramsMes = [':mes' => $mes, ':anio' => $anio];

if ($_SESSION['rol'] !== 'administrador') {
    $sqlMes .= " AND c.socio_id = :uid";
    $paramsMes[':uid'] = $_SESSION['id'];
}

$stmtMes = $pdo->prepare($sqlMes);
$stmtMes->execute($paramsMes);
$reservasMes = $stmtMes->fetchAll(PDO::FETCH_ASSOC);

$reservasPorDia = [];
foreach ($reservasMes as $c) {
    $d = intval(date('j', strtotime($c['fecha'])));
    $reservasPorDia[$d][] = $c;
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
    
    // Filtrar búsqueda también
    if ($_SESSION['rol'] !== 'administrador') {
        // Insertamos la condición antes del ORDER BY
        $sql = str_replace("ORDER BY", "AND c.socio_id = :uid ORDER BY", $sql);
    }

    $stmt = $pdo->prepare($sql);
    $paramsBusqueda = [':likeQ' => "%{$busqueda}%", ':fechaQ' => $busqueda];
    if ($_SESSION['rol'] !== 'administrador') {
        $paramsBusqueda[':uid'] = $_SESSION['id'];
    }
    $stmt->execute($paramsBusqueda);
    $resultadosBusqueda = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Helper seguro para salida
function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php $tituloPagina = "Sección de Reservas"; ?>
    <?php include 'head.php'; ?>
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
        <h1 class="titulo-principal">Sección de Reservas</h1>
    </div>
    <?php include 'nav.php'; ?> <!-- Antes era nav.html -->
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
                <a href="cita.php" class="btn-atras"><span>Nueva Reserva</span></a>
                <a href="citas.php" class="btn-atras"><span>Mostrar Calendario</span></a>
            </div>
        </form>
    </main>

    <!-- ======================== -->
<!-- RESULTADOS DE BÚSQUEDA -->
<!-- ======================== -->
<?php if ($busqueda !== ''): ?>
    <h2 class="titulo-principal">Resultados de búsqueda para "<?= h($busqueda) ?>"</h2>
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
            <p>No se encontraron reservas.</p>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>


    
<!-- ======================== -->
<!-- RESERVAS DEL DÍA SELECCIONADO -->
<!-- ======================== -->
<?php if ($diaSeleccionado): ?>
    <h2 class="titulo-principal">Reservas del día <?= h($diaSeleccionado) ?>/<?= h($mes) ?>/<?= h($anio) ?></h2>
    <div class="citas-dia">
        <?php
        if (!empty($reservasPorDia[$diaSeleccionado])) {
            foreach ($reservasPorDia[$diaSeleccionado] as $c) {
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
                    <p>No se encontraron reservas</p>
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
        <h2 style="margin: 0; display: flex; flex-direction: column; align-items: center;">
            <?= $nombresMeses[$mes] ?>
            <span style="font-size: 1.1rem; margin-top: 0.2rem;"><?= h($mes) ?>/<?= h($anio) ?></span>
        </h2>
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
                    $tiene = !empty($reservasPorDia[$d]);
                    echo '<td class="clickable" onclick="location.href=\'citas.php?dia='.$d.'&mes='.$mes.'&anio='.$anio.'\';">';
                    echo '<div>' . h($d) . '</div>';
                    if ($tiene) echo '<div><small>(' . count($reservasPorDia[$d]) . ')</small></div>';
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
        <p>¿Deseas cancelar esta reserva?</p>
        <button id="confirmarCancelar" class="btn-atras btn-modal">Sí, cancelar</button>
        <button id="cerrarModal" class="btn-atras btn-modal">No, mantener</button>
    </div>
</div>

<script src="js/footer.js"></script>
<script src="js/transiciones.js"></script>
<script src="js/modal.js"></script>
<script>
    // Registrar el Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('sw.js') // CORREGIDO: Ruta relativa, sin la barra inicial
                .then(registration => {
                    console.log('ServiceWorker registrado con éxito:', registration.scope);
                })
                .catch(err => {
                    console.log('Fallo en el registro del ServiceWorker:', err);
                });
        });
    }
</script>

</body>
</html>
