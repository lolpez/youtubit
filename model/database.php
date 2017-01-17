<?php

class Singleton {

    private static $instance = null;

    private static $pdo;

    final private function __construct() {
        try {
            self::getPDO();
        } catch (PDOException $e) {
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPDO() {
        if (self::$pdo == null) {
            $bd = 'mysql:host=localhost;port=3306;dbname=youtubit';
            $username = 'root';
            $password = '';
            self::$pdo = new PDO($bd, $username, $password);
            // Habilitar excepciones
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$pdo;
    }

    final protected function __clone() {
        
    }

    function _destructor() {
        self::$pdo = null;
    }

}
?>





