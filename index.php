<?php
// Configuración de CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

// Verificar que los archivos existan antes de incluirlos
$archivosRequeridos = [
    './config.php',
    './AprendizModel.php',
    './AprendizController.php'
];

foreach ($archivosRequeridos as $archivo) {
    if (!file_exists($archivo)) {
        http_response_code(500);
        die(json_encode([
            "status" => "error",
            "mensaje" => "Error: No se encuentra el archivo $archivo"
        ]));
    }
    require_once $archivo;
}

// Si es una solicitud OPTIONS, terminar aquí (para CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Verificar que la clase existe antes de instanciarla
if (!class_exists('AprendizController')) {
    http_response_code(500);
    die(json_encode([
        "status" => "error",
        "mensaje" => "Error: La clase AprendizController no está definida"
    ]));
}

try {
    // Procesar la URL
    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $url_parts = explode('/', trim($url, '/'));

    // Crear el controlador
    $controlador = new AprendizController();

    // Verificar que estamos en la ruta correcta
    if (!isset($url_parts[0]) || $url_parts[0] !== 'ApiAprendiz') {
        throw new Exception('Ruta no válida');
    }

    // Determinar la acción basada en la URL
    if (!isset($url_parts[1]) || empty($url_parts[1])) {
        // Ruta: /ApiAprendiz/ - Mostrar todos los usuarios
        $controlador->mostrarAprendiz();
    } else {
        // Ruta: /ApiAprendiz/{id} - Mostrar usuario específico
        $id = filter_var($url_parts[1], FILTER_VALIDATE_INT);
        if ($id === false) {
            throw new Exception('ID no válido');
        }
        $controlador->mostrarAprendizPorId($id);
    }

} catch (Error $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "mensaje" => "Error interno del servidor: " . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "mensaje" => $e->getMessage()
    ]);
}
?>