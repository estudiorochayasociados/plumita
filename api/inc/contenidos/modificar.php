<?php
$contenidos = new Clases\Contenidos();
$funciones=new Clases\PublicFunction();

$id = $funciones->antihack_mysqli(isset($_GET['cod']) ? $_GET['cod'] : '');

$contenidos->set("id", $id);
$data = $contenidos->view();
if (isset($_POST["agregar"])) {
    $contenidos->set("contenido", $funciones->antihack_mysqli(isset($_POST["contenido"]) ? $_POST["contenido"] : $data['contenido']));
    $contenidos->edit();
    if ($contenidos->add()){
        $array = array("status" => true);
    }else{
        $array = array("status" => false);
    }
echo json_encode($array,JSON_PRETTY_PRINT);
}
?>
