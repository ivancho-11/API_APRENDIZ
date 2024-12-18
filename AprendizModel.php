<?php
class AprendizModel {
    private static $db = null;
    private static $host = 'localhost';
    private static $dbname = 'api_aprendiz'; // Asegúrate de cambiar esto
    private static $username = 'root';
    private static $password = '';

    public static function conectarDB() {
        if (self::$db === null) {
            try {
                self::$db = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbname,
                    self::$username,
                    self::$password,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                return true;
            } catch (PDOException $e) {
                error_log("Error de conexión: " . $e->getMessage());
                return false;
            }
        }
        return true;
    }

    // Método para obtener todos los usuarios
    public static function buscarAprendiz() {
        if (!self::conectarDB()) {
            return false;
        }
        
        try {
            $stmt = self::$db->query("SELECT * FROM usuarios");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return false;
        }
    }

    // Método para buscar un usuario por su ID
    public static function buscaridAprendiz($id) {
        if (!self::conectarDB()) {
            return false;
        }
        
        try {
            $stmt = self::$db->prepare("SELECT * FROM usuarios WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return false;
        }
    }

    public static function cerrarConexion() {
        self::$db = null;
    }
}
?>