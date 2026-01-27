<?php
require_once 'conexion.php';
header('Content-Type: application/json');

$response = [
    'servicios' => [],
    'noticias' => []
];

try {
    // 1. Obtener Servicios
    // Intentamos obtener nombre, precio y duración. 
    // Si tu tabla usa 'descripcion' como nombre, lo manejamos aquí.
    $stmtServicios = $pdo->query("SELECT * FROM servicio");
    $servicios = $stmtServicios->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($servicios as $s) {
        // Usamos 'nombre' si existe, si no 'descripcion'
        $nombre = $s['nombre'] ?? $s['descripcion'];
        $precio = floatval($s['precio']) == 0 ? "Gratis" : number_format($s['precio'], 2) . "€";
        
        $response['servicios'][] = [
            'nombre' => $nombre,
            'precio' => $precio,
            'duracion' => $s['duracion']
        ];
    }

    // 2. Obtener Últimas Noticias (títulos y fechas)
    $stmtNoticias = $pdo->query("SELECT titulo, fecha_publicacion FROM noticia WHERE fecha_publicacion <= NOW() ORDER BY fecha_publicacion DESC LIMIT 3");
    $response['noticias'] = $stmtNoticias->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
?>