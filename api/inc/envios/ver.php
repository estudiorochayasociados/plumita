<?php
$envios = new Clases\Envios();
echo json_encode($envios->list(""),JSON_PRETTY_PRINT);
?>