<?php
$envios = new Clases\Envios();
$funciones = new Clases\PublicFunction();
if (isset($_POST["agregar"])) {
    $count = 0;
    $cod   = substr(md5(uniqid(rand())), 0, 10);
    $envios->set("cod", $cod);
    $envios->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $envios->set("peso", $funciones->antihack_mysqli(isset($_POST["peso"]) ? $_POST["peso"] : ''));
    $envios->set("precio", $funciones->antihack_mysqli(isset($_POST["precio"]) ? $_POST["precio"] : ''));
    if ($envios->add()){
        $array = array("status" => true);
    }else{
        $array = array("status" => false);
    }
echo json_encode($array,JSON_PRETTY_PRINT);
}
?>
