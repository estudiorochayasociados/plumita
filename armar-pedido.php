<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$categoria = new Clases\Categorias();
$producto = new Clases\Productos();
$usuario = new Clases\Usuarios();
$carrito = new Clases\Carrito();
$usuarios = new Clases\Usuarios();

$usuarioSesion = $usuarios->view_sesion();
if (empty($usuarioSesion)) {
    $funciones->headerMove(URL . "/index");
}
$template->set("title", TITULO . " | Armar pedido");
$template->set("description", "Armar pedido " . TITULO);
$template->set("keywords", "Armar pedido " . TITULO);
$template->set("favicon", FAVICON);
$template->themeInit();

///Datos
$carro = $carrito->return();
$url_limpia = CANONICAL;
$url_limpia = str_replace("?success", "", $url_limpia);
$url_limpia = str_replace("?error", "", $url_limpia);
//Gets
$page = $funciones->antihack_mysqli(isset($_GET["pagina"]) ? $_GET["pagina"] : '0');
$titulo = $funciones->antihack_mysqli(isset($_GET["buscar"]) ? $_GET["buscar"] : '');

$cantidad = 50;
if ($page > 0) {
    $page = $page - 1;
}
if ($_GET) {
    if (@count($_GET) > 1) {
        if (isset($_GET["pagina"])) {
            $anidador = "&";
        } else {
            $anidador = "&";
        }
    } else {
        if (isset($_GET["pagina"])) {
            $anidador = "?";
        } else {
            $anidador = "&";
        }
    }
} else {
    $anidador = "?";
}
///Datos
$filter = [];
if (!empty($titulo)) {
    array_push($filter, "(titulo LIKE '%$titulo%' || cod_producto LIKE '%$titulo%')");
}
if (empty($filter)) $filter = '';
$productsData = $producto->listWithOps($filter, 'id ASC', ($cantidad * $page) . ',' . $cantidad);
if (!empty($productsData)) {
    $totalPages = $producto->paginador($filter, $cantidad);
}
//
//Eliminar
if (isset($_GET["remover"])) {
    $carroPago = $carrito->checkPago();
    if ($carroPago != '') {
        $carrito->delete($carroPago);
    }
    $carroEnvio = $carrito->checkEnvio();
    if ($carroEnvio != '') {
        $carrito->delete($carroEnvio);
    }
    $carrito->delete($_GET["remover"]);
    $funciones->headerMove(URL . "/armar-pedido");
}
$template->themeNav();
?>
<!-- BREADCRUMBS -->
<section class="solid_banner_area">
    <div class="container">
        <div class="solid_banner_inner navegador">
            <h3>Armar pedido</h3>
            <ul>
                <li><a href="<?= URL ?>/index">Inicio</a></li>
                <li class="current"><a href="#">Armar pedido</a></li>
            </ul>
        </div>
    </div>
