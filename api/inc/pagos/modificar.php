<?php
$pagos = new Clases\Pagos();
$funciones=new Clases\PublicFunction();
$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$pagos->set("cod", $cod);
$pagos_ = $pagos->view();

if (isset($_POST["agregar"])) {
    $pagos->set("cod", $pagos_["cod"]);
    $pagos->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : $pagos_["titulo"]));
    $pagos->set("leyenda", $funciones->antihack_mysqli(isset($_POST["leyenda"]) ? $_POST["leyenda"] : $pagos_["leyenda"]));
    $pagos->set("estado", $funciones->antihack_mysqli(isset($_POST["estado"]) ? $_POST["estado"] : $pagos_["estado"]));
    $pagos->set("aumento", $funciones->antihack_mysqli(isset($_POST["aumento"]) ? $_POST["aumento"] : $pagos_["aumento"]));
    $pagos->set("disminuir", $funciones->antihack_mysqli(isset($_POST["disminuir"]) ? $_POST["disminuir"] : $pagos_["disminuir"]));
    $pagos->set("defecto", $funciones->antihack_mysqli(isset($_POST["defecto"]) ? $_POST["defecto"] : $pagos_["defecto"]));
    if ($pagos->edit()){
        $array = array("status" => true);
    }else{
        $array = array("status" => false);
    }
echo json_encode($array,JSON_PRETTY_PRINT);
}
?>
