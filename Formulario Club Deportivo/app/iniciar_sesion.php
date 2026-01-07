<?php
session_start();
require_once 'conexion.php';

/* Recoger datos del formulario */
$usuario  = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($usuario) || empty($password)) {
    header('Location: login.php?error=1');
    exit();
}

/* Buscar usuario en la BD */
$stmt = $pdo->prepare(
    "SELECT nombre, password 
     FROM usuario 
     WHERE nombre = ?"
);
$stmt->execute([$usuario]);
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
