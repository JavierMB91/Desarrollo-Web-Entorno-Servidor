<?php
header('Content-Type: application/json; charset=utf-8');

$apiKey = getenv('GOOGLE_BOOKS_API_KEY');
if (!$apiKey) {
    http_response_code(500);
    echo json_encode(['error' => 'La API key de Google Books no está configurada en el servidor.']);
    exit;
}

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$maxResults = isset($_GET['maxResults']) ? (int) $_GET['maxResults'] : 40;

if ($query === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Parámetro q requerido.']);
    exit;
}

if ($maxResults < 1) {
    $maxResults = 1;
} elseif ($maxResults > 40) {
    $maxResults = 40;
}

$googleUrl = 'https://www.googleapis.com/books/v1/volumes?q=' . urlencode($query)
    . '&maxResults=' . $maxResults
    . '&key=' . urlencode($apiKey);

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'timeout' => 10,
        'ignore_errors' => true,
    ],
]);

$response = @file_get_contents($googleUrl, false, $context);

if ($response === false) {
    http_response_code(502);
    echo json_encode(['error' => 'No se pudo conectar con Google Books.']);
    exit;
}

$statusCode = 200;
if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $matches)) {
    $statusCode = (int) $matches[1];
}

http_response_code($statusCode);
echo $response;
