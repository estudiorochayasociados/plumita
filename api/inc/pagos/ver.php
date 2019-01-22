<?php
$pagos = new Clases\Pagos();
$data = $pagos->list("");
echo json_encode($data, JSON_PRETTY_PRINT);
?>