</section>
<!-- END BREADCRUMBS -->
<!--================Product Details Area =================-->
<section class="product_details_area">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
               <h4>Carrito</h4>
                <div id="cartTable" class="mt-10">

                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <form style="width: 100%" method="get">
                        <input type="text" class="form-control d-inline-block" name="buscar" value="<?= $titulo ?>" placeholder="Buscar" style="width: 88%">

                        <button type="submit" class="btn btn-info" style="background-color: #013aa0">Buscar</button>
                    </form>
                </div>
                <hr>
                <?php if (!empty($_SESSION['usuarios']['descuento'])) { ?>
                    <div class="alert alert-success">
                        Usted posee un descuento del <?= $_SESSION['usuarios']['descuento'] ?>%.
                    </div>
                <?php } ?>
                <table class="table table-striped">
                    <thead>
                    <tr id="head">
                        <th>CÓDIGO</th>
                        <th>NOMBRE</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($productsData as $product) { ?>
                        <tr>
                            <th scope="row"><?= $product['data']['cod_producto'] ?></th>
                            <td><?= mb_strtoupper($product['data']['titulo']) ?></td>
                            <td>
                                <a class="btn btn-sm btn-info "
                                   style="background-color: #013aa0;color: #ffffff;"
                                   onclick="addDesc('<?= $product['data']['cod'] ?>')">
                                    VER
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-12">
                        <?php if (!empty($totalPages)) { ?>
                            <div class="list-nav">
                                <ul class="pagination pagination-grid hide-first-last">
                                    <?php
                                    if (!empty($totalPages)) {
                                        if ($totalPages != 1 && $totalPages != 0) {
                                            $url_final = $funciones->eliminar_get(CANONICAL, "pagina");
                                            $links = '';
                                            $links .= "<li class='page'><a class='mr-5' href='" . $url_final . $anidador . "pagina=1'>1</a></li>";
                                            $i = max(2, $page - 5);

                                            if ($i > 2) {
                                                $links .= "<li class='page'><a class='mr-5' href='#'>...</a></li>";
                                            }
                                            for (; $i <= min($page + 6, $totalPages); $i++) {
                                                if ($page + 1 == $i) {
                                                    $current = "active";
                                                } else {
                                                    $current = "";
                                                }
                                                $links .= "<li class='page $current'><a class='mr-5' href='" . $url_final . $anidador . "pagina=" . $i . "'>" . $i . "</a></li>";
                                            }
                                            if ($i - 1 != $totalPages) {
                                                $links .= "<li class='page'><a class='mr-5' href='#'>...</a></li>";
                                                $links .= "<li class='page'><a class='mr-5' href='" . $url_final . $anidador . "pagina=" . $totalPages . "'>" . $totalPages . "</a></li>";
                                            }
                                            echo $links;
                                            echo "";
                                        }
                                    }
                                    ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php foreach ($productsData as $product) { ?>
    <div id="productModal<?= $product['data']['cod'] ?>" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header p-0" style="text-align: right;display: initial;align-items: unset;">
                    <div class="modal-he">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">&times;</button>
                    </div>
                </div>
                <div class="modal-body" style="padding: 0px">
                    <div class="header-base d-pb-0 d-pt-0">
                        <div class="m-title">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="title-base text-left" style="margin: 10px;">
                                        <h3 id="productModalTitle<?= $product['data']['cod'] ?>" class="d-f-25 mt-10"></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overlay-content">
                        <div class="section-empty section-item">
                            <div class=" content pt-15">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="productModalError<?= $product['data']['cod'] ?>">

                                        </div>
                                        <div id="productModalDiv<?= $product['data']['cod'] ?>" class="col-md-12 col-sm-12 portfolio-details">

                                        </div>
                                        <div class="col-md-12 mb-15">
                                            <button class="btn btn-success" onclick="addToCart('<?= $product['data']['cod'] ?>')">
                                                <i class="fa fa-shopping-cart"></i>AGREGAR AL CARRITO
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div id="modalS" class="modal fade pt-60" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <i class="fa fa-check-circle" style="font-size: 90px;color: green;"></i>
                    <br>
                    <span class="text-uppercase fs-18"> ¡FELICITACIONES! AGREGASTE UN NUEVO PRODUCTO A TU CARRITO:</span>
                    <br/>
                    <a href="<?= URL . '/checkout' ?>" class="btn mt-20 fs-18 text-uppercase btn-success btn-block" style="border:none;"><b>procesar pedido</b></a>

                    <a data-dismiss="modal" class="text-uppercase fs-12"><b>seguir agregando</b></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modalE" class="modal fade pt-60" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <i class="fa fa-exclamation-circle" style="font-size: 90px;color: red;"></i>
                    <br>
                    <span id="error" class="text-uppercase fs-16"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $template->themeEnd(); ?>
<script>
    refreshCart();

    function addDesc(cod) {
        $.ajax({
            url: "<?=URL?>/curl/product/view.php",
            type: "POST",
            data: {cod: cod},
            success: function (data) {
                data = JSON.parse(data);
                if (data['status'] == true) {
                    $('#productModalTitle' + cod).html('');
                    $('#productModalTitle' + cod).html(data['header']);
                    $('#productModalDiv' + cod).html('');
                    $('#productModalDiv' + cod).html(data['response']);
                    $('#productModal' + cod).modal('toggle');
                } else {
                    $('#error').html('');
                    $('#error').append(data['message']);
                    $('#modalE').modal('toggle');
                }
            },
            error: function () {
            }
        });
    }

    function addToCart(cod) {
        if ($('#amount').val() != '' && $('#amount').val() > 0) {
            $.ajax({
                url: "<?=URL?>/curl/cart/add.php",
                type: "POST",
                data: $('#cartForm' + cod).serialize(),
                success: function (data) {
                    $('#productModalError' + cod).html('');
                    data = JSON.parse(data);
                    if (data['status'] == true) {
                        $('#productModal' + cod).modal('toggle');
                        $('#modalS').modal('toggle');
                        refreshCart();
                    } else {
                        $('#error').html('');
                        $('#error').append(data['message']);
                        $('#modalE').modal('toggle');
                    }
                },
                error: function () {
                    $('#productModalError' + cod).html('');
                    $('#productModalError' + cod).append('<div class="col-md-12"><div class="alert alert-warning">Ocurrió un error, recargar la página.</div></div>');
                }
            });
        } else {
            $('#productModalError' + cod).html('');
            $('#productModalError' + cod).append('<div class="col-md-12"><div class="alert alert-warning">INGRESAR UNA CANTIDAD CORRECTA.</div></div>');
        }
    }

    function refreshCart() {
        $.ajax({
            url: "<?=URL?>/curl/cart/view.php",
            type: "POST",
            success: function (data) {
                data = JSON.parse(data);
                if (data['status'] == true) {
                    $('#cartTable').html('');
                    $('#cartTable').html(data['texto']);
                } else {
                    //$('#cartTable').html('');
                    //$('#cartTable').html(data['texto']);
                }
            },
            error: function () {
            }
        });
    }
</script>
