<?php
session_start();
require_once 'conexion.php';

// ========================
// 1. Comprobar si llega un ID
// ========================
if (!isset($_GET['id'])) {
    header("Location: socios.php");
    exit;
}

$id = $_GET['id'];

// ========================
// 2. Cargar los datos del socio
// ========================
$sql = "SELECT * FROM usuario WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$socio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$socio) {
    header("Location: socios.php");
    exit;
}

// ========================
// 3. Procesar formulario (POST)
// ========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre   = $_POST['nombre'];
    $edad     = $_POST['edad'];
    $telefono = $_POST['telefono'];
    $telefonoLimpio = preg_replace('/\D/', '', $telefono);

    // Foto actual
    $fotoActual = $socio['foto'];
    $fotoFinal = $fotoActual;

    // ==========================
    // Manejo de foto
    // ==========================
    if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, ['jpg', 'jpeg'])) {
            $_SESSION['mensaje_error'] = "❌ Formato de imagen no permitido.";
            header("Location: editarSocio.php?id=$id");
            exit;
        }

        $nuevoNombreFoto = $telefonoLimpio . "." . $ext;
        $rutaDestino = __DIR__ . "/uploads/usuarios/" . $nuevoNombreFoto;

        // Borrar foto anterior si existe
        if ($fotoActual && file_exists(__DIR__ . "/uploads/usuarios/" . $fotoActual)) {
            unlink(__DIR__ . "/uploads/usuarios/" . $fotoActual);
        }

        // Subir nueva foto
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
            $_SESSION['mensaje_error'] = "❌ Error al subir la imagen.";
            header("Location: editarSocio.php?id=$id");
            exit;
        }

        $fotoFinal = $nuevoNombreFoto;

    } else {

        // Si no sube nueva foto, pero cambia el teléfono → renombrar
        if ($fotoActual) {
            $extActual = strtolower(pathinfo($fotoActual, PATHINFO_EXTENSION));
            $nuevoNombreFoto = $telefonoLimpio . "." . $extActual;

            if ($fotoActual !== $nuevoNombreFoto) {
                $rutaAnterior = __DIR__ . "/uploads/usuarios/" . $fotoActual;
                $rutaNueva = __DIR__ . "/uploads/usuarios/" . $nuevoNombreFoto;

                if (file_exists($rutaAnterior)) {
                    rename($rutaAnterior, $rutaNueva);
                    $fotoFinal = $nuevoNombreFoto;
                }
            }
        }
    }

    // ==========================
    // Manejo de contraseña
    // ==========================
    $password = $_POST['password'] ?? '';
    $params = [
        'nombre'   => $nombre,
        'edad'     => $edad,
        'telefono' => $telefono,
        'foto'     => $fotoFinal,
        'id'       => $id
    ];

    $sqlUpdate = "UPDATE usuario SET 
                    nombre = :nombre,
                    edad = :edad,
                    telefono = :telefono,
                    foto = :foto";

    if (!empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sqlUpdate   .= ", password = :password";
        $params['password'] = $passwordHash;
    }

    $sqlUpdate .= " WHERE id = :id";

    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->execute($params);

    $_SESSION['mensaje_exito'] = "✅ Socio editado con éxito.";
    header("Location: socios.php");
    exit;
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
    <title>Editar socio</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body class="socios-body">

<!-- =======================
     MENSAJES DE SESIÓN
======================= -->
<?php if (isset($_SESSION['mensaje_exito'])): ?>
    <div class="mensaje-exito">
        <?= $_SESSION['mensaje_exito']; ?>
    </div>
    <?php unset($_SESSION['mensaje_exito']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['mensaje_error'])): ?>
    <div class="mensaje-error">
        <?= $_SESSION['mensaje_error']; ?>
    </div>
    <?php unset($_SESSION['mensaje_error']); ?>
<?php endif; ?>


<h1 class="titulo-club">Editar socio</h1>
<div id="nav"></div>

<main class="editar-formulario">

<form method="post" enctype="multipart/form-data">

    <div class="bloque-form">
        <label>Nombre:</label>
        <input id="nombre" type="text" name="nombre" value="<?= htmlspecialchars($socio['nombre']) ?>">
        <span class="error" id="nombreError"></span>
    </div>

    <div class="bloque-form">
        <label>Edad:</label>
        <input id="edad" type="number" name="edad" value="<?= htmlspecialchars($socio['edad']) ?>">
        <span class="error" id="edadError"></span>
    </div>

    <div class="bloque-form">
        <label>Teléfono:</label>
        <input id="telefono" type="tel" name="telefono" value="<?= htmlspecialchars($socio['telefono']) ?>">
        <span class="error" id="telefonoError"></span>
    </div>

    <div class="bloque-form">
        <label>Contraseña (dejar vacío para no cambiar):</label>
        <input id="password" type="password" name="password" placeholder="Nueva contraseña">
        <span class="error" id="passwordError"></span>
    </div>

    <div class="bloque-form">
        <label>Foto actual:</label><br>
        <?php 
            $fotoPath = $socio['foto'] ? 'uploads/usuarios/' . $socio['foto'] : 'uploads/usuarios/default.jpg';
        ?>
        <img src="<?= htmlspecialchars($fotoPath) ?>?v=<?= file_exists($fotoPath) ? filemtime($fotoPath) : time() ?>"
             width="80" alt="Foto socio">
    </div>

    <div class="bloque-form">
        <label>Nueva foto (JPG):</label>
        <input id="foto" type="file" name="foto" accept=".jpg,.jpeg">
        <span class="error" id="fotoError"></span>
    </div>

    <div class="contenedor-botones">
        <button type="submit">Guardar cambios</button>
        <a href="socios.php" class="btn-atras">Cancelar</a>
    </div>
</form>

</main>

<div id="footer"></div>

<script src="js/nav.js"></script>
<script src="js/funcionesEditarSocio.js"></script>
<script src="js/footer.js"></script>
<script src="js/transiciones.js"></script>

</body>
</html>
