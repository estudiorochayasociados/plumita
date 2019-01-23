<?php
$contenidos = new Clases\Contenidos();
$funciones=new Clases\PublicFunction();

if (isset($_POST["agregar"])) {
    $contenidos->set("cod", $funciones->antihack_mysqli(isset($_POST["codigo"]) ? $_POST["codigo"] : ''));
    $contenidos->set("contenido", $funciones->antihack_mysqli(isset($_POST["contenido"]) ? $_POST["contenido"] : ''));
    if ($contenidos->add()){
        $array = array("status" => true);
    }else{
        $array = array("status" => false);
    }
echo json_encode($array,JSON_PRETTY_PRINT);
}
?>