<?php
session_start();
require_once 'conexion.php';

$nombre  = $_POST['nombre'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($nombre) || empty($password)) {
    header('Location: login.php?error=1');
    exit();
}

/* Buscar usuario en la BD */
$stmt = $pdo->prepare(
    "SELECT nombre, password 
     FROM usuario 
     WHERE nombre = ?"
);
$stmt->execute([$nombre]);
$usuarioDB = $stmt->fetch(PDO::FETCH_ASSOC);

/* Verificar contraseña */
if ($usuarioDB && password_verify($password, $usuarioDB['password'])) {
    $_SESSION['usuario'] = $usuarioDB['nombre'];
    header('Location: index.php');
    exit();
} else {
    header('Location: login.php?error=1');
    exit();
}

