<?php
class AprendizController {
    public function __construct() {
        // Constructor vacío o con la inicialización que necesites
    }

    public function mostrarAprendiz() {
        try {
            $usuarios = AprendizModel::buscarAprendiz();
            if (!$usuarios) {
                http_response_code(404);
                echo json_encode([
                    "status" => "error",
                    "mensaje" => "No hay datos disponibles"
                ]);
                return;
            }
            echo json_encode([
                "status" => "success",
                "data" => $usuarios
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "mensaje" => "Error al obtener los datos"
            ]);
        }
    }

    public function mostrarAprendizPorId($id) {
        try {
            $usuario = AprendizModel::buscaridAprendiz($id);
            if (!$usuario) {
                http_response_code(404);
                echo json_encode([
                    "status" => "error",
                    "mensaje" => "Usuario no encontrado"
                ]);
                return;
            }
            echo json_encode([
                "status" => "success",
                "data" => $usuario
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "mensaje" => "Error al obtener el usuario"
            ]);
        }
    }
}
?>