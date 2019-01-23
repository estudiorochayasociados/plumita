<?php
$envios = new Clases\Envios();
$funciones=new Clases\PublicFunction();
$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$envios->set("cod", $cod);
$envios_ = $envios->view();
if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = $envios_["cod"];
    $envios->set("cod", $cod);
    $envios->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : $envios_["titulo"]));
    $envios->set("precio", $funciones->antihack_mysqli(isset($_POST["precio"]) ? $_POST["precio"] : $envios_["precio"]));
    $envios->set("peso", $funciones->antihack_mysqli(isset($_POST["peso"]) ? $_POST["peso"] : $envios_["peso"]));
    if ($envios->edit()){
        $array = array("status" => true);
    }else{
        $array = array("status" => false);
    }
echo json_encode($array,JSON_PRETTY_PRINT);
}
?>