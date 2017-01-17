<?php

class VideoView{

    public function View($lista,$video_actual){
        require_once 'header.php';
        require_once 'reproductor.php';
        require_once 'footer.php';
    }

    public function ViewServer($video_actual){
        require_once 'reproductor-server.php';
    }

}

?>