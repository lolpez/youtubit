<?php

require_once 'database.php';

class Video {

    private $pdo;

    public function __CONSTRUCT() {
        try {
            $this->pdo = Singleton::getInstance()->getPDO();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function Listar() {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM video");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
	
	public function Aleatorio() {
        try {
            $stm = $this->pdo->prepare("SELECT pkvideo FROM video ORDER BY RAND() LIMIT 1");
            $stm->execute();
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function Obtener($pkvideo) {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM video WHERE pkvideo= ? ");
            $stm->execute(array($pkvideo));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            return false;
        }
    }

    public function Obtener_Maximo() {
        try {
            $stm = $this->pdo->prepare("SELECT pkvideo FROM video ORDER BY pkvideo DESC LIMIT 1");
            $stm->execute(array());
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            return false;
        }
    }

    public function Obtener_Actual() {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM video WHERE play=1");
            $stm->execute();
            if ($stm->rowCount() != 1){
                $this->Reset();
                $video_actual =  $this->Obtener(1);
                $this->Reproducir($video_actual->pkvideo);
                return $video_actual;
            }
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            return false;
        }
    }

    public function Reproducir($pkvideo){
        $this->Reset();
        try {
            $sql = "UPDATE video SET play=1 WHERE pkvideo=?";
            $this->pdo->prepare($sql)->execute(array($pkvideo));
            return true;
        } catch (exception $e) {
            return false;
        }
    }

    public function Reset(){
        try {
            $sql = "UPDATE video SET play=0 WHERE play=1";
            $this->pdo->prepare($sql)->execute();
            return true;
        } catch (exception $e) {
            return false;
        }
    }

    public function Guardar($datos) {
        try {
            $sql = "INSERT INTO video (titulo,url,id) VALUES (?,?,?)";
            $this->pdo->prepare($sql)->execute(
                array(
                    $datos['titulo'],
                    $datos['url'],
                    $datos['id']
                )
            );
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            return false;
        }
    }
}

?>
