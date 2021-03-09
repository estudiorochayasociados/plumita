<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$checkout = new Clases\Checkout();
$carrito = new Clases\Carrito();
$usuario = new Clases\Usuarios();

if (empty($_SESSION["usuarios"]) || $_SESSION['usuarios']['invitado'] == 1) {
    $usuario->logout();
    $funciones->headerMove(URL);
}

$op = isset($_GET["op"]) ? $_GET["op"] : '';
$usuarioSesion = $usuario->viewSession();

$pedidos = $funciones->antihack_mysqli(strpos($_SERVER['REQUEST_URI'], "pedidos"));
$cuenta = $funciones->antihack_mysqli(strpos($_SERVER['REQUEST_URI'], "cuenta"));
if ($pedidos == "" && $cuenta == "") {
    $vacio = "ok";
} else {
    $vacio = NULL;
}
if (isset($_GET['logout'])) {
    $checkout->destroy();
    $usuario->logout();
}

$template->set("title", "PANEL DE USUARIO | " . TITULO);
$template->set("description", "");
$template->set("keywords", "");
$template->set("imagen", LOGO);
$template->set("body", "");
$template->themeInit();
?>
<section class="solid_banner_area">
    <div class="container">
        <div class="solid_banner_inner navegador">
            <h3>Panel de usuario</h3>
            <ul>
                <li><a href="<?= URL ?>/index">Inicio</a></li>
                <li class="current"><a href="<?= URL ?>/sesion">Panel de usuario</a></li>
            </ul>
        </div>
    </div>
</section>
<!--My Account section start-->
<div class="my-account-section section pt-10 pb-100 pb-lg-80 pb-md-70 pb-sm-60 pb-xs-50">
    <div class="container">
        <div class="row">
            <div class="col-md-12 pt-15">
                <div class="row">
                    <div class="col-md-3">
                        <a href="<?= URL ?>/productos" class="btn btn-primary btn btn-lg btn-block mb-15">
                            <i class="fa fa-shopping-cart fa-2x mt-10"></i>
                            <h4 class="blanco">Ir a comprar</h4>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= URL ?>/sesion/pedidos" class="btn btn-primary btn btn-lg btn-block mb-15">
                            <i class="fa fa-list fa-2x mt-10"></i>
                            <h4 class="blanco">Mis Pedidos</h4>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= URL ?>/sesion/cuenta" class="btn btn-primary btn btn-lg btn-block mb-15">
                            <i class="fa fa-edit fa-2x mt-10"></i>
                            <h4 class="blanco">Mis datos</h4>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= URL ?>/sesion?logout" class="btn btn-primary btn btn-lg btn-block mb-15">
                            <i class="fa fa-sign-out fa-2x mt-10"></i>
                            <h4 class="blanco">Salir</h4>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 float-md-right">
                <div class="categories_product_area">
                    <div class="row">
                        <?php
                        $op = isset($_GET["op"]) ? $_GET["op"] : 'pedidos';
                        if ($op != '') {
                            include("assets/inc/sesion/" . $op . ".php");
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--My Account section end-->
<!-- panel-user -->
<div class="container pb-150">

</div>
<?php $template->themeEnd(); ?>