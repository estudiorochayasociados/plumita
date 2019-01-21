<?php
$usuarios = new Clases\Usuarios();
echo json_encode($usuarios->list(""),JSON_PRETTY_PRINT);
?>