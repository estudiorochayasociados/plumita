<?php
$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$categoria = new Clases\Categorias();
$usuario = new Clases\Usuarios();

$categoriasData = $categoria->list((["area='productos'"]), "", "");
$carro = $carrito->return();
if (isset($_GET['logout'])) {
    $usuario->logout();
    $f->headerMove(URL);
}
?>
<!--================Menu Area =================-->
<header class="shop_header_area carousel_menu_area">
    <div class="carousel_menu_inner pb-10">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="navbar-brand d-none d-md-block" style="width:15%;height:100px;background:url(<?= LOGO ?>) no-repeat center center/contain;">
                </div>
                <div class="navbar-brand d-sm-none" style="width:65%;height:100px;background:url(<?= LOGO ?>) no-repeat center center/contain;">
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"><a class="nav-link fs-12" href="<?= URL . '/' ?>">Inicio</a></li>
                        <li class="nav-item">
                            <a class="nav-link fs-12" href="<?= URL . '/c/empresa' ?>">
                                Empresa
                            </a>
                        </li>
                        <li class="nav-item dropdown submenu">
                            <a class="nav-link dropdown-toggle fs-12" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Productos <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <?php foreach ($categoriasData as $cats) { ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?= URL . '/productos/c/' . $f->normalizar_link($cats['data']['titulo']) ?>">
                                            <?= mb_strtoupper($cats['data']['titulo']) ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link fs-12" href="<?= URL . '/blogs' ?>">Blog</a></li>
                        <li class="nav-item"><a class="nav-link fs-12" href="<?= URL . '/service' ?>">Service</a></li>
                        <li class="nav-item"><a class="nav-link fs-12" href="<?= URL . '/contacto' ?>">Contacto</a></li>
                        <li class="nav-item dropdown submenu">
                            <a class="nav-link dropdown-toggle fs-12" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Catálogos <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="nav-item">
                                    <a class="nav-link fs-12" target="_blank" href="<?= URL . '/assets/archivos/catalogo.pdf' ?>">PRODUCTOS</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fs-12" target="_blank" href="<?= URL . '/assets/archivos/catalogo-repuestos.pdf' ?>">REPUESTOS</a>
                                </li>
                            </ul>
                        </li>
                        <?php if (!empty($_SESSION['usuarios'])) { ?>
                            <li class="nav-item d-md-none"><a class="nav-link" href="<?= URL ?>/sesion">Mi cuenta</a></li>
                        <?php } else { ?>
                            <li class="nav-item d-md-none"><a class="nav-link" href="<?= URL ?>/usuarios">Iniciar sesion</a></li>
                        <?php } ?>
                        <!--                        <li class="nav-item d-md-none"><a class="nav-link" href="-->
                        <? //= URL . '/carrito'; ?>
                        <!--">Carrito</a></li>-->
                    </ul>
                    <ul class="navbar-nav justify-content-end">
                        <li class="search_icon"><a title="Buscar productos" href="<?= URL ?>/productos#buscar"><i class="icon-magnifier icons"></i></a></li>
                        <?php if (!empty($_SESSION['usuarios'])) { ?>
                            <li class="user_icon">
                                <a title="Cuenta" href="<?= URL ?>/sesion"><i class="icon-user icons"></i>
                                </a>
                            </li>
                        <?php } else { ?>
                            <li class="user_icon">
                                <a href="<?= URL ?>/usuarios"><i class="icon-user icons"></i>
                                </a>
                            </li>
                        <?php } ?>
                        <li>
                            <a title="Carrito" href="<?= URL ?>/carrito"><i class="icon-bag icons"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</header>
<!--================End Menu Area =================-->