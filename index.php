<?php
    require_once ("controller.php");
    $controller = new VideoController();
    if (isset($_GET['web_service'])){
        switch($_GET['web_service']){
            case 'guardar':
                $controller->Guardar($_POST['url']);
                return;
                break;
            case 'reproducir':
                $controller->Reproducir($_POST['pkvideo']);
                return;
                break;
            case 'siguiente':
                $controller->Siguiente($_POST['pkvideo']);
                return;
                break;
            case 'actualizar':
                $controller->Actualizar();
                return;
                break;
            default:
                $controller->Index();
                break;
        }
    }
    $controller->Index();
?>