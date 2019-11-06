<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$serv = new Clases\ServicioTecnico();


var_dump($serv->list('','id ASC','1'));