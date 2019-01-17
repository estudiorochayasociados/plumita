<?php
$carrito = new Clases\Carrito();
$carro = $carrito->return();
?>
<!--================Menu Area =================-->
<header class="shop_header_area carousel_menu_area">
    <div class="carousel_top_header row m0">
        <div class="container">
            <div class="carousel_top_h_inner">
                <div class="float-md-left">
                    <div class="top_header_left">
                        <?php
                        $data_in = "http://ws.geeklab.com.ar/dolar/get-dolar-json.php";
                        $data_json = @file_get_contents($data_in);
                        if (strlen($data_json) > 0) {
                            $data_out = json_decode($data_json, true);

                            if (is_array($data_out)) {
                                if (isset($data_out['libre'])) echo "Dolar: $" . $data_out['libre'] . "<br>\n";
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="float-md-right">
                    <div class="top_header_middle">
                        <a href="#"><i class="fa fa-phone"></i>Telefono: <span><?= TELEFONO ?></span></a>
                        <a href="#"><i class="fa fa-envelope"></i> Email: <span><?= EMAIL ?></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="carousel_menu_inner">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="navbar-brand" style="width:15%;height:100px;background:url(<?= LOGO ?>) no-repeat center center/contain;">
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"><a class="nav-link" href="<?= URL . '/index' ?>">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= URL . '/productos' ?>">Productos</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= URL . '/blogs' ?>">Blog</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= URL . '/c/empresa' ?>">Sobre nosotros</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= URL . '/contacto' ?>">Contacto</a></li>
                    </ul>
                    <ul class="navbar-nav justify-content-end">
                        <li class="search_icon"><a title="Buscar productos" href="<?= URL ?>/productos#buscar"><i class="icon-magnifier icons"></i></a></li>
                        <?php if (@count($_SESSION["usuarios"]) != 0): ?>
                            <li class="user_icon">
                                <a title="Cuenta" href="<?= URL ?>/sesion"><i class="icon-user icons"></i>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="user_icon">
                                <a data-toggle="modal" data-target="#login"
                                   title="Iniciar sesion"><i class="icon-user icons"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li><a title="Carrito" href="<?= URL . '/carrito'; ?>"><i class="icon-handbag icons"></i> <?php if (!empty($carro)){echo @count($carro);} ?></a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</header>
<!--================End Menu Area =================-->
