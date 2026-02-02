<?php
session_start();
require_once 'conexion.php';
require_once 'check_sesion.php';

if (!is_loged_in()) {
    header('Location: login.php');
    exit();
}

 $usuario_id = $_SESSION['id'];

// Obtener datos del usuario por ID
 $stmt = $pdo->prepare("SELECT * FROM usuario WHERE id = ?");
 $stmt->execute([$usuario_id]);
 $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}

// Procesar formulario si se envían cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $edad = trim($_POST['edad'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $password_actual = $_POST['password_actual'] ?? '';
    $password_nuevo = $_POST['password_nuevo'] ?? '';

    if (!$nombre || !$edad || !$telefono) {
        $_SESSION['mensaje_error'] = "Todos los campos obligatorios deben completarse.";
        header("Location: perfil.php");
        exit;
    }

    // SUBIDA DE FOTO
    $fotoNombre = $usuario['foto'] ?? null; // Mantener la actual por defecto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $extPermitidas = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $extPermitidas)) {
            $_SESSION['mensaje_error'] = "Formato de imagen no permitido (solo JPG, JPEG, PNG).";
            header("Location: perfil.php");
            exit;
        }

        $telefonoLimpio = preg_replace('/\D/', '', $telefono);
        $fotoNombre = $telefonoLimpio . '.' . $ext;
        $rutaDestino = __DIR__ . '/uploads/usuarios/' . $fotoNombre;

        // Borrar foto anterior si existe y es diferente a la nueva
        if (!empty($usuario['foto']) && $usuario['foto'] !== $fotoNombre && file_exists(__DIR__ . '/uploads/usuarios/' . $usuario['foto'])) {
            unlink(__DIR__ . '/uploads/usuarios/' . $usuario['foto']);
        }

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
            $_SESSION['mensaje_error'] = "Error al subir la imagen.";
            header("Location: perfil.php");
            exit;
        }
    }

    // CAMBIO DE CONTRASEÑA
    $passwordHash = $usuario['password']; // por defecto la contraseña actual
    if (!empty($password_actual) || !empty($password_nuevo)) {
        if (!$password_actual || !$password_nuevo) {
            $_SESSION['mensaje_error'] = "Para cambiar la contraseña debes completar ambos campos.";
            header("Location: perfil.php");
            exit;
        }
        // Verificar contraseña actual
        if (!password_verify($password_actual, $usuario['password'])) {
            $_SESSION['mensaje_error'] = "La contraseña actual es incorrecta.";
            header("Location: perfil.php");
            exit;
        }
        // Hashear nueva contraseña
        $passwordHash = password_hash($password_nuevo, PASSWORD_DEFAULT);
    }

    // ACTUALIZAR BASE DE DATOS
    try {
        $stmt = $pdo->prepare("UPDATE usuario SET nombre=:nombre, edad=:edad, telefono=:telefono, foto=:foto, password=:password WHERE id=:id");
        $stmt->execute([
            ':nombre' => $nombre,
            ':edad' => $edad,
            ':telefono' => $telefono,
            ':foto' => $fotoNombre,
            ':password' => $passwordHash,
            ':id' => $usuario['id']
        ]);

        // Actualizar sesión si cambió el nombre
        $_SESSION['nombre'] = $nombre;

        $_SESSION['mensaje_exito'] = "Perfil actualizado correctamente.";
        header("Location: perfil.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['mensaje_error'] = "Error al actualizar el perfil: " . $e->getMessage();
        header("Location: perfil.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php $tituloPagina = "Mi Perfil"; ?>
    <?php include 'head.php'; ?>
</head>
<body>

<!-- Estructura idéntica a testimonio.php -->
<div class="container">

    <header>
        <div class="titulo-con-logo">
            <!-- Título en el header, igual que en el resto de secciones -->
            <h1 class="titulo-principal">Mi Perfil</h1>
        </div>
        <!-- Navegación dentro del header -->
        <?php include 'nav.php'; ?>
    </header>

    <main>

        <!-- Mensajes de sesión -->
        <?php if (isset($_SESSION['mensaje_exito'])): ?>
            <div class="mensaje-exito"><?= $_SESSION['mensaje_exito']; ?></div>
            <?php unset($_SESSION['mensaje_exito']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['mensaje_error'])): ?>
            <div class="mensaje-error"><?= $_SESSION['mensaje_error']; ?></div>
            <?php unset($_SESSION['mensaje_error']); ?>
        <?php endif; ?>

        <!-- Formulario con estilos genéricos de form -->
        <form action="" method="post" enctype="multipart/form-data">
            
            <!-- Foto de Perfil -->
            <div class="foto-perfil">
                <?php if (!empty($usuario['foto']) && file_exists(__DIR__ . '/uploads/usuarios/' . $usuario['foto'])): ?>
                    <img src="uploads/usuarios/<?= htmlspecialchars($usuario['foto']); ?>" alt="Foto de <?= htmlspecialchars($usuario['nombre']); ?>">
                <?php else: ?>
                    <!-- Imagen por defecto si no tiene -->
                    <img src="https://picsum.photos/seed/<?= $usuario['id']; ?>/150/150" alt="Avatar por defecto">
                <?php endif; ?>
            </div>

            <div class="bloque-form">
                <label for="nombre">Nombre completo</label>
                <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($usuario['nombre']); ?>" required>
            </div>

            <div class="bloque-form">
                <label for="edad">Edad</label>
                <input type="number" name="edad" id="edad" value="<?= htmlspecialchars($usuario['edad']); ?>" required>
            </div>

            <div class="bloque-form">
                <label for="telefono">Teléfono</label>
                <input type="tel" name="telefono" id="telefono" value="<?= htmlspecialchars($usuario['telefono']); ?>" required>
            </div>

            <div class="bloque-form">
                <label for="foto">Actualizar foto</label>
                <input type="file" name="foto" id="foto">
            </div>

            <!-- Separador elegante -->
            <div class="divider"></div>

            <div class="bloque-form">
                <label for="password_actual">Contraseña actual</label>
                <input type="password" id="password_actual" name="password_actual" placeholder="Dejar vacío si no deseas cambiarla">
            </div>

            <div class="bloque-form">
                <label for="password_nuevo">Nueva contraseña</label>
                <input type="password" id="password_nuevo" name="password_nuevo" placeholder="Dejar vacío si no deseas cambiarla">
            </div>

            <div class="contenedor-botones">
                <button type="submit"><span>Guardar cambios</span></button>
                <a href="index.php" class="btn-atras"><span>Volver</span></a>
            </div>

        </form>

    </main>
</div> <!-- Fin container -->

<div id="footer"></div>
<script src="js/footer.js"></script>
</body>
</html>