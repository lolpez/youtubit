<?php
require_once 'view/view.php';
require_once 'model/model.php';

class VideoController {

    private $model;
    private $vista;

    public function __CONSTRUCT() {
        $this->model = new Video();
        $this->vista = new VideoView();
    }

    public function Index() {
        $lista = $this->model->Listar();
        $video_actual = $this->model->Obtener_Actual();
        $this->vista->View($lista,$video_actual);
    }

    public function InicializarServer(){
        $video_actual = $this->model->Obtener_Actual();
        $this->vista->ViewServer($video_actual);
    }

    public function Guardar($url) {
        $video = $this->Youtubify($url);
        if ($video) {
            parse_str(parse_url($url)['query'], $query);
            $video_url = $url;
            $video_id = $query['v'];
            $video_title = $video['title'];
            $datos = array(
                'titulo' => $video_title,
                'url' => $video_url,
                'id' => $video_id
            );
            echo json_encode($this->model->Obtener($this->model->Guardar($datos)));
        }else{
            echo json_encode('La url del video no es correcta');
        }
    }

    public function Reproducir($pkvideo){
        $this->model->Reproducir($pkvideo);
        echo json_encode($this->model->Obtener($pkvideo));
    }

    public function Siguiente($pkvideo){
        $siguiente = intval($pkvideo) + 1;
        if ($siguiente > $this->model->Obtener_Maximo()->pkvideo){
            $siguiente = 1;
        }
        $this->Reproducir($siguiente);
    }

    public function Actualizar(){
        echo json_encode($this->model->Obtener_Actual());
    }

    function Youtubify($url) {
        $json_output = file_get_contents("http://www.youtube.com/oembed?url=".$url."&format=json");
        return json_decode($json_output, true);
    }

}
?>