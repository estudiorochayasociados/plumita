<?php
$landing = new Clases\Landing();
$funciones = new Clases\PublicFunction();
$filter = isset($_POST["filter"]) ? $_POST["filter"] : '';
$data = $landing->list($filter);
$data_ = array();
foreach ($data as $d) {
    $d_ = array("id" => $d['id'], "cod" => $d['cod'], "titulo" => $d['titulo'], "link" => URLSITE . "/landing/" . $funciones->normalizar_link($d["titulo"]) . "/" . $d["cod"]);
    array_push($data_, $d_);
}
echo json_encode($data_, JSON_PRETTY_PRINT);

?>