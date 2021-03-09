<?php
require_once dirname(__DIR__)."/Config/Autoload.php";
require_once dirname(__DIR__)."/Clases/Meli.php";
Config\Autoload::run();
if (isset($_SESSION["admin"])) {
    include("config/config.php");
} else {
    $pagesCustom = NULL;
}


$template = new Clases\TemplateAdmin();
$template->set("title", "Admin");
$template->set("description", "Admin");
$template->set("keywords", "Inicio");
$template->favicon = 'img/favicon.png';
$template->themeInit($pagesCustom);
$admin = new Clases\Admin();
$funciones = new Clases\PublicFunction();

if (!isset($_SESSION["admin"])) {
    $admin->loginForm();
} else {
    $op = isset($_GET["op"]) ? $_GET["op"] : 'inicio';
    $accion = isset($_GET["accion"]) ? $_GET["accion"] : 'ver';

    if ($op != '') {
        if ($op == "salir") {
            session_destroy();
            $funciones->headerMove(URLADMIN . "/index.php");
        } else {
            $config = new Clases\Config();
            $meli = new Meli($config->meli["data"]["app_id"], $config->meli["data"]["app_secret"]);
            if ($op != "inicio" && $pagesCustom[$op] != true) {
                $funciones->headerMove(URLADMIN);
            }
            include "inc/" . $op . "/" . $accion . ".php";
        }
    }
}

$template->themeEnd();
