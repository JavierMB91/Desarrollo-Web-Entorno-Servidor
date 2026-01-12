<?php
// $host = getenv("DB_HOST");
// $db   = getenv("DB_NAME");
// $user = getenv("DB_USER");
// $pass = getenv("DB_PASSWORD");

$host = ("sql100.infinityfree.com");
$db   = ("if0_40888308_mi_aplicacion");
$user = ("if0_40888308");
$pass = ("M5tkkw8ww");

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    // Mostrar un mensaje amigable o registrar el error
    echo '<p class="error">❌ No se pudo conectar a la base de datos</p>';
    error_log($e->getMessage());
}
