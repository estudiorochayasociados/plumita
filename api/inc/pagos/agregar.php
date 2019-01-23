<?php
$pagos = new Clases\Pagos();
$funciones=new Clases\PublicFunction();
if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = substr(md5(uniqid(rand())), 0, 10);
    $pagos->set("cod", $cod);
    $pagos->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $pagos->set("leyenda", $funciones->antihack_mysqli(isset($_POST["leyenda"]) ? $_POST["leyenda"] : ''));
    $pagos->set("estado", $funciones->antihack_mysqli(isset($_POST["estado"]) ? $_POST["estado"] : ''));
    $pagos->set("aumento", $funciones->antihack_mysqli(isset($_POST["aumento"]) ? $_POST["aumento"] : ''));
    $pagos->set("disminuir", $funciones->antihack_mysqli(isset($_POST["disminuir"]) ? $_POST["disminuir"] : ''));
    $pagos->set("defecto", $funciones->antihack_mysqli(isset($_POST["defecto"]) ? $_POST["defecto"] : ''));
    if ($pagos->add()){
        $array = array("status" => true);
    }else{
        $array = array("status" => false);
    }
echo json_encode($array,JSON_PRETTY_PRINT);
}
?>
