<?php
session_start();
require_once 'conexion.php';

$telefono  = $_POST['telefono'] ?? '';
$password  = $_POST['password'] ?? '';

$login_incorrecto = false;

// Validar campos vacíos
if (!empty($telefono) && !empty($password)) {
    // Intentar buscar usuario
    $stmt = $pdo->prepare("SELECT nombre, password FROM usuario WHERE BINARY telefono = ?");
    $stmt->execute([$telefono]);
    $usuarioDB = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuarioDB || !password_verify($password, $usuarioDB['password'])) {
        // Teléfono o contraseña incorrecta
        $login_incorrecto = true;
    }
} else {
    // Si faltan datos también mostramos error
    $login_incorrecto = true;
}

if ($login_incorrecto) {
    // Redirigir con flag de error simple
    header("Location: login.php?error=1");
    exit();
}

// Login correcto
$_SESSION['nombre'] = $usuarioDB['nombre'];
header('Location: index.php');
exit();
