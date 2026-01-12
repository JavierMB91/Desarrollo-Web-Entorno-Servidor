<?php
session_start();
require_once 'conexion.php'; // tu conexión PDO

// Verificar si el usuario está logueado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// ==========================
// 1. Procesar el formulario
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si es admin toma el post, si es socio toma su sesión
    $cliente = ($_SESSION['rol'] === 'administrador') ? intval($_POST['cliente']) : $_SESSION['id'];
    $servicio = intval($_POST['servicio']);
    $dia = $_POST['dia'];
    $hora = $_POST['hora'];

    // Validaciones simples
    if (!$cliente || !$servicio || !$dia || !$hora) {
        $_SESSION['mensaje_error'] = "❌ Todos los campos son obligatorios.";
    } else {
        // Comprobar si ya existe una cita para ese socio en la misma fecha y hora
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM cita WHERE socio_id = ? AND fecha = ? AND hora = ?");
        $stmtCheck->execute([$cliente, $dia, $hora]);
        $existe = $stmtCheck->fetchColumn();

        if ($existe) {
            $_SESSION['mensaje_error'] = "❌ Este participante ya tiene una cita a esa hora.";
        } else {
            // Insertar la cita
            $stmt = $pdo->prepare("INSERT INTO cita (socio_id, servicio_id, fecha, hora) VALUES (?, ?, ?, ?)");
            $stmt->execute([$cliente, $servicio, $dia, $hora]);
            $_SESSION['mensaje_exito'] = "✅ Cita agendada correctamente.";
        }
    }

    // Redirigir a citas.php para mostrar el mensaje
    header("Location: citas.php");
    exit;
}

// ==========================
// 2. Traer participantes y servicios
// ==========================
$clientes = [];
if ($_SESSION['rol'] === 'administrador') {
    $stmtClientes = $pdo->query("SELECT id, nombre FROM usuario WHERE rol='socio' ORDER BY nombre");
    $clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);
}

$stmtServicios = $pdo->query("SELECT id, nombre, duracion FROM servicio ORDER BY nombre");
$servicios = $stmtServicios->fetchAll(PDO::FETCH_ASSOC);

// ==========================
// 3. Helper seguro
// ==========================
function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
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
  <link rel="stylesheet" href="css/estilos.css">
  <title>Agendar Lectura</title>
</head>
<body class="cita-body">
  <div class="container">
    <header>
      <div class="titulo-con-logo">
        <h1 class="titulo-club">Agendar Lectura Guiada</h1>
      </div>
      <?php include 'nav.php'; ?> <!-- Antes era nav.html -->
    </header>
    <main>
      <h2 class="titulo-club">Nueva Cita</h2>

      <form action="" method="post" enctype="multipart/form-data" id="formularioCita">

        <div class="bloque-form">
          <label for="cliente">Participante</label>
          <?php if ($_SESSION['rol'] === 'administrador'): ?>
              <select id="cliente" name="cliente">
                <option value="">Seleccionar participante</option>
                <?php foreach ($clientes as $c): ?>
                  <option value="<?= $c['id'] ?>"><?= h($c['nombre']) ?></option>
                <?php endforeach; ?>
              </select>
              <span id="clienteError" class="error"></span>
          <?php else: ?>
              <input type="text" id="cliente" value="<?= h($_SESSION['nombre']) ?>" disabled>
              <!-- No enviamos input hidden 'cliente' aquí porque lo cogemos de sesión en PHP por seguridad -->
          <?php endif; ?>
        </div>

        <div class="bloque-form">
          <label for="servicio">Libro / Actividad</label>
          <select id="servicio" name="servicio">
            <option value="">Seleccionar libro o actividad</option>
            <?php foreach ($servicios as $s): ?>
              <option value="<?= $s['id'] ?>"><?= h($s['nombre']) ?> (<?= h($s['duracion']) ?> min)</option>
            <?php endforeach; ?>
          </select>
          <span id="servicioError" class="error"></span>
        </div>

        <div class="bloque-form">
          <label for="dia">Día</label>
          <input type="date" id="dia" name="dia">
          <span id="diaError" class="error"></span>
        </div>

        <div class="bloque-form">
          <label for="hora">Hora</label>
          <input type="time" id="hora" name="hora">
          <span id="horaError" class="error"></span>
        </div>

        <div class="contenedor-botones">
          <button type="submit"><span>Agendar</span></button>
          <a href="citas.php" class="btn-atras"><span>Volver</span></a>
        </div>

      </form>
    </main>
    <div id="footer"></div>
  </div>

  <script src="js/funcionesCita.js"></script>
  <script src="js/footer.js"></script>
  <script src="js/transiciones.js"></script>
</body>
</html>
